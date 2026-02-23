<section class="profile-section-compact">
    <header>
        <h2 class="text-lg font-medium text-gray-900">Eliminar cuenta</h2>
        <p class="mt-1 text-sm text-gray-600">
            Si eliminas tu cuenta, se borrarán para siempre todos tus datos. Descarga antes la información que quieras conservar (por ejemplo desde Historial de rentas).
        </p>
        <p class="mt-1.5 text-xs font-medium text-gray-700">Se eliminará:</p>
        <ul class="profile-list-compact mt-1 text-sm text-gray-600">
            <li>Tu perfil y datos personales</li>
            <li>Historial de reservas y mensajes</li>
            <li>Favoritos y preferencias</li>
        </ul>
    </header>
    <div class="mt-3">
        <x-danger-button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        >Eliminar cuenta</x-danger-button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                ¿Seguro que quieres eliminar tu cuenta?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Esta acción no se puede deshacer. Escribe tu contraseña para confirmar.
            </p>

            <div class="mt-4">
                <x-input-label for="password" value="Contraseña" class="sr-only" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="Contraseña"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-4 flex justify-end gap-2">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancelar
                </x-secondary-button>
                <x-danger-button class="ms-3">Eliminar cuenta</x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
