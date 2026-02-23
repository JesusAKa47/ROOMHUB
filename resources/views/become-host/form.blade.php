<x-app-layout>
    @push('styles')
    <style>
        .bh-page { background: #FAF6F0; min-height: 100vh; padding-bottom: 3rem; }
        .bh-page .wrap { max-width: 56rem; margin: 0 auto; padding: 1.5rem 1rem 2rem; }
        @media (min-width: 1024px) { .bh-page .wrap { padding: 2rem 2rem 2.5rem; } }
        .bh-header { display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem; }
        .bh-header-icon { width: 3rem; height: 3rem; border-radius: 0.75rem; background: rgba(111,78,55,.1); color: #6F4E37; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .bh-header-icon svg { width: 1.5rem; height: 1.5rem; }
        .bh-header h1 { font-size: 1.5rem; font-weight: 700; color: #2C1810; margin: 0; letter-spacing: -0.02em; }
        @media (min-width: 640px) { .bh-header h1 { font-size: 1.75rem; } }
        .bh-header p { font-size: 0.9375rem; color: #6B5344; margin: 0.35rem 0 0; max-width: 42rem; line-height: 1.5; }
        .bh-kicker { font-size: 0.75rem; text-transform: uppercase; letter-spacing: .08em; color: #6F4E37; font-weight: 600; margin-bottom: 0.25rem; }
        .bh-card { background: #fff; border: 1px solid #E8E2DA; border-radius: 1rem; box-shadow: 0 1px 3px rgba(44,24,16,.04); overflow: hidden; }
        .bh-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #E8E2DA; background: #FFFDFB; }
        .bh-card-header span { display: inline-flex; align-items: center; padding: 0.25rem 0.6rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; color: #52796F; background: rgba(82,121,111,.12); }
        .bh-card-header h3 { font-size: 1rem; font-weight: 700; color: #2C1810; margin: 0.5rem 0 0.25rem; }
        .bh-card-header p { font-size: 0.875rem; color: #6B5344; margin: 0; }
        .bh-card-body { padding: 1.5rem; }
        @media (min-width: 640px) { .bh-card-body { padding: 1.75rem; } }
        .bh-label { display: block; font-size: 0.875rem; font-weight: 600; color: #2C1810; margin-bottom: 0.25rem; }
        .bh-input, .bh-select, .bh-textarea { width: 100%; padding: 0.6rem 0.75rem; border-radius: 0.5rem; border: 1px solid #E8E2DA; font-size: 0.9375rem; color: #2C1810; background: #fff; }
        .bh-input:focus, .bh-select:focus, .bh-textarea:focus { outline: none; border-color: #6F4E37; box-shadow: 0 0 0 3px rgba(111,78,55,.12); }
        .bh-help { font-size: 0.75rem; color: #6B5344; margin-top: 0.25rem; }
        .bh-error { font-size: 0.8125rem; color: #E53935; margin-top: 0.25rem; }
        .bh-actions { display: flex; flex-wrap: wrap; gap: 0.75rem; margin-top: 1.25rem; }
        .bh-btn-primary { display: inline-flex; align-items: center; justify-content: center; padding: 0.7rem 1.4rem; border-radius: 0.5rem; font-size: 0.9375rem; font-weight: 600; background: #6F4E37; color: #fff; border: none; cursor: pointer; text-decoration: none; }
        .bh-btn-primary:hover { background: #4A3728; }
        .bh-btn-secondary { display: inline-flex; align-items: center; justify-content: center; padding: 0.7rem 1.4rem; border-radius: 0.5rem; font-size: 0.9375rem; font-weight: 600; background: #fff; color: #6B5344; border: 1px solid #E8E2DA; text-decoration: none; }
        .bh-btn-secondary:hover { background: #FAF6F0; color: #2C1810; border-color: #D4C4B0; }
        .bh-alert-ok { margin-bottom: 1rem; padding: 0.7rem 0.9rem; border-radius: 0.5rem; font-size: 0.875rem; background: rgba(82,121,111,.1); color: #2d5a4f; border: 1px solid rgba(82,121,111,.3); }
    </style>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Convertirse en anfitrión') }}
        </h2>
    </x-slot>

    <div class="bh-page">
        <div class="wrap">
            <header class="bh-header">
                <span class="bh-header-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </span>
                <div>
                    <p class="bh-kicker">Paso 2 de 2 · Perfil de anfitrión</p>
                    <h1>Completa tu perfil para empezar a publicar</h1>
                    <p>Ya enviaste tu verificación de identidad. Ahora solo necesitamos algunos datos de contacto para que los huéspedes puedan confiar en ti y para que podamos ayudarte si algo ocurre con una reserva.</p>
                </div>
            </header>

            <div class="bh-card">
                <div class="bh-card-header">
                    <span>Verificación enviada</span>
                    <h3>Configura tu perfil de anfitrión</h3>
                    <p>Indica un teléfono de contacto y el tipo de cuenta con el que administrarás tus alojamientos.</p>
                </div>

                <div class="bh-card-body">
                    @if(session('ok'))
                        <div class="bh-alert-ok">{{ session('ok') }}</div>
                    @endif

                    <form method="POST" action="{{ route('become-host.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label class="bh-label">Teléfono de contacto <span class="text-red-500">*</span></label>
                            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                                <div style="min-width: 9rem; flex: 0 0 auto;">
                                    <select id="phone_country" name="phone_country" class="bh-select" required>
                                        <option value="+52" {{ old('phone_country', '+52') === '+52' ? 'selected' : '' }}>+52 México</option>
                                        <option value="+1" {{ old('phone_country') === '+1' ? 'selected' : '' }}>+1 EE.UU. / Canadá</option>
                                        <option value="+34" {{ old('phone_country') === '+34' ? 'selected' : '' }}>+34 España</option>
                                    </select>
                                </div>
                                <div style="flex: 1 1 12rem;">
                                    <input id="phone_number" type="text" name="phone_number"
                                           value="{{ old('phone_number') }}" required maxlength="20"
                                           placeholder="Ej. 5571234567" class="bh-input">
                                </div>
                            </div>
                            <p class="bh-help">
                                Selecciona la lada internacional y escribe el número local sin espacios ni signos. Validaremos que la longitud coincida con la lada elegida.
                            </p>
                            @error('phone_country')
                                <p class="bh-error">{{ $message }}</p>
                            @enderror
                            @error('phone_number')
                                <p class="bh-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="type" class="bh-label">¿Eres persona o empresa?</label>
                            <select id="type" name="type" required class="bh-select">
                                <option value="persona" {{ old('type', 'persona') === 'persona' ? 'selected' : '' }}>Persona</option>
                                <option value="empresa" {{ old('type') === 'empresa' ? 'selected' : '' }}>Empresa</option>
                            </select>
                            @error('type')
                                <p class="bh-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="notes" class="bh-label">Notas (opcional)</label>
                            <textarea id="notes" name="notes" rows="3" placeholder="Algo que quieras que sepamos..." class="bh-textarea">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="bh-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bh-actions">
                            <button type="submit" class="bh-btn-primary">
                                Convertirme en anfitrión
                            </button>
                            <a href="{{ route('dashboard') }}" class="bh-btn-secondary">
                                Ahora no
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
