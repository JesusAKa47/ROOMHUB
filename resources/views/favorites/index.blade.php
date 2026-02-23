<x-app-layout>
    @push('styles')
    <style>
        :root { --rh-primary: #6F4E37; --rh-primary-dark: #4A3728; --rh-secondary: #A67C52; --rh-accent: #52796F; --rh-red: #DC2626; }
        .favorites-page { background: #FAF6F0; min-height: 100vh; }
        .favorites-page .wrap { max-width: 88rem; margin: 0 auto; padding: 1.5rem 1rem 2.5rem; }
        @media (min-width: 1024px) { .favorites-page .wrap { padding: 2rem 1.5rem 3rem; } }
        .favorites-page .breadcrumb { font-size: 0.8125rem; color: #5c5e7a; margin-bottom: 0.75rem; }
        .favorites-page .breadcrumb a { color: #6F4E37; text-decoration: none; }
        .favorites-page .breadcrumb a:hover { text-decoration: underline; color: #4A3728; }
        .favorites-page .page-header { margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 1rem; }
        .favorites-page .page-header-icon { width: 3rem; height: 3rem; border-radius: 0.75rem; background: rgba(111,78,55,.1); color: var(--rh-primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .favorites-page .page-header h1 { font-size: 1.5rem; font-weight: 700; color: #6F4E37; margin: 0; letter-spacing: -0.02em; }
        .favorites-page .page-header p { font-size: 0.9375rem; color: #5c5e7a; margin: 0.25rem 0 0; line-height: 1.5; }
        .favorites-page .cards-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; max-width: 100%; }
        @media (min-width: 640px) { .favorites-page .cards-grid { gap: 2rem; } }
        @media (min-width: 1280px) { .favorites-page .cards-grid { grid-template-columns: repeat(3, 1fr); } }
        .favorites-page .room-card { position: relative; display: flex; flex-direction: column; align-items: center; padding: 1.25rem 1.5rem; border-radius: 1rem; overflow: hidden; text-decoration: none; color: inherit; transition: box-shadow .4s ease, transform .2s ease; }
        .favorites-page .room-card:nth-child(3n+1) { background: #FAF6F0; }
        .favorites-page .room-card:nth-child(3n+2) { background: #F5EDE4; }
        .favorites-page .room-card:nth-child(3n+3) { background: #EDE4D8; }
        .favorites-page .room-card:hover { box-shadow: 0 .5rem 1.5rem rgba(111,78,55,.12), 0 0 0 1px rgba(111,78,55,.08); }
        .favorites-page .room-card .card__img { width: 100%; max-width: 100%; padding: 0.75rem 0; transition: transform .5s ease, margin-left .5s ease; position: relative; }
        .favorites-page .room-card .card__img .room-img-wrap { position: relative; width: 100%; aspect-ratio: 4/3; min-height: 200px; border-radius: 0.75rem; overflow: hidden; background: #e8e0d6; box-shadow: 0 2px 8px rgba(111,78,55,.08); }
        .favorites-page .room-card .card__img .room-img-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .5s ease; }
        .favorites-page .room-card .room-badge { position: absolute; top: 0.5rem; left: 0.5rem; padding: 0.2rem 0.45rem; border-radius: 0.375rem; background: var(--rh-primary); color: #fff; font-size: 0.625rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; z-index: 1; }
        .favorites-page .room-card .room-fav { position: absolute; top: 0.5rem; right: 0.5rem; width: 2rem; height: 2rem; border: none; border-radius: 0.5rem; background: rgba(255,255,255,.9); box-shadow: 0 1px 3px rgba(0,0,0,.1); display: flex; align-items: center; justify-content: center; color: #6B5344; cursor: pointer; transition: color .2s, background .2s; z-index: 1; }
        .favorites-page .room-card .room-fav:hover { color: var(--rh-red); background: #fff; }
        .favorites-page .room-card .room-fav .room-fav-icon-filled { display: none; }
        .favorites-page .room-card .room-fav .room-fav-icon-outline { display: block; }
        .favorites-page .room-card .room-fav.is-favorited { color: var(--rh-red); }
        .favorites-page .room-card .room-fav.is-favorited .room-fav-icon-filled { display: block; }
        .favorites-page .room-card .room-fav.is-favorited .room-fav-icon-outline { display: none; }
        .favorites-page .room-card .card__name { position: absolute; left: -100%; top: 0; width: 3.25rem; height: 100%; writing-mode: vertical-rl; transform: rotate(180deg); text-align: center; background: var(--rh-primary); color: #fff; font-weight: 700; font-size: 0.75rem; letter-spacing: 0.02em; padding: 1rem 0; display: flex; align-items: center; justify-content: center; transition: left .5s ease; line-height: 1.2; z-index: 2; }
        .favorites-page .room-card:hover .card__name { left: 0; }
        .favorites-page .room-card:hover .card__img { transform: rotate(4deg); margin-left: 2.25rem; }
        .favorites-page .room-card:hover .card__img .room-img-wrap img { transform: scale(1.04); }
        .favorites-page .room-card:hover .card__precis { margin-left: 2.25rem; padding: 0 0.5rem; }
        .favorites-page .room-card .card__precis { width: 100%; display: flex; justify-content: space-between; align-items: flex-end; gap: 0.5rem; margin-top: 0.75rem; transition: margin-left .5s ease, padding .5s ease; }
        .favorites-page .room-card .card__precis .card__prices { display: flex; flex-direction: column; align-items: flex-start; min-width: 0; }
        .favorites-page .room-card .card__preci--before { font-size: 0.75rem; color: var(--rh-secondary); margin-bottom: 0.15rem; }
        .favorites-page .room-card .card__preci--now { font-size: 1rem; font-weight: 700; color: var(--rh-primary); }
        .favorites-page .room-card .card__precis .card__icon--ver { font-size: 0.875rem; font-weight: 600; color: var(--rh-primary); text-decoration: none; }
        .favorites-page .room-card .card__precis .card__icon--ver:hover { color: var(--rh-primary-dark); text-decoration: underline; }
        .favorites-page .room-img-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #6B5344; }
        .favorites-page .room-img-placeholder svg { width: 2.5rem; height: 2.5rem; }
        .favorites-page .empty-state { background: #fff; border-radius: 1rem; border: 1px solid #E4E4ED; box-shadow: 0 1px 3px rgba(17,20,57,.04); padding: 3rem 1.5rem; text-align: center; }
        .favorites-page .empty-state-icon { width: 4rem; height: 4rem; margin: 0 auto 1rem; border-radius: 1rem; background: rgba(111,78,55,.08); color: #5c5e7a; display: flex; align-items: center; justify-content: center; }
        .favorites-page .empty-state-icon svg { width: 2rem; height: 2rem; }
        .favorites-page .empty-state h3 { font-size: 1.125rem; font-weight: 600; color: #6F4E37; margin: 0 0 0.375rem; }
        .favorites-page .empty-state p { font-size: 0.9375rem; color: #5c5e7a; margin: 0; line-height: 1.5; }
        .favorites-page .empty-state a { display: inline-flex; align-items: center; gap: 0.375rem; margin-top: 1rem; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; color: #fff; background: var(--rh-primary); text-decoration: none; transition: background .2s; }
        .favorites-page .empty-state a:hover { background: var(--rh-primary-dark); }
    </style>
    @endpush

    <div class="favorites-page min-h-screen">
        <div class="wrap">
            <nav class="breadcrumb" aria-label="Navegación">
                <a href="{{ url('/') }}">Inicio</a>
                <span style="margin:0 0.35rem;opacity:0.7;">/</span>
                <a href="{{ route('cuartos.index') }}">Alojamientos</a>
                <span style="margin:0 0.35rem;opacity:0.7;">/</span>
                <span>Mis favoritos</span>
            </nav>

            <header class="page-header">
                <span class="page-header-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </span>
                <div>
                    <h1>Mis favoritos</h1>
                    <p>Alojamientos que guardaste para ver después.</p>
                </div>
            </header>

            @if($apartments->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon" aria-hidden="true">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <h3>Aún no tienes favoritos</h3>
                    <p>Explora alojamientos y haz clic en el corazón para guardarlos aquí.</p>
                    <a href="{{ route('cuartos.index') }}">Explorar alojamientos</a>
                </div>
            @else
                <div class="cards-grid">
                    @foreach($apartments as $a)
                        @php
                            $dailyRate = $a->daily_rate ?? $a->monthly_rent / 30;
                            $isNew = $a->available_from && $a->available_from->isAfter(now()->subDays(14));
                            $isFav = in_array($a->id, $favoritedIds ?? []);
                            $avg = round($a->rating_avg ?? 0, 1);
                        @endphp
                        <a href="{{ route('cuartos.show', $a) }}" class="room-card">
                            <div class="card__name" aria-hidden="true">{{ Str::limit($a->title, 20) }}</div>
                            <div class="card__img">
                                <div class="room-img-wrap">
                                    @if(!empty($a->photos) && count($a->photos) > 0)
                                        <img src="{{ url('files/'.$a->photos[0]) }}" alt="{{ $a->title }}">
                                    @else
                                        <div class="room-img-placeholder">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/></svg>
                                        </div>
                                    @endif
                                    @if($isNew)
                                        <span class="room-badge">Nuevo</span>
                                    @endif
                                    <button type="button" class="room-fav {{ $isFav ? 'is-favorited' : '' }}" data-url="{{ route('favorites.toggle', $a) }}" aria-label="{{ $isFav ? 'Quitar de favoritos' : 'Añadir a favoritos' }}">
                                        <svg class="room-fav-icon-outline" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        <svg class="room-fav-icon-filled" width="18" height="18" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="card__precis">
                                <div class="card__prices">
                                    <span class="card__preci card__preci--before">${{ number_format($dailyRate, 0) }}/noche</span>
                                    <span class="card__preci card__preci--now">${{ number_format($a->monthly_rent, 0) }}/mes</span>
                                    @if($avg > 0)
                                        <span class="card__preci card__preci--before" style="margin-top:0.2rem;">★ {{ number_format($avg, 1) }}</span>
                                    @endif
                                </div>
                                <span class="card__icon card__icon--ver">Ver</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
    (function(){
        var csrfToken = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if (!csrfToken) { var csrfInput = document.querySelector('input[name="_token"]'); csrfToken = csrfInput ? csrfInput.value : '{{ csrf_token() }}'; }
        document.addEventListener('click', function(e){
            var btn = e.target.closest('.room-fav');
            if (!btn) return;
            e.preventDefault();
            e.stopPropagation();
            var url = btn.getAttribute('data-url');
            if (!url) return;
            btn.disabled = true;
            fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }, body: JSON.stringify({}) })
                .then(function(r){ return r.json().then(function(data){ return { ok: r.ok, status: r.status, data: data }; }); })
                .then(function(res){
                    btn.disabled = false;
                    if (res.status === 401) return;
                    if (res.ok && res.data && typeof res.data.favorited !== 'undefined') {
                        btn.classList.toggle('is-favorited', res.data.favorited);
                        btn.setAttribute('aria-label', res.data.favorited ? 'Quitar de favoritos' : 'Añadir a favoritos');
                        if (!res.data.favorited) {
                            var card = btn.closest('.room-card');
                            if (card) card.remove();
                        }
                    }
                })
                .catch(function(){ btn.disabled = false; });
        });
    })();
    </script>
</x-app-layout>
