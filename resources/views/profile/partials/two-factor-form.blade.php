<section class="profile-section-compact">
    <header>
        <h2 class="text-lg font-medium text-gray-900">Verificación en dos pasos (2FA)</h2>
        <p class="mt-1 text-sm text-gray-600">
            Añade una capa extra de seguridad. Al iniciar sesión te pediremos un código de 6 dígitos desde una app como Google Authenticator o Authy.
        </p>
    </header>

    @if($user->hasTwoFactorEnabled())
        <div class="mt-3 flex flex-wrap items-center gap-3">
            <span class="profile-mode-badge">✓ Activado</span>
            <form method="POST" action="{{ route('profile.two-factor.disable') }}" class="inline" onsubmit="return confirm('¿Desactivar la verificación en dos pasos? Tu cuenta será menos segura.');">
                @csrf
                <input type="password" name="password" placeholder="Tu contraseña" required class="profile-form form-input inline-block mr-2" style="display:inline-block;width:10rem;">
                @error('password')
                    <span class="text-sm text-red-600 ml-2">{{ $message }}</span>
                @enderror
                <button type="submit" class="profile-btn-secondary">Desactivar 2FA</button>
            </form>
        </div>
    @else
        <div class="mt-3">
            <a href="{{ route('profile.two-factor.setup') }}" class="profile-mode-btn">Activar verificación en dos pasos</a>
        </div>
    @endif

    @if(session('status') === '2fa-enabled')
        <p class="mt-2 text-sm text-green-600">Verificación en dos pasos activada correctamente.</p>
    @endif
    @if(session('status') === '2fa-disabled')
        <p class="mt-2 text-sm text-gray-600">Verificación en dos pasos desactivada.</p>
    @endif
    @if(session('status') === '2fa-already-enabled')
        <p class="mt-2 text-sm text-gray-600">Ya tienes la verificación en dos pasos activada.</p>
    @endif
</section>
