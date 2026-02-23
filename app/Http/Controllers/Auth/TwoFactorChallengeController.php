<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorChallengeController extends Controller
{
    /**
     * Mostrar formulario para introducir el código 2FA.
     */
    public function create(Request $request): View|RedirectResponse
    {
        if (! session()->has('login.id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    /**
     * Verificar código 2FA y completar el login.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6', 'regex:/^[0-9]+$/'],
        ]);

        $userId = session('login.id');
        $user = User::find($userId);
        if (! $user || ! $user->two_factor_secret) {
            session()->forget('login.id');
            return redirect()->route('login')->withErrors(['code' => 'Sesión expirada. Inicia sesión de nuevo.']);
        }

        $google2fa = new Google2FA();
        if (! $google2fa->verifyKey($user->two_factor_secret, $request->input('code'), 2)) {
            throw ValidationException::withMessages([
                'code' => 'El código no es válido. Revisa tu app de autenticación.',
            ]);
        }

        session()->forget('login.id');
        Auth::guard('web')->login($user, session('login.remember', false));
        $request->session()->regenerate();

        $user->update(['last_login_at' => now()]);
        $user->logActivity('login', ['2fa' => true], $request->ip());

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.index', absolute: false));
        }
        if ($user->isOwner()) {
            return redirect()->intended(route('owner.dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
