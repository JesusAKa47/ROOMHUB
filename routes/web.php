<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\CuartosController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\BecomeHostController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\ApartmentController as OwnerApartmentController;
use App\Http\Controllers\Admin\{
    ApartmentController,
    ClientController,
    HostVerificationController,
    OwnerController,
    ReportController,
    UserController as AdminUserController
};
use App\Models\{Apartment, Owner, Client, Reservation};

Route::get('/test-brevo', function () {
    \Illuminate\Support\Facades\Mail::raw('Correo de prueba funcionando con Brevo + RoomHub', function ($m) {
        $m->to('tu-correo-real@gmail.com')->subject('Prueba Brevo');
    });

    return "Si no hay error, el correo debe llegar en los próximos segundos.";
});

// Servir archivos de storage (siempre por Laravel, evita problemas con symlink en local/Windows)
Route::get('files/{path}', function (string $path) {
    $path = str_replace(['../', '..\\'], '', $path);
    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }
    $fullPath = Storage::disk('public')->path($path);
    $mime = match (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
        'jpg', 'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'pdf' => 'application/pdf',
        default => 'application/octet-stream',
    };
    return response()->file($fullPath, ['Content-Type' => $mime]);
})->where('path', '.*')->name('storage.serve');

// Página principal pública
Route::get('/', fn () => view('welcome'));

// Dashboard: admin va a su panel; dueños con ambos modos pueden elegir; resto ve inicio
Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();
    if ($user->isAdmin()) {
        return redirect()->route('admin.index');
    }
    if ($user->isOwner() && !$user->client_id) {
        return redirect()->route('owner.dashboard');
    }
    $featuredApartments = Apartment::with('owner')
        ->where('status', 'activo')
        ->orderBy('available_from', 'desc')
        ->take(6)
        ->get();
    $totalApartments = Apartment::where('status', 'activo')->count();
    return view('dashboard', compact('featuredApartments', 'totalApartments'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas según rol (auth)
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::get('/profile/payment-methods', [ProfileController::class, 'stripePortal'])->name('profile.payment-methods');
    Route::post('/profile/activate-client', [ProfileController::class, 'activateClientMode'])->name('profile.activate-client');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/two-factor/setup', [TwoFactorController::class, 'showSetup'])->name('profile.two-factor.setup');
    Route::get('/profile/two-factor/qr', [TwoFactorController::class, 'qrImage'])->name('profile.two-factor.qr');
    Route::post('/profile/two-factor/confirm', [TwoFactorController::class, 'confirmSetup'])->name('profile.two-factor.confirm');
    Route::post('/profile/two-factor/disable', [TwoFactorController::class, 'disable'])->name('profile.two-factor.disable');
    Route::get('/quienes-somos', function () {
        return view('quienes-somos');
    })->name('quienes');

    // Mensajería básica entre usuarios (cliente ↔ anfitrión)
    Route::get('/mensajes', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/mensajes', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/mensajes/report', [MessageController::class, 'report'])->name('messages.report');

    // Notificaciones internas
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // --- Convertirse en anfitrión: verificación legal (INE + preguntas) → luego completar perfil ---
    Route::get('/become-host', [BecomeHostController::class, 'show'])->name('become-host.show');
    Route::post('/become-host/verification', [BecomeHostController::class, 'storeVerification'])->name('become-host.verification.store');
    Route::get('/become-host/complete', [BecomeHostController::class, 'showComplete'])->name('become-host.complete');
    Route::post('/become-host', [BecomeHostController::class, 'store'])->name('become-host.store');

    // --- Cuartos y reservas (pago con Stripe) ---
    Route::get('/cuartos', [CuartosController::class, 'index'])->name('cuartos.index');
    Route::get('/cuartos/mapa', [CuartosController::class, 'mapa'])->name('cuartos.mapa');
    Route::get('/cuartos/{apartment}', [CuartosController::class, 'show'])->name('cuartos.show');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/toggle/{apartment}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::post('/cuartos/{apartment}/comments', [CuartosController::class, 'storeComment'])->name('cuartos.comments.store');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::post('/reservations/{reservation}/confirm-payment', [ReservationController::class, 'confirmPayment'])->name('reservations.confirm-payment');
    Route::get('/reservations/{reservation}/success', [ReservationController::class, 'success'])->name('reservations.success');
    Route::get('/reservations/{reservation}/ticket-pdf', [ReservationController::class, 'ticketPdf'])->name('reservations.ticket-pdf');
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::get('/reservations/history', [ReservationController::class, 'history'])->name('reservations.history');
    Route::get('/reservations/history/pdf', [ReservationController::class, 'historyPdf'])->name('reservations.history.pdf');

    // --- Dueño: dashboard y CRUD de sus departamentos ---
    Route::middleware('role:owner')->prefix('owner')->name('owner.')->group(function () {
        Route::get('dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
        Route::get('apartments', [OwnerApartmentController::class, 'index'])->name('apartments.index');
        Route::get('apartments/create', [OwnerApartmentController::class, 'create'])->name('apartments.create');
        Route::post('apartments', [OwnerApartmentController::class, 'store'])->name('apartments.store');
        Route::get('apartments/{apartment}/edit', [OwnerApartmentController::class, 'edit'])->name('apartments.edit');
        Route::put('apartments/{apartment}', [OwnerApartmentController::class, 'update'])->name('apartments.update');
        Route::delete('apartments/{apartment}', [OwnerApartmentController::class, 'destroy'])->name('apartments.destroy');
    });

    // --- Admin: panel completo (solo role admin) ---
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {

        Route::get('/', function () {
            $today = now()->startOfDay();
            $inDays = 14;

            $paidReservations = Reservation::with('apartment')
                ->where('status', 'paid')
                ->orderByDesc('updated_at')
                ->limit(25)
                ->get();

            // Ocupación: reservas pagadas con estancia activa hoy
            $occupancyActive = Reservation::with(['apartment', 'client'])
                ->where('status', 'paid')
                ->whereDate('check_in', '<=', $today)
                ->whereDate('check_out', '>=', $today)
                ->orderBy('check_out')
                ->get();

            // Próximos check-in (entradas en los próximos N días)
            $upcomingCheckIns = Reservation::with(['apartment', 'client'])
                ->where('status', 'paid')
                ->whereDate('check_in', '>', $today)
                ->whereDate('check_in', '<=', $today->copy()->addDays($inDays))
                ->orderBy('check_in')
                ->limit(15)
                ->get();

            // Próximos check-out (salidas en los próximos N días)
            $upcomingCheckOuts = Reservation::with(['apartment', 'client'])
                ->where('status', 'paid')
                ->whereDate('check_out', '>=', $today)
                ->whereDate('check_out', '<=', $today->copy()->addDays($inDays))
                ->orderBy('check_out')
                ->limit(15)
                ->get();

            $admin = request()->user();
            $adminNotifications = $admin->notifications()->limit(10)->get();

            return view('admin.index', [
                'stats' => [
                    'apartments' => Apartment::count(),
                    'owners'     => Owner::count(),
                    'clients'    => Client::count(),
                    'verifications_pending' => \App\Models\HostVerification::whereIn('status', ['pendiente', 'en_revision'])->count(),
                    'revenue_total' => Reservation::where('status', 'paid')->sum('total_amount_cents') / 100,
                    'revenue_count' => Reservation::where('status', 'paid')->count(),
                ],
                'paidReservations' => $paidReservations,
                'occupancyActive' => $occupancyActive,
                'upcomingCheckIns' => $upcomingCheckIns,
                'upcomingCheckOuts' => $upcomingCheckOuts,
                'adminNotifications' => $adminNotifications,
            ]);
        })->name('index');

        Route::get('finances', [ReportController::class, 'finances'])->name('finances.index');
        Route::get('reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
        Route::get('reports/revenue/excel', [ReportController::class, 'revenueExcel'])->name('reports.revenue.excel');
        Route::get('reports/apartments', [ReportController::class, 'apartments'])->name('reports.apartments');

        Route::get('apartments', [ApartmentController::class, 'index'])->name('apartments.index');
        Route::get('apartments/create', [ApartmentController::class, 'create'])->name('apartments.create');
        Route::post('apartments', [ApartmentController::class, 'store'])->name('apartments.store');
        Route::get('apartments/{apartment}/edit', [ApartmentController::class, 'edit'])->name('apartments.edit');
        Route::put('apartments/{apartment}', [ApartmentController::class, 'update'])->name('apartments.update');
        Route::delete('apartments/{apartment}', [ApartmentController::class, 'destroy'])->name('apartments.destroy');

        Route::resource('owners', OwnerController::class)->except('show');
        Route::resource('clients', ClientController::class)->except('show');

        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('users/{user}/activity', [AdminUserController::class, 'activity'])->name('users.activity');
        Route::post('users/{user}/block', [AdminUserController::class, 'block'])->name('users.block');
        Route::post('users/{user}/unblock', [AdminUserController::class, 'unblock'])->name('users.unblock');
        Route::post('users/{user}/send-password-reset', [AdminUserController::class, 'sendPasswordResetLink'])->name('users.send-password-reset');

        Route::get('verifications', [HostVerificationController::class, 'index'])->name('verifications.index');
        Route::get('verifications/{verification}', [HostVerificationController::class, 'show'])->name('verifications.show');
        Route::post('verifications/{verification}/approve', [HostVerificationController::class, 'approve'])->name('verifications.approve');
        Route::post('verifications/{verification}/reject', [HostVerificationController::class, 'reject'])->name('verifications.reject');
        Route::post('verifications/{verification}/set-under-review', [HostVerificationController::class, 'setUnderReview'])->name('verifications.set-under-review');
    });
});

require __DIR__ . '/auth.php';
