<x-app-layout>
    @push('styles')
    <style>
        .owner-dash { background: #FAF6F0; min-height: 100vh; }
        .owner-dash .wrap { max-width: 64rem; margin: 0 auto; padding: 1.5rem 1rem 2.5rem; }
        @media (min-width: 1024px) { .owner-dash .wrap { padding: 2rem 1.5rem 3rem; } }
        .owner-dash .main-header { display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.75rem; }
        .owner-dash .main-header-icon { width: 3rem; height: 3rem; border-radius: 0.75rem; background: rgba(111,78,55,.1); color: #6F4E37; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .owner-dash .main-header-icon svg { width: 1.5rem; height: 1.5rem; }
        .owner-dash .main-header h1 { font-size: 1.5rem; font-weight: 700; color: #2C1810; margin: 0; letter-spacing: -0.02em; }
        .owner-dash .main-header p { font-size: 0.9375rem; color: #6B5344; margin: 0.25rem 0 0; line-height: 1.5; }
        .owner-dash .alert { padding: 0.75rem 1rem; border-radius: 0.5rem; margin-bottom: 1.25rem; font-size: 0.875rem; }
        .owner-dash .alert-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
        .owner-dash .card { background: #fff; border-radius: 1rem; border: 1px solid #E8E2DA; box-shadow: 0 1px 3px rgba(44,24,16,.04); overflow: hidden; }
        .owner-dash .card-cta { display: block; padding: 1.25rem 1.5rem; text-decoration: none; color: inherit; border-left: 4px solid #6F4E37; transition: box-shadow .2s, border-color .2s; }
        .owner-dash .card-cta:hover { box-shadow: 0 4px 12px rgba(44,24,16,.06); border-left-color: #4A3728; }
        .owner-dash .card-cta-inner { display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
        .owner-dash .card-cta h2 { font-size: 1.125rem; font-weight: 600; color: #2C1810; margin: 0; }
        .owner-dash .card-cta p { font-size: 0.875rem; color: #6B5344; margin: 0.25rem 0 0; }
        .owner-dash .card-cta .arrow { color: #6F4E37; flex-shrink: 0; }
        .owner-dash .section-title { font-size: 1.125rem; font-weight: 700; color: #2C1810; margin: 0 0 1rem; letter-spacing: -0.01em; }
        .owner-dash .section { margin-bottom: 2rem; }
        .owner-dash .section:last-of-type { margin-bottom: 0; }
        .owner-dash .rented-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        .owner-dash .rented-table th { text-align: left; padding: 0.875rem 1.25rem; background: #FFFDFB; color: #6B5344; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #E8E2DA; }
        .owner-dash .rented-table td { padding: 1rem 1.25rem; border-bottom: 1px solid #F0EBE3; color: #2C1810; }
        .owner-dash .rented-table tr:last-child td { border-bottom: none; }
        .owner-dash .rented-table .rented-title { font-weight: 600; color: #2C1810; }
        .owner-dash .rented-table .rented-addr { font-size: 0.8125rem; color: #6B5344; margin-top: 0.125rem; }
        .owner-dash .rented-table .badge-rentas { display: inline-block; padding: 0.25rem 0.5rem; border-radius: 9999px; background: #6F4E37; color: #fff; font-size: 0.75rem; font-weight: 600; }
        .owner-dash .rented-table a { color: #6F4E37; text-decoration: none; font-weight: 500; }
        .owner-dash .rented-table a:hover { color: #4A3728; text-decoration: underline; }
        .owner-dash .rented-empty { padding: 1.5rem 1.25rem; background: #FFFDFB; border-radius: 0.5rem; color: #6B5344; font-size: 0.875rem; line-height: 1.5; border: 1px dashed #D4C4B0; }
        .owner-dash .empty-state { padding: 3rem 1.5rem; text-align: center; }
        .owner-dash .empty-state-icon { width: 4rem; height: 4rem; margin: 0 auto 1rem; border-radius: 1rem; background: rgba(111,78,55,.08); color: #6B5344; display: flex; align-items: center; justify-content: center; }
        .owner-dash .empty-state-icon svg { width: 2rem; height: 2rem; }
        .owner-dash .empty-state h3 { font-size: 1.125rem; font-weight: 600; color: #2C1810; margin: 0 0 0.375rem; }
        .owner-dash .empty-state p { font-size: 0.9375rem; color: #6B5344; margin: 0; line-height: 1.5; }
        .owner-dash .empty-state a { display: inline-flex; align-items: center; gap: 0.375rem; margin-top: 1rem; padding: 0.5rem 1.25rem; border-radius: 0.5rem; background: #6F4E37; color: #fff; font-weight: 600; font-size: 0.875rem; text-decoration: none; transition: background .2s; }
        .owner-dash .empty-state a:hover { background: #4A3728; }
        .owner-dash .apt-grid { display: grid; gap: 1.25rem; grid-template-columns: 1fr; }
        @media (min-width: 640px) { .owner-dash .apt-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .owner-dash .apt-grid { grid-template-columns: repeat(3, 1fr); } }
        .owner-dash .apt-card { background: #fff; border-radius: 1rem; border: 1px solid #E8E2DA; box-shadow: 0 1px 3px rgba(44,24,16,.04); overflow: hidden; transition: box-shadow .2s, border-color .2s; }
        .owner-dash .apt-card:hover { box-shadow: 0 4px 12px rgba(44,24,16,.06); border-color: #D4C4B0; }
        .owner-dash .apt-card .apt-img { position: relative; aspect-ratio: 4/3; background: #F0EBE3; overflow: hidden; }
        .owner-dash .apt-card .apt-img img { width: 100%; height: 100%; object-fit: cover; }
        .owner-dash .apt-img-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #B8A99A; }
        .owner-dash .apt-img-placeholder svg { width: 2.5rem; height: 2.5rem; }
        .owner-dash .apt-card .apt-body { padding: 1rem 1.25rem; }
        .owner-dash .apt-card .apt-title { font-weight: 600; color: #2C1810; margin: 0; font-size: 0.9375rem; }
        .owner-dash .apt-card .apt-address { font-size: 0.8125rem; color: #6B5344; margin: 0.25rem 0 0; }
        .owner-dash .apt-card .apt-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem; margin-top: 0.5rem; font-size: 0.8125rem; color: #6B5344; }
        .owner-dash .apt-card .apt-rent { color: #6F4E37; font-weight: 600; }
        .owner-dash .apt-card .apt-status { padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; }
        .owner-dash .apt-card .apt-status.activo { background: rgba(82,121,111,.12); color: #427A6B; }
        .owner-dash .apt-card .apt-status.inactivo { background: rgba(166,124,82,.12); color: #8B6914; }
        .owner-dash .apt-card .apt-actions { margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid #F0EBE3; display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .owner-dash .apt-card .apt-actions a { font-size: 0.875rem; font-weight: 500; text-decoration: none; }
        .owner-dash .apt-card .apt-actions .btn-edit { color: #6F4E37; }
        .owner-dash .apt-card .apt-actions .btn-edit:hover { color: #4A3728; text-decoration: underline; }
        .owner-dash .apt-card .apt-actions .btn-view { color: #6B5344; }
        .owner-dash .apt-card .apt-actions .btn-view:hover { color: #2C1810; text-decoration: underline; }
    </style>
    @endpush

    <div class="owner-dash">
        <div class="wrap">
            <header class="main-header">
                <span class="main-header-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </span>
                <div>
                    <h1>Mis alojamientos</h1>
                    <p>Gestiona los inmuebles que tienes publicados. Aquí ves también cuáles han sido rentados y cuántas reservas tienen.</p>
                </div>
            </header>

            @if(session('ok'))
                <div class="alert alert-success">{{ session('ok') }}</div>
            @endif

            {{-- CTA principal --}}
            <a href="{{ route('owner.apartments.index') }}" class="card card-cta" style="margin-bottom: 2rem;">
                <div class="card-cta-inner">
                    <div>
                        <h2>Gestionar mis inmuebles</h2>
                        <p>Ver listado completo, crear nuevo alojamiento, editar o eliminar</p>
                    </div>
                    <svg class="arrow w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>

            {{-- Cuartos rentados --}}
            <div class="section">
                <h2 class="section-title">Cuartos rentados</h2>
                @if($rentedApartments->isEmpty())
                    <div class="rented-empty">Aún no tienes reservas en ninguno de tus inmuebles. Cuando un cliente reserve y pague, aparecerá aquí.</div>
                @else
                    <div class="card">
                        <table class="rented-table">
                            <thead>
                                <tr>
                                    <th>Departamento</th>
                                    <th>Reservas</th>
                                    <th>Última reserva</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rentedApartments as $a)
                                    @php $lastReservation = $a->reservations->first(); @endphp
                                    <tr>
                                        <td>
                                            <span class="rented-title">{{ $a->title }}</span>
                                            <div class="rented-addr">{{ $a->address }}</div>
                                        </td>
                                        <td><span class="badge-rentas">{{ $a->reservations_count }} {{ $a->reservations_count === 1 ? 'reserva' : 'reservas' }}</span></td>
                                        <td>
                                            @if($lastReservation)
                                                {{ $lastReservation->check_in->format('d/m/Y') }} – {{ $lastReservation->check_out->format('d/m/Y') }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('owner.apartments.edit', $a) }}">Editar</a>
                                            <span style="color: #D4C4B0;">·</span>
                                            <a href="{{ route('cuartos.show', $a) }}" target="_blank" rel="noopener">Ver ficha</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            @if($apartments->isEmpty())
                <div class="card empty-state">
                    <div class="empty-state-icon" aria-hidden="true">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3>Aún no tienes alojamientos</h3>
                    <p>Crea el primero desde el enlace de arriba o pide al administrador que lo registre por ti.</p>
                    <a href="{{ route('owner.apartments.create') }}">Crear mi primer alojamiento</a>
                </div>
            @else
                <h2 class="section-title">Tus alojamientos ({{ $apartments->count() }})</h2>
                <div class="apt-grid">
                    @foreach($apartments as $a)
                        <div class="apt-card">
                            <div class="apt-img">
                                @if(!empty($a->photos) && count($a->photos) > 0)
                                    <img src="{{ url('files/'.$a->photos[0]) }}" alt="{{ $a->title }}">
                                @else
                                    <div class="apt-img-placeholder">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="apt-body">
                                <p class="apt-title">{{ $a->title }}</p>
                                <p class="apt-address">{{ $a->address }}</p>
                                <div class="apt-meta">
                                    <span class="apt-rent">${{ number_format($a->monthly_rent, 0) }}/mes</span>
                                    <span>·</span>
                                    <span>Disponible {{ $a->available_from->format('d/m/Y') }}</span>
                                    <span class="apt-status {{ $a->status }}">{{ ucfirst($a->status) }}</span>
                                </div>
                                <div class="apt-actions">
                                    <a href="{{ route('owner.apartments.edit', $a) }}" class="btn-edit">Editar</a>
                                    <a href="{{ route('cuartos.show', $a) }}" class="btn-view" target="_blank" rel="noopener">Ver como visitante</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
