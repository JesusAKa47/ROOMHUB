<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Reservation;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NewReservationNotification;
use App\Notifications\ReservationPaidNotification;
use App\Notifications\ReservationCancelledNotification;
use App\Notifications\OwnerReservationPaidNotification;
use App\Notifications\OwnerReservationCancelledNotification;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Stripe\StripeClient;
use Symfony\Component\HttpFoundation\Response;

class ReservationController extends Controller
{
    /**
     * Crear reserva y devolver client_secret para pagar con tarjeta en página (Stripe Elements).
     * Por día: check_in y check_out obligatorios. Por mes: no se envían fechas; se usa primer mes.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $rules = [
            'apartment_id' => ['required', 'exists:apartments,id'],
            'rent_type' => ['required', 'in:day,month'],
            'guest_notes' => ['nullable', 'string', 'max:500'],
        ];
        if ($request->input('rent_type') === 'day') {
            $rules['check_in'] = ['required', 'date', 'after_or_equal:today'];
            $rules['check_out'] = ['required', 'date', 'after:check_in'];
        }
        $request->validate($rules);

        $apartment = Apartment::findOrFail($request->apartment_id);
        if ($apartment->status !== 'activo') {
            return $request->wantsJson()
                ? response()->json(['message' => 'Este alojamiento no está disponible.'], 422)
                : back()->withErrors(['apartment_id' => 'Este alojamiento no está disponible.']);
        }

        $user = $request->user();
        if (! $user->client_id) {
            return $request->wantsJson()
                ? response()->json(['message' => 'Activa tu modo cliente en el perfil para poder reservar.'], 403)
                : redirect()->route('profile.edit')->with('status', 'Activa tu modo cliente en el perfil para poder reservar.');
        }

        if ($request->rent_type === 'month') {
            $checkIn = Carbon::now()->addMonth()->startOfMonth();
            $checkOut = $checkIn->copy()->addMonth();
            $total = $apartment->monthly_rent;
        } else {
            $checkIn = Carbon::parse($request->check_in);
            $checkOut = Carbon::parse($request->check_out);
            $total = $apartment->daily_rate * $checkIn->diffInDays($checkOut);
        }

        $totalCents = (int) round($total * 100);
        if ($totalCents < 50) {
            return $request->wantsJson()
                ? response()->json(['message' => 'El monto mínimo es 50 centavos.'], 422)
                : back()->withErrors(['check_out' => 'El monto mínimo es 50 centavos. Ajusta las fechas.']);
        }

        $reservation = Reservation::create([
            'apartment_id' => $apartment->id,
            'user_id' => $user->id,
            'client_id' => $user->client_id,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'rent_type' => $request->rent_type,
            'total_amount_cents' => $totalCents,
            'currency' => 'mxn',
            'payment_method' => Reservation::PAYMENT_STRIPE,
            'status' => 'pending',
            'guest_notes' => $request->guest_notes,
        ]);

        foreach (User::where('role', User::ROLE_ADMIN)->get() as $admin) {
            $admin->notify(new NewReservationNotification($reservation));
        }

        $secret = config('services.stripe.secret');
        if (! $secret) {
            $reservation->update(['status' => 'paid']);
            $this->notifyOwnerReservationPaid($reservation);
            foreach (User::where('role', User::ROLE_ADMIN)->get() as $admin) {
                $admin->notify(new ReservationPaidNotification($reservation));
            }
            return $request->wantsJson()
                ? response()->json(['reservation_id' => $reservation->id, 'skip_payment' => true])
                : redirect()->route('reservations.success', $reservation)->with('ok', 'Reserva creada (modo prueba).');
        }

        $stripe = new StripeClient($secret);
        $intent = $stripe->paymentIntents->create([
            'amount' => $totalCents,
            'currency' => 'mxn',
            'automatic_payment_methods' => ['enabled' => true],
            'metadata' => ['reservation_id' => (string) $reservation->id],
        ]);
        $reservation->update(['stripe_payment_intent_id' => $intent->id]);

        return response()->json([
            'client_secret' => $intent->client_secret,
            'reservation_id' => $reservation->id,
        ]);
    }

    /**
     * Marcar reserva como pagada tras confirmar el pago con Stripe en el frontend.
     */
    public function confirmPayment(Request $request, Reservation $reservation): JsonResponse|RedirectResponse
    {
        if ($reservation->user_id !== $request->user()->id) {
            abort(404);
        }
        if ($reservation->status !== 'pending') {
            return $request->wantsJson()
                ? response()->json(['message' => 'La reserva ya fue procesada.'], 400)
                : redirect()->route('reservations.success', $reservation);
        }

        $piId = $reservation->stripe_payment_intent_id;
        if (! $piId) {
            return $request->wantsJson()
                ? response()->json(['message' => 'No hay pago asociado.'], 400)
                : redirect()->route('reservations.success', $reservation);
        }

        $secret = config('services.stripe.secret');
        if ($secret) {
            $stripe = new StripeClient($secret);
            $intent = $stripe->paymentIntents->retrieve($piId);
            if ($intent->status !== 'succeeded') {
                return $request->wantsJson()
                    ? response()->json(['message' => 'El pago no se ha completado.'], 400)
                    : redirect()->route('cuartos.show', $reservation->apartment)->withErrors(['payment' => 'El pago no se completó.']);
            }
        }

        $reservation->update(['status' => 'paid']);
        $this->notifyOwnerReservationPaid($reservation);

        foreach (User::where('role', User::ROLE_ADMIN)->get() as $admin) {
            $admin->notify(new ReservationPaidNotification($reservation));
        }

        return $request->wantsJson()
            ? response()->json(['success' => true, 'redirect' => route('reservations.success', $reservation)])
            : redirect()->route('reservations.success', $reservation);
    }

    /**
     * Página de éxito tras la reserva.
     */
    public function success(Request $request, Reservation $reservation): View|RedirectResponse
    {
        if ($reservation->user_id !== $request->user()->id) {
            abort(404);
        }

        return view('reservations.success', compact('reservation'));
    }

    /**
     * Descargar ticket de la reserva en PDF.
     */
    public function ticketPdf(Request $request, Reservation $reservation): Response
    {
        if ($reservation->user_id !== $request->user()->id) {
            abort(404);
        }

        $reservation->load(['user', 'apartment']);

        $pdf = Pdf::loadView('reservations.ticket-pdf', compact('reservation'))
            ->setPaper('a4', 'portrait');

        $filename = 'ticket-reserva-' . $reservation->id . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Historial de reservas del usuario autenticado.
     */
    public function history(Request $request): View
    {
        $user = $request->user();

        $reservations = Reservation::with(['apartment.owner'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('reservations.history', compact('reservations'));
    }

    /**
     * Historial de reservas del usuario en PDF.
     */
    public function historyPdf(Request $request)
    {
        $user = $request->user();

        $reservations = Reservation::with(['apartment.owner'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        $pdf = Pdf::loadView('reservations.history-pdf', [
            'reservations' => $reservations,
            'user' => $user,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('historial-rentas-' . $user->id . '.pdf');
    }

    /**
     * Cancelar reserva (solo pendientes).
     */
    public function cancel(Request $request, Reservation $reservation): RedirectResponse
    {
        if ($reservation->user_id !== $request->user()->id) {
            abort(404);
        }
        if ($reservation->status === 'pending') {
            $reservation->update(['status' => 'cancelled']);
            foreach (User::where('role', User::ROLE_ADMIN)->get() as $admin) {
                $admin->notify(new ReservationCancelledNotification($reservation));
            }
            $reservation->loadMissing(['apartment.owner.user']);
            $ownerUser = $reservation->apartment?->owner?->user;
            if ($ownerUser && $ownerUser->id !== $request->user()->id) {
                $ownerUser->notify(new OwnerReservationCancelledNotification($reservation));
            }
            return back()->with('ok', 'Reserva cancelada.');
        }
        return back();
    }

    /**
     * Crear mensaje automático cliente → anfitrión cuando se confirma una reserva pagada.
     */
    protected function notifyOwnerReservationPaid(Reservation $reservation): void
    {
        $reservation->loadMissing(['apartment.owner.user', 'user']);

        $apartment = $reservation->apartment;
        $owner = $apartment?->owner;
        $ownerUser = $owner?->user;
        $clientUser = $reservation->user;

        if (! $apartment || ! $ownerUser || ! $clientUser) {
            return;
        }
        if ($ownerUser->id === $clientUser->id) {
            return;
        }

        $ownerUser->notify(new OwnerReservationPaidNotification($reservation));

        $checkIn = $reservation->check_in?->format('d/m/Y');
        $checkOut = $reservation->check_out?->format('d/m/Y');

        $body = "Hola, acabo de pagar la reserva para \"{$apartment->title}\"";
        if ($checkIn && $checkOut) {
            $body .= " del {$checkIn} al {$checkOut}";
        }
        $body .= ". ¿Cuándo podríamos acordar el día y hora de entrada al alojamiento?";

        Message::create([
            'sender_id' => $clientUser->id,
            'receiver_id' => $ownerUser->id,
            'apartment_id' => $apartment->id,
            'body' => $body,
        ]);
    }
}
