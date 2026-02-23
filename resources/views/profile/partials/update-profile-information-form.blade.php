<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Información del perfil</h2>
        <p class="mt-1 text-sm text-gray-600">Actualiza tu nombre, correo y ubicación.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="profile-form mt-4">
        @csrf
        @method('patch')

        <div class="profile-form-grid">
            <div class="form-group">
                <x-input-label for="name" :value="__('Nombre')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-1 text-sm" :messages="$errors->get('name')" />
            </div>
            <div class="form-group">
                <x-input-label for="email" :value="__('Correo electrónico')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-1 text-sm" :messages="$errors->get('email')" />
            </div>

            {{-- Ubicación geográfica --}}
            <div class="profile-form-section form-group-full">
                <h3 class="text-sm font-semibold text-gray-900 mb-2">Ubicación geográfica</h3>
                <p class="text-xs text-gray-600 mb-3">Código postal y datos de estado, ciudad, municipio y colonia.</p>
                <div class="form-group">
                <label class="profile-form form-label" for="profile_codigo_postal">Código postal</label>
                <input type="text" id="profile_codigo_postal" name="postal_code" inputmode="numeric" pattern="[0-9]*" maxlength="10" placeholder="97000" autocomplete="postal-code" class="profile-form form-input" value="{{ old('postal_code', $user->postal_code ?? '') }}">
                <p class="text-xs text-gray-500 mt-1">Escribe 5 dígitos y se completarán automáticamente estado, ciudad, municipio y colonias.</p>
                @error('postal_code')<p class="profile-form form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="profile-form form-label" for="profile_field_state">Estado (entidad federativa)</label>
                <input type="text" id="profile_field_state" name="state" class="profile-form form-input" value="{{ old('state', $user->state ?? '') }}" maxlength="100" placeholder="Ej. Yucatán">
                @error('state')<p class="profile-form form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="profile-form form-label" for="profile_field_city">Ciudad</label>
                <input type="text" id="profile_field_city" name="city" class="profile-form form-input" value="{{ old('city', $user->city ?? '') }}" maxlength="100" placeholder="Ej. Mérida">
                @error('city')<p class="profile-form form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="profile-form form-label" for="profile_field_municipality">Municipio</label>
                <input type="text" id="profile_field_municipality" name="municipality" class="profile-form form-input" value="{{ old('municipality', $user->municipality ?? '') }}" maxlength="100" placeholder="Ej. Mérida">
                @error('municipality')<p class="profile-form form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="profile-form form-label" for="profile_field_locality">Localidad / Colonia</label>
                <input type="text" id="profile_field_locality" name="locality" list="profile-datalist-colonias" class="profile-form form-input" value="{{ old('locality', $user->locality ?? '') }}" maxlength="150" placeholder="Colonia o zona">
                <datalist id="profile-datalist-colonias"></datalist>
                @error('locality')<p class="profile-form form-error">{{ $message }}</p>@enderror
            </div>
            </div>

            @if($user->client_id && $user->client)
            <div class="form-group">
                <x-input-label for="phone" :value="__('Teléfono')" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->client->phone ?? '')" placeholder="Ej. 5512345678" autocomplete="tel" />
                <x-input-error class="mt-1 text-sm" :messages="$errors->get('phone')" />
            </div>
            <div class="form-group">
                <x-input-label for="gender" :value="__('Género')" />
                <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="hombre" {{ old('gender', $user->client->gender ?? '') === 'hombre' ? 'selected' : '' }}>{{ __('Hombre') }}</option>
                    <option value="mujer" {{ old('gender', $user->client->gender ?? '') === 'mujer' ? 'selected' : '' }}>{{ __('Mujer') }}</option>
                    <option value="otro" {{ old('gender', $user->client->gender ?? '') === 'otro' ? 'selected' : '' }}>{{ __('Otro') }}</option>
                </select>
                <x-input-error class="mt-1 text-sm" :messages="$errors->get('gender')" />
            </div>
            @endif

            {{-- Privacidad --}}
            <div class="profile-form-section form-group-full">
                <h3 class="text-sm font-semibold text-gray-900 mb-2">Privacidad</h3>
                <p class="text-xs text-gray-600 mb-2">Qué información pueden ver otros usuarios.</p>
            <div class="form-group">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="hidden" name="privacy_show_name_public" value="0">
                    <input type="checkbox" name="privacy_show_name_public" value="1" class="rounded border-gray-300 text-6F4E37 focus:ring-6F4E37" {{ old('privacy_show_name_public', $user->privacy_show_name_public ?? true) ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-gray-700">Mostrar mi nombre a otros usuarios</span>
                </label>
            </div>
            <div class="form-group">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="hidden" name="privacy_show_location_public" value="0">
                    <input type="checkbox" name="privacy_show_location_public" value="1" class="rounded border-gray-300 text-6F4E37 focus:ring-6F4E37" {{ old('privacy_show_location_public', $user->privacy_show_location_public ?? false) ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-gray-700">Mostrar ciudad/estado (ubicación) a otros</span>
                </label>
            </div>
            <div class="form-group">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="hidden" name="privacy_show_last_login" value="0">
                    <input type="checkbox" name="privacy_show_last_login" value="1" class="rounded border-gray-300 text-6F4E37 focus:ring-6F4E37" {{ old('privacy_show_last_login', $user->privacy_show_last_login ?? false) ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-gray-700">Mostrar última conexión</span>
                </label>
            </div>
            </div>

            {{-- Idioma y zona horaria --}}
            <div class="profile-form-section form-group-full">
                <h3 class="text-sm font-semibold text-gray-900 mb-2">Idioma y zona horaria</h3>
                <div class="profile-form-grid" style="grid-template-columns: repeat(2, 1fr);">
                    <div class="form-group">
                        <label class="profile-form form-label" for="locale">Idioma</label>
                        <select id="locale" name="locale" class="profile-form form-input">
                            <option value="es" {{ old('locale', $user->locale ?? 'es') === 'es' ? 'selected' : '' }}>Español</option>
                            <option value="en" {{ old('locale', $user->locale ?? 'es') === 'en' ? 'selected' : '' }}>English</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="profile-form form-label" for="timezone">Zona horaria</label>
                        <select id="timezone" name="timezone" class="profile-form form-input">
                            <option value="">Predeterminada del servidor</option>
                            <option value="America/Mexico_City" {{ old('timezone', $user->timezone ?? '') === 'America/Mexico_City' ? 'selected' : '' }}>America/Mexico_City (Centro)</option>
                            <option value="America/Tijuana" {{ old('timezone', $user->timezone ?? '') === 'America/Tijuana' ? 'selected' : '' }}>America/Tijuana (Pacífico)</option>
                            <option value="America/Cancun" {{ old('timezone', $user->timezone ?? '') === 'America/Cancun' ? 'selected' : '' }}>America/Cancun (Sureste)</option>
                            <option value="America/Chihuahua" {{ old('timezone', $user->timezone ?? '') === 'America/Chihuahua' ? 'selected' : '' }}>America/Chihuahua (Norte)</option>
                            <option value="America/Monterrey" {{ old('timezone', $user->timezone ?? '') === 'America/Monterrey' ? 'selected' : '' }}>America/Monterrey</option>
                            <option value="America/New_York" {{ old('timezone', $user->timezone ?? '') === 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                            <option value="America/Los_Angeles" {{ old('timezone', $user->timezone ?? '') === 'America/Los_Angeles' ? 'selected' : '' }}>America/Los_Angeles</option>
                            <option value="Europe/Madrid" {{ old('timezone', $user->timezone ?? '') === 'Europe/Madrid' ? 'selected' : '' }}>Europe/Madrid</option>
                        </select>
                    </div>
                </div>
            </div>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail() && $user->isOwner())
            <div class="form-group-full" id="verificar-correo">
                <p class="text-sm text-gray-800">
                    Tu correo no está verificado.
                    <button form="send-verification" type="button" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Reenviar correo de verificación
                    </button>
                </p>
                @if (session('status') === 'verification-link-sent')
                    <p class="mt-1 text-sm text-green-600">Se ha enviado un nuevo enlace de verificación a tu correo.</p>
                @endif
            </div>
            @endif

            <div class="form-group-full flex items-center gap-4 pt-2">
                <x-primary-button>Guardar</x-primary-button>
                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">Guardado.</p>
                @endif
            </div>
        </div>
    </form>
</section>
