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
        .bh-page .layout { display: grid; gap: 1.5rem; }
        @media (min-width: 1024px) { .bh-page .layout { grid-template-columns: minmax(0, 1.6fr) minmax(0, 1.1fr); align-items: flex-start; } }
        .bh-card { background: #fff; border: 1px solid #E8E2DA; border-radius: 1rem; box-shadow: 0 1px 3px rgba(44,24,16,.04); }
        .bh-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #E8E2DA; background: #FFFDFB; }
        .bh-card-header h3 { font-size: 1rem; font-weight: 700; color: #2C1810; margin: 0 0 .25rem; }
        .bh-card-header p { font-size: 0.875rem; color: #6B5344; margin: 0; }
        .bh-card-body { padding: 1.5rem; }
        @media (min-width: 640px) { .bh-card-body { padding: 1.75rem; } }
        .bh-label { display: block; font-size: 0.875rem; font-weight: 600; color: #2C1810; margin-bottom: 0.25rem; }
        .bh-help { font-size: 0.75rem; color: #6B5344; }
        .bh-input-file { margin-top: 0.5rem; display: block; width: 100%; font-size: 0.875rem; color: #6B5344; }
        .bh-input-file::file-selector-button { margin-right: .75rem; padding: .45rem 1rem; border-radius: .5rem; border: 0; background: #6F4E37; color: #fff; font-size: 0.8125rem; font-weight: 600; cursor: pointer; }
        .bh-input-file::file-selector-button:hover { background: #4A3728; }
        .bh-error { font-size: 0.8125rem; color: #E53935; margin-top: 0.25rem; }
        .bh-question { background: #FAF6F0; border-radius: 0.75rem; padding: 1rem 1.1rem; border: 1px solid #E8E2DA; }
        .bh-question-title { font-size: 0.875rem; font-weight: 600; color: #2C1810; margin-bottom: 0.4rem; }
        .bh-question-options { display: flex; flex-wrap: wrap; gap: 1rem; margin-top: 0.15rem; }
        .bh-radio-label { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.875rem; color: #6B5344; cursor: pointer; }
        .bh-radio-label input { accent-color: #6F4E37; }
        .bh-actions { display: flex; flex-wrap: wrap; gap: 0.75rem; margin-top: 1.25rem; }
        .bh-btn-primary { display: inline-flex; align-items: center; justify-content: center; padding: 0.7rem 1.4rem; border-radius: 0.5rem; font-size: 0.9375rem; font-weight: 600; background: #6F4E37; color: #fff; border: none; cursor: pointer; text-decoration: none; }
        .bh-btn-primary:hover { background: #4A3728; }
        .bh-btn-secondary { display: inline-flex; align-items: center; justify-content: center; padding: 0.7rem 1.4rem; border-radius: 0.5rem; font-size: 0.9375rem; font-weight: 600; background: #fff; color: #6B5344; border: 1px solid #E8E2DA; text-decoration: none; }
        .bh-btn-secondary:hover { background: #FAF6F0; color: #2C1810; border-color: #D4C4B0; }
        .bh-note { font-size: 0.8125rem; color: #6B5344; margin-top: .75rem; }
        .bh-alert-rejected { margin-top: .75rem; padding: .65rem .9rem; border-radius: .5rem; font-size: 0.8125rem; background: rgba(166,124,82,.12); color: #6B5344; border: 1px solid #D4C4B0; }
        .bh-side { font-size: 0.875rem; color: #6B5344; }
        .bh-side h4 { font-size: 0.9375rem; font-weight: 600; color: #2C1810; margin-bottom: 0.5rem; }
        .bh-side ul { list-style: none; padding: 0; margin: 0; display: grid; gap: 0.35rem; }
        .bh-side li { display: flex; align-items: center; gap: 0.4rem; }
        .bh-side li svg { width: 16px; height: 16px; color: #6F4E37; flex-shrink: 0; }
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
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </span>
                <div>
                    <p class="bh-kicker">Paso 1 de 2 · Verificación legal</p>
                    <h1>Verifica tu identidad para publicar alojamientos</h1>
                    <p>Antes de aceptar reservas como anfitrión necesitamos confirmar quién eres. Sube una foto clara de tu INE y responde unas preguntas rápidas. Podrás seguir usando RoomHub como cliente en todo momento.</p>
                </div>
            </header>

            <div class="layout">
                {{-- Columna principal: formulario --}}
                <div class="bh-card">
                    <div class="bh-card-header">
                        <h3>Verificación de identidad</h3>
                        <p>Esta información solo se usa para cumplir con requisitos legales y de seguridad.</p>
                        @if(isset($rejected) && $rejected)
                            <div class="bh-alert-rejected">
                                {{ $message ?? 'Tu verificación anterior fue rechazada. Revisa la foto y respuestas y envíalas de nuevo.' }}
                            </div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('become-host.verification.store') }}" enctype="multipart/form-data" class="bh-card-body space-y-6">
                        @csrf

                        <div>
                            <label for="ine_photo" class="bh-label">Foto de tu INE (frente) <span class="text-red-500">*</span></label>
                            <p class="bh-help">Formatos: JPG, PNG o WebP. Máximo 5 MB. Asegúrate de que los datos se vean nítidos.</p>
                            <input id="ine_photo" type="file" name="ine_photo" accept="image/jpeg,image/jpg,image/png,image/webp" required class="bh-input-file">
                            @error('ine_photo')
                                <p class="bh-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div style="border-top: 1px solid #E8E2DA; padding-top: 1.25rem;">
                            <h4 class="bh-label" style="margin-bottom: .75rem;">Preguntas de verificación</h4>
                            <div class="space-y-4">
                                @foreach($questions as $key => $label)
                                    <div class="bh-question">
                                        <p class="bh-question-title">{{ $label }}</p>
                                        <div class="bh-question-options">
                                            <label class="bh-radio-label">
                                                <input type="radio" name="{{ $key }}" value="si" {{ old($key) === 'si' ? 'checked' : '' }} required>
                                                <span>Sí</span>
                                            </label>
                                            <label class="bh-radio-label">
                                                <input type="radio" name="{{ $key }}" value="no" {{ old($key) === 'no' ? 'checked' : '' }}>
                                                <span>No</span>
                                            </label>
                                        </div>
                                        @error($key)
                                            <p class="bh-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <p class="bh-note">
                            Al enviar esta información aceptas que la usemos únicamente para verificar tu identidad y proteger a la comunidad de RoomHub.
                        </p>

                        <div class="bh-actions">
                            <button type="submit" class="bh-btn-primary">
                                Enviar verificación
                            </button>
                            <a href="{{ route('dashboard') }}" class="bh-btn-secondary">
                                Cancelar (seguir como cliente)
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Columna lateral: contexto --}}
                <aside class="bh-side">
                    <div class="bh-card" style="padding: 1.25rem 1.5rem;">
                        <h4>¿Por qué pedimos esto?</h4>
                        <ul>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Para proteger a huéspedes y anfitriones.
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Cumplir con requisitos legales y fiscales.
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Evitar cuentas falsas o suplantaciones.
                            </li>
                        </ul>
                        <p style="margin-top: 0.75rem; font-size: 0.8125rem; color: #6B5344;">
                            Una vez aprobada tu verificación, podrás completar tu perfil de anfitrión y publicar tus primeros alojamientos.
                        </p>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
