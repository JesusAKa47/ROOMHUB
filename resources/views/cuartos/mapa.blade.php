<x-app-layout>
    @php
        $apartmentsJson = $apartments->map(function ($a) {
            $photos = $a->photos ?? [];
            $locationParts = array_filter([$a->locality, $a->city, $a->municipality, $a->state]);
            return [
                'id' => $a->id,
                'title' => $a->title,
                'lat' => (float) $a->lat,
                'lng' => (float) $a->lng,
                'photo' => !empty($photos[0]) ? url('files/' . $photos[0]) : null,
                'daily_rate' => (float) ($a->daily_rate ?? $a->monthly_rent / 30),
                'monthly_rent' => (float) $a->monthly_rent,
                'location' => implode(', ', $locationParts) ?: $a->address,
                'url' => route('cuartos.show', $a),
                'rating_avg' => round($a->rating_avg ?? 0, 1),
                'max_people' => (int) $a->max_people,
            ];
        })->values();
    @endphp

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <style>
        body.page-mapa { overflow: hidden; height: 100vh; display: flex; flex-direction: column; }
        body.page-mapa main { position: relative; z-index: 0; flex: 1; min-height: 0; display: flex; flex-direction: column; overflow: hidden; }
        .mapa-page { height: 100%; min-height: 0; display: flex; flex-direction: column; background: #F8F8F9; }
        .mapa-page .mapa-header { flex-shrink: 0; padding: 0.75rem 1rem; background: #fff; border-bottom: 1px solid #E4E4ED; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
        .mapa-page .mapa-header h1 { font-size: 1.25rem; font-weight: 700; color: #6F4E37; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
        .mapa-page .mapa-header .mapa-actions { display: flex; align-items: center; gap: 0.5rem; }
        .mapa-page .mapa-header a { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; text-decoration: none; background: #6F4E37; color: #fff; transition: background .2s; }
        .mapa-page .mapa-header a:hover { background: #4A3728; }
        .mapa-page #map-cuartos { flex: 1; min-height: 0; width: 100%; background: #E4E4ED; }
        .mapa-page .leaflet-control-attribution { display: none !important; }
        .mapa-page .leaflet-popup-content-wrapper { border-radius: 1rem; box-shadow: 0 8px 24px rgba(17,20,57,.18); border: 1px solid #E4E4ED; padding: 0; overflow: hidden; background: #fff; }
        .mapa-page .leaflet-popup-content { margin: 0; width: 300px !important; }
        .mapa-page .leaflet-popup-tip { background: #fff; }
        .mapa-page .mapa-popup-card { font-family: inherit; }
        .mapa-page .mapa-popup-card .mapa-card-img { width: 100%; height: 140px; object-fit: cover; background: #f0f0f5; display: block; }
        .mapa-page .mapa-popup-card .mapa-card-body { padding: 1rem 1.125rem; }
        .mapa-page .mapa-popup-card .mapa-card-title { font-size: 1rem; font-weight: 700; color: #6F4E37; margin: 0 0 0.375rem; line-height: 1.3; letter-spacing: -0.02em; }
        .mapa-page .mapa-popup-card .mapa-card-location { font-size: 0.8125rem; color: #5c5e7a; margin: 0 0 0.5rem; line-height: 1.4; display: flex; align-items: flex-start; gap: 0.35rem; }
        .mapa-page .mapa-popup-card .mapa-card-location svg { flex-shrink: 0; margin-top: 0.1rem; color: #6F4E37; }
        .mapa-page .mapa-popup-card .mapa-card-price { font-size: 1rem; font-weight: 700; color: #6F4E37; margin: 0 0 0.5rem; }
        .mapa-page .mapa-popup-card .mapa-card-meta { font-size: 0.8125rem; color: #5c5e7a; margin: 0 0 1rem; }
        .mapa-page .mapa-popup-card .mapa-card-btn { display: inline-flex; align-items: center; justify-content: center; width: 100%; padding: 0.625rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; text-decoration: none; background: #6F4E37; color: #fff; transition: background .2s; }
        .mapa-page .mapa-popup-card .mapa-card-btn:hover { background: #4A3728; color: #fff; }
        .mapa-page .leaflet-popup-close-button { color: #5c5e7a !important; font-size: 1.25rem !important; padding: 0.5rem !important; right: 0.25rem !important; top: 0.25rem !important; }
        .mapa-page .leaflet-popup-close-button:hover { color: #6F4E37 !important; }
    </style>
    @endpush

    <div class="mapa-page">
        <header class="mapa-header">
            <h1>
                <span aria-hidden="true">🗺️</span>
                Mapa de alojamientos
            </h1>
            <div class="mapa-actions">
                <a href="{{ route('cuartos.index') }}">← Listado</a>
            </div>
        </header>
        <div id="map-cuartos"></div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
    (function() {
        var apartments = @json($apartmentsJson);
        var mapEl = document.getElementById('map-cuartos');
        if (!mapEl || apartments.length === 0) {
            if (mapEl) mapEl.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#5c5e7a;">No hay alojamientos con ubicación en el mapa.</div>';
            return;
        }

        var centerLat = apartments.reduce(function(s, a) { return s + a.lat; }, 0) / apartments.length;
        var centerLng = apartments.reduce(function(s, a) { return s + a.lng; }, 0) / apartments.length;

        function escapeHtml(s) {
            if (!s) return '';
            var div = document.createElement('div');
            div.textContent = s;
            return div.innerHTML;
        }

        function buildPopupContent(apt) {
            var title = escapeHtml(apt.title || 'Alojamiento');
            var location = escapeHtml(apt.location || '');
            var daily = Math.round(apt.daily_rate).toLocaleString('es-MX');
            var monthly = Math.round(apt.monthly_rent).toLocaleString('es-MX');
            var meta = apt.max_people + ' persona' + (apt.max_people > 1 ? 's' : '');
            if (apt.rating_avg > 0) meta += ' · ' + apt.rating_avg + ' ★';
            var imgHtml = apt.photo
                ? '<img class="mapa-card-img" src="' + escapeHtml(apt.photo) + '" alt="">'
                : '<div class="mapa-card-img" style="display:flex;align-items:center;justify-content:center;color:#B8A99A;"><svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/></svg></div>';
            var locationHtml = location
                ? '<p class="mapa-card-location"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>' + location + '</p>'
                : '';
            return '<div class="mapa-popup-card">' +
                imgHtml +
                '<div class="mapa-card-body">' +
                '<h3 class="mapa-card-title">' + title + '</h3>' +
                locationHtml +
                '<p class="mapa-card-price">$' + daily + ' por noche · $' + monthly + '/mes</p>' +
                '<p class="mapa-card-meta">' + escapeHtml(meta) + '</p>' +
                '<a class="mapa-card-btn" href="' + escapeHtml(apt.url) + '">Ver detalle</a>' +
                '</div></div>';
        }

        function initMap() {
            var map = L.map('map-cuartos', { attributionControl: false }).setView([centerLat, centerLng], 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
            setTimeout(function() { map.invalidateSize(); }, 150);

            apartments.forEach(function(apt) {
                var marker = L.marker([apt.lat, apt.lng]).addTo(map);
                marker._apartment = apt;
                marker.bindPopup(buildPopupContent(apt), {
                    maxWidth: 320,
                    minWidth: 300,
                    offset: [0, -10],
                    autoPan: true,
                    closeButton: true,
                    className: 'mapa-popup'
                });
                marker.on('click', function() {
                    map.setView([apt.lat, apt.lng], 15, { animate: true, duration: 0.35 });
                    marker.openPopup();
                });
            });

            if (apartments.length === 1) {
                map.setView([apartments[0].lat, apartments[0].lng], 8);
            }
        }

        if (document.readyState === 'complete') {
            initMap();
        } else {
            window.addEventListener('load', initMap);
        }
    })();
    </script>
</x-app-layout>
