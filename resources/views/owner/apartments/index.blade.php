<x-app-layout>
    @push('styles')
    <style>
        .owner-apt-page { background: #F8F8F9; min-height: 100vh; }
        .owner-apt-page .wrap { max-width: 64rem; margin: 0 auto; padding: 1.5rem 1rem 2.5rem; }
        @media (min-width: 1024px) { .owner-apt-page .wrap { padding: 2rem 1.5rem 3rem; } }
        .owner-apt-page .main-header { display: flex; flex-wrap: wrap; align-items: flex-start; justify-content: space-between; gap: 1rem; margin-bottom: 1.75rem; }
        .owner-apt-page .main-header-inner { display: flex; align-items: flex-start; gap: 1rem; }
        .owner-apt-page .main-header-icon { width: 3rem; height: 3rem; border-radius: 0.75rem; background: rgba(111,78,55,.1); color: #6F4E37; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .owner-apt-page .main-header-icon svg { width: 1.5rem; height: 1.5rem; }
        .owner-apt-page .main-header h1 { font-size: 1.5rem; font-weight: 700; color: #6F4E37; margin: 0; letter-spacing: -0.02em; }
        .owner-apt-page .main-header p { font-size: 0.9375rem; color: #5c5e7a; margin: 0.25rem 0 0; line-height: 1.5; }
        .owner-apt-page .btn-new { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; border-radius: 0.5rem; background: #6F4E37; color: #fff; font-weight: 600; font-size: 0.875rem; text-decoration: none; transition: background .2s; box-shadow: 0 1px 3px rgba(111,78,55,.06); }
        .owner-apt-page .btn-new:hover { background: #4A3728; }
        .owner-apt-page .alert { padding: 0.75rem 1rem; border-radius: 0.5rem; margin-bottom: 1.25rem; font-size: 0.875rem; }
        .owner-apt-page .alert-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
        .owner-apt-page .card { background: #fff; border-radius: 1rem; border: 1px solid #E8E2DA; box-shadow: 0 1px 3px rgba(111,78,55,.04); overflow: hidden; }
        .owner-apt-page .empty-state { padding: 3rem 1.5rem; text-align: center; }
        .owner-apt-page .empty-state-icon { width: 4rem; height: 4rem; margin: 0 auto 1rem; border-radius: 1rem; background: rgba(111,78,55,.08); color: #5c5e7a; display: flex; align-items: center; justify-content: center; }
        .owner-apt-page .empty-state-icon svg { width: 2rem; height: 2rem; }
        .owner-apt-page .empty-state h3 { font-size: 1.125rem; font-weight: 600; color: #6F4E37; margin: 0 0 0.375rem; }
        .owner-apt-page .empty-state p { font-size: 0.9375rem; color: #5c5e7a; margin: 0; line-height: 1.5; }
        .owner-apt-page .empty-state a { display: inline-flex; align-items: center; gap: 0.375rem; margin-top: 1rem; padding: 0.5rem 1.25rem; border-radius: 0.5rem; background: #6F4E37; color: #fff; font-weight: 600; font-size: 0.875rem; text-decoration: none; transition: background .2s; }
        .owner-apt-page .empty-state a:hover { background: #4A3728; }
        .owner-apt-page .section-title { font-size: 1.125rem; font-weight: 700; color: #6F4E37; margin: 0 0 1rem; letter-spacing: -0.01em; }
        .owner-apt-page .apt-grid { display: grid; gap: 1.25rem; grid-template-columns: 1fr; }
        @media (min-width: 640px) { .owner-apt-page .apt-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .owner-apt-page .apt-grid { grid-template-columns: repeat(3, 1fr); } }
        .owner-apt-page .apt-card { background: #fff; border-radius: 1rem; border: 1px solid #E8E2DA; box-shadow: 0 1px 3px rgba(111,78,55,.04); overflow: hidden; transition: box-shadow .2s, border-color .2s; }
        .owner-apt-page .apt-card:hover { box-shadow: 0 4px 12px rgba(111,78,55,.06); border-color: #D4C4B0; }
        .owner-apt-page .apt-card .apt-img { position: relative; aspect-ratio: 4/3; background: #F0EBE3; overflow: hidden; }
        .owner-apt-page .apt-card .apt-img img { width: 100%; height: 100%; object-fit: cover; }
        .owner-apt-page .apt-img-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #B8A99A; }
        .owner-apt-page .apt-img-placeholder svg { width: 2.5rem; height: 2.5rem; }
        .owner-apt-page .apt-card .apt-body { padding: 1rem 1.25rem; }
        .owner-apt-page .apt-card .apt-title { font-weight: 600; color: #6F4E37; margin: 0; font-size: 0.9375rem; }
        .owner-apt-page .apt-card .apt-address { font-size: 0.8125rem; color: #5c5e7a; margin: 0.25rem 0 0; }
        .owner-apt-page .apt-card .apt-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem; margin-top: 0.5rem; font-size: 0.8125rem; color: #5c5e7a; }
        .owner-apt-page .apt-card .apt-rent { color: #6F4E37; font-weight: 600; }
        .owner-apt-page .apt-card .apt-status { padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; }
        .owner-apt-page .apt-card .apt-status.activo { background: rgba(82,121,111,.12); color: #427A6B; }
        .owner-apt-page .apt-card .apt-status.inactivo { background: rgba(166,124,82,.12); color: #8B6914; }
        .owner-apt-page .apt-card .apt-actions { margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid #F0EBE3; display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem; }
        .owner-apt-page .apt-card .apt-actions a { font-size: 0.875rem; font-weight: 500; text-decoration: none; color: #6F4E37; }
        .owner-apt-page .apt-card .apt-actions a:hover { color: #4A3728; text-decoration: underline; }
        .owner-apt-page .apt-card .apt-actions form { display: inline; }
        .owner-apt-page .apt-card .apt-actions button { font-size: 0.875rem; font-weight: 500; background: none; border: none; cursor: pointer; color: #DC2626; padding: 0; }
        .owner-apt-page .apt-card .apt-actions button:hover { color: #B91C1C; text-decoration: underline; }
        .owner-apt-page .apt-card .apt-actions .sep { color: #D4C4B0; font-size: 0.875rem; user-select: none; }
    </style>
    @endpush

    <div class="owner-apt-page">
        <div class="wrap">
            <header class="main-header">
                <div class="main-header-inner">
                    <span class="main-header-icon" aria-hidden="true">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </span>
                    <div>
                        <h1>Mis alojamientos</h1>
                        <p>Gestiona tus inmuebles. Crea nuevos, edita o da de baja. Los cambios se reflejan según el estado (activo/inactivo).</p>
                    </div>
                </div>
                <a href="{{ route('owner.apartments.create') }}" class="btn-new">+ Alta de inmueble</a>
            </header>

            @if(session('ok'))
                <div class="alert alert-success">{{ session('ok') }}</div>
            @endif

            @if($apartments->isEmpty())
                <div class="card empty-state">
                    <div class="empty-state-icon" aria-hidden="true">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3>Aún no tienes alojamientos</h3>
                    <p>Publica tu primer inmueble y empieza a recibir reservas.</p>
                    <a href="{{ route('owner.apartments.create') }}">Crear mi primer inmueble</a>
                </div>
            @else
                <h2 class="section-title">Tus inmuebles ({{ $apartments->count() }})</h2>
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
                                    <a href="{{ route('owner.apartments.edit', $a) }}">Editar</a>
                                    <span class="sep">·</span>
                                    <form action="{{ route('owner.apartments.destroy', $a) }}" method="POST" data-confirm="¿Eliminar este inmueble? Esta acción no se puede deshacer." data-confirm-icon="warning">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
