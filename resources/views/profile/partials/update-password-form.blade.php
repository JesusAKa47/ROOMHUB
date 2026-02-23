<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Contraseña</h2>
        <p class="mt-1 text-sm text-gray-600">Usa una contraseña larga y segura.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="profile-form mt-4">
        @csrf
        @method('put')

        <div class="profile-form-grid">
            <div class="form-group">
                <x-input-label for="update_password_current_password" :value="__('Contraseña actual')" />
                <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1 text-sm" />
            </div>
            <div class="form-group">
                <x-input-label for="update_password_password" :value="__('Nueva contraseña')" />
                <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1 text-sm" />
            </div>
            <div class="form-group">
                <x-input-label for="update_password_password_confirmation" :value="__('Confirmar contraseña')" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1 text-sm" />
            </div>
            <div class="form-group-full flex items-center gap-4 pt-2">
                <x-primary-button>Guardar</x-primary-button>
                @if (session('status') === 'password-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">Guardado.</p>
                @endif
            </div>
        </div>
    </form>
</section>
