<x-app-layout>
    @push('styles')
    <style>
        .bh-page { background: #FAF6F0; min-height: 100vh; padding-bottom: 3rem; }
        .bh-page .wrap { max-width: 40rem; margin: 0 auto; padding: 1.5rem 1rem 2rem; }
        .bh-header { display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem; }
        .bh-header-icon { width: 3rem; height: 3rem; border-radius: 0.75rem; background: rgba(111,78,55,.1); color: #6F4E37; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .bh-header-icon svg { width: 1.5rem; height: 1.5rem; }
        .bh-header h1 { font-size: 1.5rem; font-weight: 700; color: #2C1810; margin: 0; letter-spacing: -0.02em; }
        .bh-kicker { font-size: 0.75rem; text-transform: uppercase; letter-spacing: .08em; color: #6F4E37; font-weight: 600; margin-bottom: 0.25rem; }
        .bh-card { background: #fff; border: 1px solid #E8E2DA; border-radius: 1rem; box-shadow: 0 1px 3px rgba(44,24,16,.04); overflow: hidden; }
        .bh-card-body { padding: 1.5rem 1.75rem; }
        .bh-alert-info { padding: 1rem 1.25rem; border-radius: 0.5rem; font-size: 0.9375rem; background: rgba(82,121,111,.1); color: #2d5a4f; border: 1px solid rgba(82,121,111,.25); margin-bottom: 1rem; }
        .bh-status-badge { display: inline-flex; align-items: center; padding: 0.35rem 0.75rem; border-radius: 9999px; font-size: 0.8125rem; font-weight: 600; }
        .bh-status-pendiente { background: rgba(166,124,82,.15); color: #6B5344; }
        .bh-status-revision { background: rgba(111,78,55,.12); color: #4A3728; }
        .bh-card-body .text-muted { font-size: 0.9375rem; color: #6B5344; margin-bottom: 1rem; line-height: 1.5; }
        .bh-card-body .text-sm { font-size: 0.8125rem; color: #8B7355; margin-bottom: 1rem; }
        .bh-btn-secondary { display: inline-flex; align-items: center; padding: 0.7rem 1.4rem; border-radius: 0.5rem; font-size: 0.9375rem; font-weight: 600; background: #fff; color: #6B5344; border: 1px solid #E8E2DA; text-decoration: none; }
        .bh-btn-secondary:hover { background: #FAF6F0; color: #2C1810; border-color: #D4C4B0; }
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
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <div>
                    <p class="bh-kicker">Paso 1 de 2 · Verificación</p>
                    <h1>Tu verificación está en revisión</h1>
                </div>
            </header>

            <div class="bh-card">
                <div class="bh-card-body">
                    @if(session('ok'))
                        <div class="bh-alert-info">{{ session('ok') }}</div>
                    @endif
                    @if(session('info'))
                        <div class="bh-alert-info">{{ session('info') }}</div>
                    @endif

                    <p class="mb-3">
                        <span class="bh-status-badge {{ $verification->isUnderReview() ? 'bh-status-revision' : 'bh-status-pendiente' }}">
                            {{ $verification->isUnderReview() ? 'En revisión' : 'Pendiente de revisión' }}
                        </span>
                    </p>
                    <p class="text-muted">
                        Un administrador revisará tu documentación (INE y respuestas). Cuando tu verificación sea aprobada, podrás completar tu perfil de anfitrión y publicar alojamientos.
                    </p>
                    <p class="text-sm">
                        Enviado el {{ $verification->submitted_at?->translatedFormat('d \d\e F \d\e Y \a \l\a\s H:i') ?? '—' }}.
                    </p>
                    <a href="{{ route('dashboard') }}" class="bh-btn-secondary">Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
