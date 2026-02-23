<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Listado de usuarios (admin). Las contraseñas no se muestran ni se almacenan en texto plano.
     */
    public function index(): View
    {
        $users = User::with(['owner', 'client'])
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Historial de actividad de un usuario.
     */
    public function activity(User $user): View
    {
        $activities = $user->activities()->limit(100)->get();

        return view('admin.users.activity', compact('user', 'activities'));
    }

    /**
     * Suspender (bloquear) la cuenta del usuario.
     */
    public function block(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return back()->with('info', 'No se puede suspender la cuenta de un administrador.');
        }

        $user->update(['status' => User::STATUS_SUSPENDED]);
        $user->logActivity('cuenta_bloqueada', [
            'by_admin_id' => request()->user()?->id,
        ], request()->ip());

        return back()->with('ok', "Cuenta de {$user->email} bloqueada correctamente.");
    }

    /**
     * Reactivar la cuenta del usuario.
     */
    public function unblock(User $user): RedirectResponse
    {
        $user->update(['status' => User::STATUS_ACTIVE]);
        $user->logActivity('cuenta_desbloqueada', [
            'by_admin_id' => request()->user()?->id,
        ], request()->ip());

        return back()->with('ok', "Cuenta de {$user->email} desbloqueada correctamente.");
    }

    /**
     * Envía por email el enlace para restablecer contraseña del usuario.
     */
    public function sendPasswordResetLink(User $user): RedirectResponse
    {
        Password::sendResetLink(['email' => $user->email]);

        $user->logActivity('password_reset_requested', null, request()->ip());

        return back()->with('ok', "Se envió el enlace para restablecer contraseña a {$user->email}");
    }
}
