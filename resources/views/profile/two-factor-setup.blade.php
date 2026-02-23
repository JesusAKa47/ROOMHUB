<x-app-layout>
    @push('styles')
    <style>
        .twofa-page { max-width: 28rem; margin: 0 auto; padding: 2rem 1rem; }
        .twofa-page h1 { font-size: 1.5rem; font-weight: 700; color: #6F4E37; margin: 0 0 0.5rem; }
        .twofa-page .sub { font-size: 0.9375rem; color: #5c5e7a; margin: 0 0 1.5rem; line-height: 1.5; }
        .twofa-qr-wrap { background: #fff; border-radius: 1rem; padding: 1.5rem; text-align: center; border: 1px solid #E4E4ED; margin-bottom: 1.5rem; }
        .twofa-qr-wrap img { display: block; margin: 0 auto; border-radius: 0.5rem; }
        .twofa-secret { font-size: 0.8125rem; color: #5c5e7a; margin-top: 0.75rem; word-break: break-all; font-family: ui-monospace, monospace; }
        .twofa-form .form-label { display: block; font-size: 0.875rem; font-weight: 500; color: #6F4E37; margin-bottom: 0.375rem; }
        .twofa-form input { width: 100%; padding: 0.625rem 0.75rem; border: 1px solid #E4E4ED; border-radius: 0.5rem; font-size: 1.125rem; letter-spacing: 0.25em; text-align: center; }
        .twofa-form input:focus { outline: none; border-color: #6F4E37; box-shadow: 0 0 0 3px rgba(111,78,55,.15); }
        .twofa-form .form-error { font-size: 0.8125rem; color: #dc2626; margin-top: 0.25rem; }
        .twofa-form button { margin-top: 1rem; width: 100%; padding: 0.625rem 1rem; border-radius: 0.5rem; font-weight: 600; background: #6F4E37; color: #fff; border: none; cursor: pointer; }
        .twofa-form button:hover { background: #4A3728; }
        .twofa-back { display: inline-block; margin-top: 1.5rem; font-size: 0.875rem; color: #6F4E37; text-decoration: none; }
        .twofa-back:hover { text-decoration: underline; }
    </style>
    @endpush

    <div class="twofa-page">
        <h1>Activar verificación en dos pasos</h1>
        <p class="sub">Escanea el código QR con tu app de autenticación (Google Authenticator, Authy, Microsoft Authenticator, etc.). Luego introduce el código de 6 dígitos para confirmar.</p>

        <div class="twofa-qr-wrap">
            <img src="{{ route('profile.two-factor.qr') }}" alt="Código QR para 2FA" width="220" height="220">
            <p class="twofa-secret">Si no puedes escanear: introduce esta clave manualmente en la app — {{ $secret }}</p>
        </div>

        <form method="POST" action="{{ route('profile.two-factor.confirm') }}" class="twofa-form">
            @csrf
            <label for="code" class="form-label">Código de 6 dígitos</label>
            <input id="code" type="text" name="code" inputmode="numeric" pattern="[0-9]*" maxlength="6" placeholder="000000" required autofocus>
            @error('code')
                <p class="form-error">{{ $message }}</p>
            @enderror
            <button type="submit">Confirmar y activar</button>
        </form>

        <a href="{{ route('profile.edit') }}" class="twofa-back">← Volver al perfil</a>
    </div>
</x-app-layout>
