<x-app-layout>
    @push('styles')
    <style>
        .rh-history-page { background: #FAF6F0; min-height: 100vh; }
        .rh-history-wrap { max-width: 64rem; margin: 0 auto; padding: 1.5rem 1rem 2.5rem; }
        @media (min-width: 1024px) { .rh-history-wrap { padding: 2rem 2rem 3rem; } }

        .rh-history-header { margin-bottom: 1.5rem; }
        .rh-history-header-inner { display: flex; flex-wrap: wrap; align-items: flex-start; justify-content: space-between; gap: 1rem; }
        .rh-history-header-text { display: flex; align-items: flex-start; gap: 1rem; }
        .rh-history-icon { width: 3rem; height: 3rem; border-radius: 0.75rem; background: rgba(111,78,55,.1); color: #6F4E37; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .rh-history-icon svg { width: 1.5rem; height: 1.5rem; }
        .rh-history-title { font-size: 1.5rem; font-weight: 700; color: #2C1810; margin: 0; letter-spacing: -0.02em; }
        .rh-history-sub { font-size: 0.9375rem; color: #6B5344; margin: 0.25rem 0 0; line-height: 1.5; max-width: 36rem; }
        .rh-history-btn-pdf { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; background: #6F4E37; color: #fff; text-decoration: none; transition: background .2s, transform .05s; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .rh-history-btn-pdf:hover { background: #4A3728; }
        .rh-history-btn-pdf svg { width: 1.125rem; height: 1.125rem; flex-shrink: 0; }

        .rh-history-card { background: #fff; border-radius: 1rem; border: 1px solid #E8E2DA; box-shadow: 0 1px 3px rgba(44,24,16,.04); overflow: hidden; }
        .rh-history-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #E8E2DA; background: #FFFDFB; }
        .rh-history-card-title { font-size: 1rem; font-weight: 600; color: #2C1810; margin: 0; }
        .rh-history-card-sub { font-size: 0.8125rem; color: #6B5344; margin-top: 0.25rem; }
        .rh-history-body { padding: 0; }
        .rh-history-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        .rh-history-table th { text-align: left; padding: 0.875rem 1.25rem; color: #6B5344; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #E8E2DA; background: #FAF6F0; }
        .rh-history-table td { padding: 1rem 1.25rem; border-bottom: 1px solid #F0EBE3; color: #2C1810; vertical-align: middle; }
        .rh-history-table tbody tr { transition: background .15s; }
        .rh-history-table tbody tr:hover { background: #FFFDFB; }
        .rh-history-table tbody tr:last-child td { border-bottom: none; }
        .rh-history-apt { font-weight: 600; color: #2C1810; }
        .rh-history-apt-addr { font-size: 0.8125rem; color: #6B5344; margin-top: 0.125rem; }
        .rh-history-status { display: inline-flex; align-items: center; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .rh-history-status.paid { background: rgba(82,121,111,.12); color: #427A6B; }
        .rh-history-status.pending { background: rgba(166,124,82,.12); color: #8B6914; }
        .rh-history-status.cancelled { background: rgba(220,38,38,.1); color: #B91C1C; }
        .rh-history-total { font-weight: 600; color: #6F4E37; }
        .rh-history-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem 0.75rem; }
        .rh-history-actions a { font-size: 0.8125rem; font-weight: 500; color: #6F4E37; text-decoration: none; transition: color .15s; }
        .rh-history-actions a:hover { color: #4A3728; text-decoration: underline; }
        .rh-history-actions .sep { color: #D4C4B0; font-weight: 300; user-select: none; }
        .rh-history-empty { padding: 3rem 1.5rem; text-align: center; }
        .rh-history-empty-icon { width: 4rem; height: 4rem; margin: 0 auto 1rem; border-radius: 1rem; background: rgba(111,78,55,.08); color: #6B5344; display: flex; align-items: center; justify-content: center; }
        .rh-history-empty-icon svg { width: 2rem; height: 2rem; }
        .rh-history-empty-title { font-size: 1rem; font-weight: 600; color: #2C1810; margin: 0 0 0.375rem; }
        .rh-history-empty-text { font-size: 0.9375rem; color: #6B5344; margin: 0; line-height: 1.5; max-width: 28rem; margin-left: auto; margin-right: auto; }
        .rh-history-pagination { padding: 1rem 1.5rem; border-top: 1px solid #E8E2DA; background: #FFFDFB; }
        @media (max-width: 767px) {
            .rh-history-table th:nth-child(n+3), .rh-history-table td:nth-child(n+3) { display: none; }
            .rh-history-table th:nth-child(1) { width: auto; }
            .rh-history-table th:nth-child(2) { display: none; }
            .rh-history-table td:nth-child(2) { display: none; }
            .rh-history-mobile-dates { font-size: 0.8125rem; color: #6B5344; margin-top: 0.25rem; }
        }
        @media (min-width: 768px) { .rh-history-mobile-dates { display: none; } }
    </style>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Historial de rentas') }}
        </h2>
    </x-slot>

    <div class="rh-history-page">
        <div class="rh-history-wrap">
            <header class="rh-history-header">
                <div class="rh-history-header-inner">
                    <div class="rh-history-header-text">
                        <span class="rh-history-icon" aria-hidden="true">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2V5a2 2 0 00-2-2h-2zm0 0V3m8 4V3m-9 8h10"/></svg>
                        </span>
                        <div>
                            <h1 class="rh-history-title">Historial de rentas</h1>
                            <p class="rh-history-sub">
                                Todas las reservas que has realizado como huésped en RoomHub.
                            </p>
                        </div>
                    </div>
                    @if(!$reservations->isEmpty())
                        <a href="{{ route('reservations.history.pdf') }}" class="rh-history-btn-pdf">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Descargar PDF
                        </a>
                    @endif
                </div>
            </header>

            <div class="rh-history-card">
                <div class="rh-history-card-header">
                    <h3 class="rh-history-card-title">Tus reservas</h3>
                    <p class="rh-history-card-sub">Detalle de alojamiento, fechas, tipo de renta y estado.</p>
                </div>
                <div class="rh-history-body">
                    @if($reservations->isEmpty())
                        <div class="rh-history-empty">
                            <div class="rh-history-empty-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2V5a2 2 0 00-2-2h-2zm0 0V3m8 4V3"/></svg>
                            </div>
                            <p class="rh-history-empty-title">Aún no tienes reservas</p>
                            <p class="rh-history-empty-text">Cuando completes una reserva, aparecerá aquí con el detalle de tu estancia.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="rh-history-table">
                                <thead>
                                    <tr>
                                        <th>Alojamiento</th>
                                        <th>Entrada</th>
                                        <th>Salida</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservations as $r)
                                        <tr>
                                            <td>
                                                <div class="rh-history-apt">
                                                    {{ $r->apartment->title ?? 'Alojamiento eliminado' }}
                                                </div>
                                                @if($r->apartment)
                                                    <div class="rh-history-apt-addr">{{ $r->apartment->address }}</div>
                                                @endif
                                                <div class="rh-history-mobile-dates">
                                                    {{ $r->check_in?->format('d/m/Y') ?? '—' }} – {{ $r->check_out?->format('d/m/Y') ?? '—' }}
                                                    · {{ $r->rent_type === 'month' ? 'Por mes' : 'Por día' }}
                                                </div>
                                            </td>
                                            <td>{{ $r->check_in?->format('d/m/Y') ?? '—' }}</td>
                                            <td>{{ $r->check_out?->format('d/m/Y') ?? '—' }}</td>
                                            <td>{{ $r->rent_type === 'month' ? 'Por mes' : 'Por día' }}</td>
                                            <td>
                                                @php $status = $r->status; @endphp
                                                <span class="rh-history-status {{ $status }}">
                                                    @switch($status)
                                                        @case('paid') Pagada @break
                                                        @case('pending') Pendiente @break
                                                        @case('cancelled') Cancelada @break
                                                        @default {{ ucfirst($status) }}
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td><span class="rh-history-total">${{ number_format($r->total_amount, 0) }} MXN</span></td>
                                            <td class="rh-history-actions">
                                                @if($r->apartment)
                                                    <a href="{{ route('reservations.success', $r) }}">Ver detalle</a>
                                                    <span class="sep">·</span>
                                                    <a href="{{ route('cuartos.show', $r->apartment) }}" target="_blank" rel="noopener">Ver alojamiento</a>
                                                @else
                                                    <a href="{{ route('reservations.success', $r) }}">Ver detalle</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($reservations->hasPages())
                            <div class="rh-history-pagination">
                                {{ $reservations->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
