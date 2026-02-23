<x-app-layout>
    @php
        $stripeKey = config('services.stripe.key');
        $dailyRate = $apartment->daily_rate ?? ($apartment->monthly_rent / 30);
        $photos = $apartment->photos ?? [];
        $owner = $apartment->owner;
        $locationParts = array_filter([$apartment->locality, $apartment->city, $apartment->municipality, $apartment->state]);
        $locationLine = implode(', ', $locationParts);
        $nearbyList = is_array($apartment->nearby) ? $apartment->nearby : [];
        $hasMap = $apartment->lat && $apartment->lng;
    @endphp

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <style>
        .show-page { background: #FAF6F0; padding-bottom: 3rem; }
        .show-page .wrap { max-width: 80rem; margin: 0 auto; padding: 1.5rem 1rem; }
        @media (min-width: 1024px) { .show-page .wrap { padding: 2rem; } }
        .show-page .back-link { display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 500; color: #5c5e7a; text-decoration: none; margin-bottom: 1rem; transition: color .15s; }
        .show-page .back-link:hover { color: #6F4E37; }
        .show-page .page-title { font-size: 1.75rem; font-weight: 700; color: #6F4E37; margin: 0 0 1.5rem; line-height: 1.3; letter-spacing: -0.02em; }
        @media (min-width: 640px) { .show-page .page-title { font-size: 2rem; } }
        .show-page .grid-layout { display: grid; gap: 1.5rem; }
        @media (min-width: 1024px) { .show-page .grid-layout { grid-template-columns: 1fr 380px; gap: 2rem; align-items: start; } }
        .show-page .gallery { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; border-radius: 1rem; overflow: hidden; background: #f0f0f5; border: 1px solid #E4E4ED; box-shadow: 0 1px 3px rgba(17,20,57,.04); }
        .show-page .gallery .cell { aspect-ratio: 4/3; overflow: hidden; }
        .show-page .gallery .cell img { width: 100%; height: 100%; object-fit: cover; }
        .show-page .gallery .cell.placeholder { display: flex; align-items: center; justify-content: center; color: #5c5e7a; }
        .show-page .content-card { background: #fff; border: 1px solid #E4E4ED; border-radius: 1rem; padding: 1.5rem; margin-bottom: 1rem; box-shadow: 0 1px 3px rgba(17,20,57,.04); }
        @media (min-width: 640px) { .show-page .content-card { padding: 1.75rem; } }
        .show-page .content-card h3 { font-size: 1.125rem; font-weight: 700; color: #6F4E37; margin: 0 0 0.75rem; }
        .show-page .content-card p, .show-page .content-card .text { font-size: 0.9375rem; color: #5c5e7a; line-height: 1.6; margin: 0; }
        .show-page .services-list { list-style: none; padding: 0; margin: 0; display: grid; gap: 0.5rem; }
        .show-page .services-list li { display: flex; align-items: center; gap: 0.5rem; font-size: 0.9375rem; color: #6F4E37; }
        .show-page .services-list li svg { flex-shrink: 0; color: #6F4E37; }
        .show-page .rules-list { list-style: none; padding: 0; margin: 0; }
        .show-page .rules-list li { display: flex; align-items: flex-start; gap: 0.5rem; font-size: 0.9375rem; color: #5c5e7a; margin-bottom: 0.5rem; }
        .show-page .rules-list li svg { flex-shrink: 0; margin-top: 0.2rem; color: #6F4E37; }
        .show-page .rating-num { font-size: 2rem; font-weight: 700; color: #6F4E37; }
        .show-page .rating-label { font-size: 0.875rem; color: #5c5e7a; margin-top: 0.25rem; }
        .show-page .rating-stars { display: inline-flex; align-items: center; gap: 0.1rem; margin-top: 0.35rem; }
        .show-page .rating-stars svg { width: 18px; height: 18px; color: #6F4E37; }
        .show-page .rating-inputs { display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.5rem; }
        .show-page .rating-inputs input[type="radio"] { display: none; }
        .show-page .rating-inputs label { cursor: pointer; color: #d1d5db; }
        .show-page .rating-inputs label svg { width: 20px; height: 20px; transition: color .15s; }
        .show-page .rating-inputs input[type="radio"]:checked ~ label svg,
        .show-page .rating-inputs label:hover svg,
        .show-page .rating-inputs label:hover ~ label svg { color: #6F4E37; }
        .show-page .comment-box { display: flex; gap: 0.75rem; align-items: flex-end; border: 1px solid #E4E4ED; border-radius: 0.5rem; padding: 0.75rem; background: #fff; }
        .show-page .comment-box input { flex: 1; padding: 0.5rem 0.75rem; border: 1px solid #E4E4ED; border-radius: 0.375rem; font-size: 0.875rem; }
        .show-page .comment-box input:focus { outline: none; border-color: #6F4E37; }
        .show-page .comment-box input.is-invalid { border-color: #E53935; }
        .show-page .comment-box .btn-send { width: 2.5rem; height: 2.5rem; border-radius: 0.375rem; background: #E53935; color: #fff; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .show-page .comment-box .btn-send:hover { background: #C62828; }
        .show-page .comment-item { border-bottom: 1px solid #f0f0f5; }
        .show-page .comment-item:last-of-type { border-bottom: none; }
        .show-page .avatar { width: 2.5rem; height: 2.5rem; border-radius: 50%; object-fit: cover; background: #f0f0f5; }
        .show-page .avatar-initial { width: 2.5rem; height: 2.5rem; border-radius: 50%; background: #6F4E37; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.875rem; flex-shrink: 0; }
        /* Sidebar */
        .show-page .sidebar { position: sticky; top: 1.5rem; background: #fff; border: 1px solid #E4E4ED; border-radius: 1rem; box-shadow: 0 4px 12px rgba(17,20,57,.06); overflow: hidden; }
        .show-page .sidebar .price { font-size: 1.25rem; font-weight: 700; color: #6F4E37; padding: 1.25rem 1.5rem; border-bottom: 1px solid #E4E4ED; background: #FAF6F0; }
        .show-page .sidebar .price span { font-weight: 500; color: #5c5e7a; font-size: 1rem; }
        .show-page .sidebar .form-block { padding: 1.25rem 1.5rem; }
        .show-page .sidebar label { display: block; font-size: 0.875rem; font-weight: 600; color: #6F4E37; margin-bottom: 0.5rem; }
        .show-page .sidebar .input-wrap { position: relative; margin-bottom: 1rem; }
        .show-page .sidebar .input-wrap input, .show-page .sidebar .input-wrap select { width: 100%; padding: 0.625rem 2.5rem 0.625rem 0.75rem; border: 1px solid #E4E4ED; border-radius: 0.5rem; font-size: 0.9375rem; color: #6F4E37; background: #fff; }
        .show-page .sidebar .input-wrap input:focus, .show-page .sidebar .input-wrap select:focus { outline: none; border-color: #6F4E37; box-shadow: 0 0 0 3px rgba(17,20,57,.15); }
        .show-page .sidebar .input-wrap .icon { position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); color: #5c5e7a; pointer-events: none; }
        .show-page .sidebar .btn-reservar { width: 100%; padding: 0.75rem 1rem; border-radius: 0.5rem; font-size: 1rem; font-weight: 600; background: #6F4E37; color: #fff; border: none; cursor: pointer; }
        .show-page .sidebar .btn-reservar:hover { background: #4A3728; }
        .show-page .sidebar .btn-reservar:disabled { opacity: .7; cursor: not-allowed; }
        .show-page .sidebar .arrendador { padding: 1.25rem 1.5rem; border-top: 1px solid #E4E4ED; background: #FAF6F0; }
        .show-page .sidebar .arrendador h4 { font-size: 0.9375rem; font-weight: 700; color: #6F4E37; margin: 0 0 0.75rem; }
        .show-page .sidebar .arrendador .owner-row { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; }
        .show-page .sidebar .arrendador .owner-name { font-weight: 600; color: #6F4E37; }
        .show-page .sidebar .arrendador .owner-role { font-size: 0.8125rem; color: #5c5e7a; }
        .show-page .sidebar .message-area { min-height: 4rem; border: 1px solid #E4E4ED; border-radius: 0.5rem; padding: 0.75rem; font-size: 0.875rem; color: #5c5e7a; background: #FAF6F0; }
        .show-page .rent-type-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; margin-bottom: 1rem; }
        .show-page .rent-type-row label.rent-opt { display: flex; flex-direction: column; padding: 0.75rem; border: 2px solid #E4E4ED; border-radius: 0.5rem; cursor: pointer; margin-bottom: 0; }
        .show-page .rent-type-row label.rent-opt:has(input:checked) { border-color: #6F4E37; background: rgba(17,20,57,.06); }
        .show-page .rent-type-row label.rent-opt input { position: absolute; opacity: 0; }
        .show-page .rent-type-row .rent-opt strong { font-size: 0.9375rem; color: #6F4E37; }
        .show-page .rent-type-row .rent-opt span { font-size: 0.8125rem; color: #5c5e7a; margin-top: 0.25rem; }
        .show-page .card-payment { margin-top: 1rem; padding: 1rem; border: 1px solid #E4E4ED; border-radius: 0.5rem; background: #FAF6F0; }
        .show-page .card-payment-label { font-size: 0.875rem; font-weight: 600; color: #6F4E37; margin-bottom: 0.25rem; }
        .show-page .card-payment-grid { display: flex; flex-direction: column; gap: 0.75rem; }
        .show-page .card-payment-row { display: flex; gap: 0.75rem; flex-wrap: wrap; }
        .show-page .card-payment-row > div { flex: 1 1 7rem; }
        .show-page .card-payment .card-element { min-height: 42px; padding: 0.55rem 0.6rem; border: 1px solid #E4E4ED; border-radius: 0.5rem; background: #fff; }
        .show-page .invalid-feedback { font-size: 0.8125rem; color: #E53935; margin-top: 0.25rem; }
        .show-page .show-breadcrumb { font-size: 0.8125rem; color: #5c5e7a; margin-bottom: 0.75rem; }
        .show-page .show-breadcrumb a { color: #6F4E37; text-decoration: none; }
        .show-page .show-breadcrumb a:hover { text-decoration: underline; color: #4A3728; }
        .show-page .show-breadcrumb-sep { margin: 0 0.35rem; opacity: 0.7; }
        .show-page .section-icon { width: 2rem; height: 2rem; border-radius: 0.5rem; background: rgba(17,20,57,.12); color: #6F4E37; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .show-page .section-icon svg { width: 1.125rem; height: 1.125rem; }
        .show-page .content-card .card-head { display: flex; align-items: flex-start; gap: 0.75rem; margin-bottom: 0.75rem; }
        .show-page .content-card .card-head h3 { margin: 0; }
        .show-page .content-card .card-head .card-desc { font-size: 0.8125rem; color: #5c5e7a; margin: 0.25rem 0 0; }
        .show-page .address-block { font-size: 0.9375rem; color: #6F4E37; line-height: 1.5; }
        .show-page .address-block .address-line { font-weight: 500; }
        .show-page .address-block .location-line { color: #5c5e7a; margin-top: 0.25rem; }
        .show-page .nearby-list { list-style: none; padding: 0; margin: 0; }
        .show-page .nearby-list li { display: flex; align-items: center; gap: 0.5rem; font-size: 0.9375rem; color: #6F4E37; margin-bottom: 0.5rem; }
        .show-page .nearby-list li svg { flex-shrink: 0; color: #6F4E37; }
        .show-page .nearby-list .nearby-meters { font-size: 0.8125rem; color: #5c5e7a; font-weight: 500; }
        .show-page #map-show { width: 100%; min-width: 100%; height: 280px; min-height: 280px; border-radius: 0.75rem; border: 1px solid #E4E4ED; overflow: hidden; background: #f0f0f5; }
        .show-page .map-card .card-head { margin-bottom: 0.5rem; }
    </style>
    @endpush

    <div class="show-page">
        <div class="wrap">
            <nav class="show-breadcrumb" aria-label="Navegación">
                <a href="{{ url('/') }}">Inicio</a>
                <span class="show-breadcrumb-sep">/</span>
                <a href="{{ route('cuartos.index') }}">Alojamientos</a>
                <span class="show-breadcrumb-sep">/</span>
                <span>{{ Str::limit($apartment->title, 40) }}</span>
            </nav>

            <a href="{{ route('cuartos.index') }}" class="back-link">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Volver al listado
            </a>

            <h1 class="page-title">{{ $apartment->title }}</h1>

            <div class="grid-layout">
                {{-- Columna izquierda: galería + contenido --}}
                <div>
                    {{-- Galería 2x2 --}}
                    <div class="gallery" style="grid-template-columns: repeat(2, 1fr);">
                        @for($i = 0; $i < 4; $i++)
                            <div class="cell {{ empty($photos[$i]) ? 'placeholder' : '' }}">
                                @if(!empty($photos[$i]))
                                    <img src="{{ url('files/'.$photos[$i]) }}" alt="{{ $apartment->title }}">
                                @else
                                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/></svg>
                                @endif
                            </div>
                        @endfor
                    </div>

                    {{-- Ubicación: dirección + localidad/ciudad + mapa --}}
                    <div class="content-card">
                        <div class="card-head">
                            <span class="section-icon" aria-hidden="true">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </span>
                            <div>
                                <h3>Ubicación</h3>
                                <p class="card-desc">Dirección y zona del alojamiento.</p>
                            </div>
                        </div>
                        <div class="address-block">
                            @if($apartment->address)
                                <p class="address-line">{{ $apartment->address }}</p>
                            @endif
                            @if($locationLine)
                                <p class="location-line">{{ $locationLine }}</p>
                            @endif
                            @if(!$apartment->address && !$locationLine)
                                <p class="text">Sin dirección registrada.</p>
                            @endif
                        </div>
                        @if($hasMap)
                            <div class="map-card" style="margin-top: 1rem;">
                                <p class="card-desc" style="margin-bottom: 0.5rem;">Mapa de la zona</p>
                                <div id="map-show"></div>
                            </div>
                        @endif
                    </div>

                    {{-- El espacio (capacidad, amueblado) --}}
                    <div class="content-card">
                        <div class="card-head">
                            <span class="section-icon" aria-hidden="true">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </span>
                            <div>
                                <h3>El espacio</h3>
                                <p class="card-desc">Capacidad y características.</p>
                            </div>
                        </div>
                        <p class="text">{{ $apartment->max_people }} persona{{ $apartment->max_people > 1 ? 's' : '' }} · {{ $apartment->is_furnished ? 'Amueblado' : 'Sin amueblar' }}</p>
                        <p class="text" style="margin-top: 0.25rem;">Disponible desde {{ $apartment->available_from->format('d/m/Y') }}.</p>
                    </div>

                    {{-- Acerca del espacio --}}
                    <div class="content-card">
                        <div class="card-head">
                            <span class="section-icon" aria-hidden="true">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            </span>
                            <div>
                                <h3>Acerca del espacio</h3>
                                <p class="card-desc">Descripción del alojamiento.</p>
                            </div>
                        </div>
                        <p class="text">{{ $apartment->description ?: 'Sin descripción adicional.' }}</p>
                    </div>

                    @if(count($nearbyList) > 0)
                    {{-- Cercanías --}}
                    <div class="content-card">
                        <div class="card-head">
                            <span class="section-icon" aria-hidden="true">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            </span>
                            <div>
                                <h3>Cercanías</h3>
                                <p class="card-desc">Lugares de interés cercanos.</p>
                            </div>
                        </div>
                        <ul class="nearby-list">
                            @foreach($nearbyList as $n)
                                @php
                                    $tipo = is_array($n) ? ($n['tipo'] ?? '') : '';
                                    $nombre = is_array($n) ? ($n['nombre'] ?? '') : '';
                                    $metros = is_array($n) ? ($n['metros'] ?? null) : null;
                                    $label = trim($tipo . ($nombre ? ': ' . $nombre : ''));
                                @endphp
                                @if($label)
                                    <li>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                        <span>{{ $label }}</span>
                                        @if($metros !== null && $metros !== '')
                                            <span class="nearby-meters">· {{ (int) $metros }} m</span>
                                        @endif
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- Servicios incluidos --}}
                    <div class="content-card">
                        <div class="card-head">
                            <span class="section-icon" aria-hidden="true">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            </span>
                            <div>
                                <h3>Servicios incluidos</h3>
                                <p class="card-desc">Comodidades del alojamiento.</p>
                            </div>
                        </div>
                        <ul class="services-list">
                            @if($apartment->has_wifi)<li><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg> Wifi</li>@endif
                            @if($apartment->has_ac)<li><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg> Aire acondicionado</li>@endif
                            @if($apartment->has_tv)<li><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg> TV</li>@endif
                            @if($apartment->has_kitchen)<li><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg> Cocina</li>@endif
                            @if($apartment->has_laundry)<li><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg> Lavadora</li>@endif
                            @if($apartment->has_parking)<li><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg> Estacionamiento</li>@endif
                            @if($apartment->has_heating)<li><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg> Calefacción</li>@endif
                            @if($apartment->has_balcony)<li><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg> Balcón</li>@endif
                            <li><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg> Máx. {{ $apartment->max_people }} personas</li>
                        </ul>
                    </div>

                    {{-- Puntuación --}}
                    <div class="content-card">
                        <div class="card-head">
                            <span class="section-icon" aria-hidden="true">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            </span>
                            <div>
                                <h3>Puntuación</h3>
                            </div>
                        </div>
                        @php
                            $avg = round($apartment->rating_avg ?? 0, 1);
                            $hasRating = $avg > 0;
                        @endphp
                        <div class="rating-num">
                            {{ $hasRating ? number_format($avg, 1) : '—' }}
                        </div>
                        <div class="rating-label">
                            @if($hasRating)
                                Puntuación general basada en {{ $apartment->comments->whereNotNull('rating')->count() }} reseña{{ $apartment->comments->whereNotNull('rating')->count() === 1 ? '' : 's' }}.
                            @else
                                Aún no hay puntuaciones para este alojamiento.
                            @endif
                        </div>
                        @if($hasRating)
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="{{ $i <= floor($avg) ? 'currentColor' : 'none' }}" viewBox="0 0 20 20" stroke="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        @endif
                    </div>

                    {{-- Comentarios --}}
                    <div class="content-card">
                        <div class="card-head">
                            <span class="section-icon" aria-hidden="true">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            </span>
                            <div>
                                <h3>Comentarios</h3>
                            </div>
                        </div>
                        @if(session('comment_ok'))
                            <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-2">
                                {{ session('comment_ok') }}
                            </div>
                        @endif

                        @forelse($apartment->comments as $comment)
                            <div class="comment-item">
                                <div class="flex items-start gap-3 mb-3">
                                    @if($comment->user->avatarUrl())
                                        <img src="{{ $comment->user->avatarUrl() }}" alt="" class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                                    @else
                                        <div class="avatar-initial">{{ strtoupper(substr($comment->user->publicName(), 0, 1)) }}</div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-900">{{ $comment->user->publicName() }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $comment->created_at->diffForHumans() }}
                                            @if(!is_null($comment->rating))
                                                · {{ $comment->rating }} / 5
                                            @endif
                                        </div>
                                        <p class="text-gray-700 text-sm mt-1 break-words">{{ $comment->body }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm mb-4">Aún no hay comentarios. ¡Sé el primero en comentar!</p>
                        @endforelse

                        <form action="{{ route('cuartos.comments.store', $apartment) }}" method="POST" class="comment-form">
                            @csrf
                            <div class="mb-2">
                                <span class="text-xs text-gray-600 block mb-1">Puntuación</span>
                                <div class="rating-inputs">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="rating-{{ $i }}" name="rating" value="{{ $i }}" {{ (int) old('rating', 5) === $i ? 'checked' : '' }}>
                                        <label for="rating-{{ $i }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="{{ (int) old('rating', 5) >= $i ? 'currentColor' : 'none' }}" viewBox="0 0 20 20" stroke="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </label>
                                    @endfor
                                </div>
                                @error('rating')
                                    <p class="invalid-feedback mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="comment-box">
                                <input type="text" name="body" id="comment-body" value="{{ old('body') }}" placeholder="Escribir un mensaje" maxlength="1000" required class="@error('body') is-invalid @enderror">
                                <button type="submit" class="btn-send" aria-label="Enviar">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                </button>
                            </div>
                            @error('body')
                                <p class="invalid-feedback mt-1">{{ $message }}</p>
                            @enderror
                        </form>
                    </div>

                    {{-- Reglas y políticas --}}
                    <div class="content-card">
                        <div class="card-head">
                            <span class="section-icon" aria-hidden="true">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </span>
                            <div>
                                <h3>Reglas y políticas</h3>
                                <p class="card-desc">Normas del alojamiento.</p>
                            </div>
                        </div>
                        <ul class="rules-list">
                            @php
                                $rulesArr = is_array($apartment->rules) ? $apartment->rules : (is_string($apartment->rules) ? preg_split('/\r\n|\r|\n/', $apartment->rules) : []);
                                $rulesArr = array_filter(array_map('trim', $rulesArr));
                            @endphp
                            @forelse($rulesArr as $rule)
                                <li>
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                                    {{ $rule }}
                                </li>
                            @empty
                                <li class="text" style="list-style:none; margin-left:0;">No hay reglas especificadas.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- Sidebar: reserva + arrendador --}}
                <aside class="sidebar">
                    <div class="price">${{ number_format($dailyRate, 0) }} <span>por noche</span></div>

                    @if(!Auth::user()->client_id)
                        <div class="form-block">
                            <div class="rounded-lg bg-amber-50 border border-amber-200 p-4 mb-4">
                                <p class="text-sm text-amber-800">Activa tu modo cliente en tu perfil para poder reservar.</p>
                            </div>
                            <a href="{{ route('profile.edit') }}?activate=client#modos-de-uso" class="btn-reservar block text-center text-decoration-none">Ir al perfil para activar modo cliente</a>
                        </div>
                    @else
                        <form id="reservation-form" action="{{ route('reservations.store') }}" method="POST" class="form-block">
                            @csrf
                            <input type="hidden" name="apartment_id" value="{{ $apartment->id }}">

                            <div class="rent-type-row">
                                <label class="rent-opt">
                                    <input type="radio" name="rent_type" value="day" checked>
                                    <strong>Por día</strong>
                                    <span>${{ number_format($dailyRate, 0) }}/día</span>
                                </label>
                                <label class="rent-opt">
                                    <input type="radio" name="rent_type" value="month">
                                    <strong>Por mes</strong>
                                    <span>${{ number_format($apartment->monthly_rent, 0) }}/mes</span>
                                </label>
                            </div>

                            <div id="date-range-wrap">
                                <label>Fechas</label>
                                <div class="input-wrap">
                                    <input type="date" id="check_in" name="check_in" min="{{ now()->format('Y-m-d') }}" placeholder="Selecciona la fecha de ingreso">
                                    <span class="icon"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></span>
                                </div>
                                <div class="input-wrap">
                                    <input type="date" id="check_out" name="check_out" placeholder="Selecciona tu fecha de salida">
                                    <span class="icon"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></span>
                                </div>
                            </div>

                            <div>
                                <label>Huéspedes</label>
                                <div class="input-wrap">
                                    <select name="guests" id="guests">
                                        @for($g = 1; $g <= $apartment->max_people; $g++)
                                            <option value="{{ $g }}">{{ $g }} {{ $g === 1 ? 'huésped' : 'huéspedes' }}</option>
                                        @endfor
                                    </select>
                                    <span class="icon"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
                                </div>
                            </div>

                            <div id="month-agree-wrap" class="hidden rounded-lg p-3 mb-4" style="background: rgba(111,78,55,.08); border: 1px solid rgba(111,78,55,.3);">
                                <p class="text-sm font-medium" style="color: #0d9488;">Renta por mes</p>
                                <p class="text-sm mt-1" style="color: #0f766e;">El día de entrada se acuerda con el anfitrión después de confirmar la reserva.</p>
                            </div>

                            <div>
                                <label for="guest_notes">Notas (opcional)</label>
                                <textarea id="guest_notes" name="guest_notes" rows="2" class="w-full rounded-lg border border-gray-300 p-2 text-sm" placeholder="Peticiones especiales..."></textarea>
                            </div>

                            @if($stripeKey)
                                <div class="card-payment">
                                    <p class="text-sm font-semibold text-gray-700 mb-2">Datos de pago</p>
                                    <div class="card-payment-grid">
                                        <div>
                                            <label class="card-payment-label">Número de tarjeta</label>
                                            <div id="card-number-element" class="card-element"></div>
                                        </div>
                                        <div class="card-payment-row">
                                            <div>
                                                <label class="card-payment-label">Fecha de expiración</label>
                                                <div id="card-expiry-element" class="card-element"></div>
                                            </div>
                                            <div>
                                                <label class="card-payment-label">CVC</label>
                                                <div id="card-cvc-element" class="card-element"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <p id="card-errors" class="invalid-feedback hidden"></p>
                                    <p class="mt-2 text-xs text-gray-500">Pago seguro con Stripe.</p>
                                </div>
                            @else
                                <p class="text-sm text-amber-700 mb-2">Stripe no configurado. Modo prueba.</p>
                            @endif

                            <button type="submit" id="btn-submit" class="btn-reservar mt-4">
                                <span id="btn-text">Reservar</span>
                                <svg id="btn-spinner" class="hidden w-5 h-5 animate-spin inline ml-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            </button>
                        </form>
                    @endif

                    <div class="arrendador">
                        <h4>Arrendador</h4>
                        @if($owner)
                            <div class="owner-row">
                                @if($owner->avatar_path)
                                    <img src="{{ url('files/'.$owner->avatar_path) }}" alt="" class="avatar">
                                @else
                                    <div class="avatar-initial">{{ strtoupper(substr($owner->name, 0, 1)) }}</div>
                                @endif
                                <div>
                                    <div class="owner-name">{{ $owner->name }}</div>
                                    <div class="owner-role">Arrendador</div>
                                </div>
                            </div>
                            @php $ownerUser = $owner->user; @endphp
                            @if($ownerUser && Auth::id() !== $ownerUser->id)
                                <a href="{{ route('messages.index', ['user' => $ownerUser->id, 'apartment' => $apartment->id]) }}"
                                   class="message-area"
                                   style="display:block; text-align:center; border-style:dashed; border-color:#d1d5db; color:#4b5563; cursor:pointer;">
                                    Escribir mensaje al arrendador
                                </a>
                            @else
                                <div class="message-area">Los mensajes de tus huéspedes aparecerán aquí.</div>
                            @endif
                        @else
                            <p class="text-sm text-gray-500">Sin información del arrendador.</p>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </div>

    @if(Auth::user()->client_id && $stripeKey)
    <script src="https://js.stripe.com/v3/"></script>
    <script>
(function() {
    const form = document.getElementById('reservation-form');
    if (!form) return;

    const stripe = Stripe('{{ $stripeKey }}');
    const elements = stripe.elements();
    const elementStyle = {
        base: {
            fontSize: '15px',
            color: '#111827',
            '::placeholder': { color: '#9ca3af' },
            fontFamily: 'system-ui, -apple-system, BlinkMacSystemFont, \"Segoe UI\", sans-serif'
        }
    };
    const cardNumber = elements.create('cardNumber', { style: elementStyle });
    const cardExpiry = elements.create('cardExpiry', { style: elementStyle });
    const cardCvc = elements.create('cardCvc', { style: elementStyle });

    cardNumber.mount('#card-number-element');
    cardExpiry.mount('#card-expiry-element');
    cardCvc.mount('#card-cvc-element');

    function handleCardChange(e) {
        const err = document.getElementById('card-errors');
        err.textContent = e.error ? e.error.message : '';
        err.classList.toggle('hidden', !e.error);
    }
    cardNumber.on('change', handleCardChange);
    cardExpiry.on('change', handleCardChange);
    cardCvc.on('change', handleCardChange);

    const rentTypeDay = form.querySelector('input[name="rent_type"][value="day"]');
    const rentTypeMonth = form.querySelector('input[name="rent_type"][value="month"]');
    const dateRangeWrap = document.getElementById('date-range-wrap');
    const monthAgreeWrap = document.getElementById('month-agree-wrap');
    const checkIn = form.querySelector('#check_in');
    const checkOut = form.querySelector('#check_out');

    function toggleRentType() {
        const isDay = rentTypeDay.checked;
        dateRangeWrap.style.display = isDay ? 'block' : 'none';
        monthAgreeWrap.classList.toggle('hidden', isDay);
        if (checkIn) checkIn.required = isDay;
        if (checkOut) checkOut.required = isDay;
    }
    rentTypeDay.addEventListener('change', toggleRentType);
    rentTypeMonth.addEventListener('change', toggleRentType);
    toggleRentType();

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-submit');
        const btnText = document.getElementById('btn-text');
        const btnSpinner = document.getElementById('btn-spinner');
        const cardErrors = document.getElementById('card-errors');

        btn.disabled = true;
        btnText.textContent = 'Procesando...';
        if (btnSpinner) btnSpinner.classList.remove('hidden');
        cardErrors.classList.add('hidden');

        const payload = new FormData(form);
        if (rentTypeMonth.checked) {
            payload.delete('check_in');
            payload.delete('check_out');
        }
        payload.append('_token', '{{ csrf_token() }}');

        try {
            const res = await fetch('{{ route("reservations.store") }}', {
                method: 'POST',
                body: payload,
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();

            if (!res.ok) throw new Error(data.message || 'Error al crear la reserva');

            if (data.skip_payment) {
                window.location.href = '{{ url("/") }}/reservations/' + data.reservation_id + '/success';
                return;
            }

            const { error } = await stripe.confirmCardPayment(data.client_secret, {
                payment_method: { card: cardNumber }
            });

            if (error) {
                cardErrors.textContent = error.message || 'Error en el pago';
                cardErrors.classList.remove('hidden');
                btn.disabled = false;
                btnText.textContent = 'Reservar';
                if (btnSpinner) btnSpinner.classList.add('hidden');
                return;
            }

            const confirmPayload = new FormData();
            confirmPayload.append('_token', '{{ csrf_token() }}');
            const confirmRes = await fetch('{{ url("/") }}/reservations/' + data.reservation_id + '/confirm-payment', {
                method: 'POST',
                body: confirmPayload,
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const confirmData = await confirmRes.json();
            window.location.href = confirmData.redirect || '{{ url("/") }}/reservations/' + data.reservation_id + '/success';
        } catch (err) {
            cardErrors.textContent = err.message || 'Ha ocurrido un error';
            cardErrors.classList.remove('hidden');
            btn.disabled = false;
            btnText.textContent = 'Reservar';
            if (btnSpinner) btnSpinner.classList.add('hidden');
        }
    });
})();
    </script>
    @endif

    @if($hasMap)
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
    (function() {
        var lat = {{ $apartment->lat }};
        var lng = {{ $apartment->lng }};
        var mapEl = document.getElementById('map-show');
        if (!mapEl || typeof L === 'undefined') return;
        function initMap() {
            var map = L.map('map-show', { center: [lat, lng], zoom: 15 });
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            L.marker([lat, lng]).addTo(map);
            setTimeout(function() {
                map.invalidateSize();
            }, 100);
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initMap);
        } else {
            initMap();
        }
    })();
    </script>
    @endif
</x-app-layout>
