<?php

namespace App\Http\Controllers;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA as BaseGoogle2FA;
use PragmaRX\Google2FAQRCode\Google2FA;

class TwoFactorController extends Controller
{
    /**
     * Mostrar formulario para activar 2FA (QR + código de verificación).
     */
    public function showSetup(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('profile.edit')->with('status', '2fa-already-enabled');
        }

        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey(32);
        $user->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => null,
        ])->save();

        return view('profile.two-factor-setup', [
            'secret' => $secret,
        ]);
    }

    /**
     * Devolver el QR de 2FA como imagen SVG (evita problemas con data URL en algunos entornos).
     */
    public function qrImage(Request $request): Response
    {
        $user = $request->user();
        if (! $user->two_factor_secret || $user->two_factor_confirmed_at) {
            abort(404);
        }

        $base = new BaseGoogle2FA();
        $otpauthUrl = $base->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->two_factor_secret
        );

        $renderer = new ImageRenderer(
            (new RendererStyle(220))->withSize(220),
            new SvgImageBackEnd
        );
        $writer = new Writer($renderer);
        $svg = $writer->writeString($otpauthUrl, 'utf-8');

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'no-store',
        ]);
    }

    /**
     * Confirmar activación de 2FA con el código de la app.
     */
    public function confirmSetup(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6', 'regex:/^[0-9]+$/'],
        ]);

        $user = $request->user();
        $secret = $user->two_factor_secret;
        if (! $secret) {
            return redirect()->route('profile.two-factor.setup')->withErrors(['code' => 'Sesión expirada. Vuelve a intentar activar 2FA.']);
        }

        $google2fa = new Google2FA();
        if (! $google2fa->verifyKey($secret, $request->input('code'), 2)) {
            throw ValidationException::withMessages([
                'code' => 'El código no es válido. Asegúrate de usar el código actual de tu app.',
            ]);
        }

        $user->update(['two_factor_confirmed_at' => now()]);

        return redirect()->route('profile.edit')->with('status', '2fa-enabled');
    }

    /**
     * Desactivar 2FA (requiere contraseña).
     */
    public function disable(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        $request->user()->forceFill([
            'two_factor_secret' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        return redirect()->route('profile.edit')->with('status', '2fa-disabled');
    }
}
