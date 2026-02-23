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
        .bh-card-body .intro { font-size: 0.9375rem; color: #6B5344; margin-bottom: 1rem; line-height: 1.5; }
        .bh-alert-rejected { padding: 1rem 1.25rem; border-radius: 0.5rem; font-size: 0.9375rem; background: rgba(229,57,53,.08); color: #b71c1c; border: 1px solid rgba(229,57,53,.25); margin-bottom: 1rem; }
        .bh-alert-rejected strong { display: block; font-size: 0.8125rem; margin-bottom: 0.35rem; color: #8B0000; }
        .bh-card-body .text-sm { font-size: 0.8125rem; color: #8B7355; margin-bottom: 0; }
        .bh-btn-primary { display: inline-flex; align-items: center; justify-content: center; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-size: 0.9375rem; font-weight: 600; background: #6F4E37; color: #fff; border: none; text-decoration: none; cursor: pointer; }
        .bh-btn-primary:hover { background: #4A3728; color: #fff; }
        .bh-btn-secondary { display: inline-flex; align-items: center; padding: 0.7rem 1.4rem; border-radius: 0.5rem; font-size: 0.9375rem; font-weight: 600; background: #fff; color: #6B5344; border: 1px solid #E8E2DA; text-decoration: none; }
        .bh-btn-secondary:hover { background: #FAF6F0; color: #2C1810; border-color: #D4C4B0; }
        .bh-actions { display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center; margin-top: 1.5rem; }
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
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </span>
                <div>
                    <p class="bh-kicker">Paso 1 de 2 · Verificación</p>
                    <h1>Tu verificación fue rechazada</h1>
                </div>
            </header>

            <div class="bh-card">
                <div class="bh-card-body">
                    <p class="intro">
                        Un administrador revisó tu documentación y no pudo aprobarla. Revisa el motivo abajo y, cuando estés listo, vuelve a enviar tu INE y respuestas.
                    </p>

                    @if($verification->rejection_reason)
                        <div class="bh-alert-rejected">
                            <strong>Motivo del rechazo:</strong>
                            {{ $verification->rejection_reason }}
                        </div>
                    @else
                        <div class="bh-alert-rejected">
                            <strong>Motivo del rechazo:</strong>
                            No se indicó un motivo. Asegúrate de que la foto del INE se vea clara y que los datos sean legibles.
                        </div>
                    @endif

                    <p class="text-sm">
                        Revisado el {{ $verification->reviewed_at?->translatedFormat('d \d\e F \d\e Y') ?? '—' }}.
                    </p>

                    <div class="bh-actions">
                        <a href="{{ route('become-host.show', ['retry' => 1]) }}" class="bh-btn-primary">
                            Intentar de nuevo
                        </a>
                        <a href="{{ route('dashboard') }}" class="bh-btn-secondary">
                            Volver al inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
