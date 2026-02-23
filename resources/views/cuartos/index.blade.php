<x-app-layout>
    @push('styles')
    <style>
        :root {
            --rh-primary: #6F4E37;
            --rh-primary-dark: #4A3728;
            --rh-secondary: #A67C52;
            --rh-accent: #52796F;
            --rh-red: #DC2626;
        }
        .cuartos-page { background: #FAF6F0; min-height: 100vh; }
        .cuartos-page .cuartos-wrap { max-width: 88rem; margin: 0 auto; padding: 1.5rem 1rem 2.5rem; }
        @media (min-width: 1024px) { .cuartos-page .cuartos-wrap { padding: 2rem 1.5rem 3rem; } }
        .cuartos-page .cuartos-layout { display: flex; flex-direction: column; gap: 1.5rem; align-items: stretch; }
        @media (min-width: 1024px) {
            .cuartos-page .cuartos-layout { flex-direction: row; gap: 2rem; align-items: flex-start; }
        }
        .cuartos-page .cuartos-sidebar { flex-shrink: 0; width: 100%; }
        @media (min-width: 1024px) {
            .cuartos-page .cuartos-sidebar { width: 18rem; max-width: 18rem; align-self: stretch; }
        }

        /* Encabezado de página */
        .cuartos-page .page-header { margin-bottom: 1.25rem; }
        @media (min-width: 1024px) { .cuartos-page .page-header { margin-bottom: 1.5rem; } }
        .cuartos-page .cuartos-breadcrumb { font-size: 0.8125rem; color: #5c5e7a; margin-bottom: 0.75rem; }
        .cuartos-page .cuartos-breadcrumb a { color: #6F4E37; text-decoration: none; }
        .cuartos-page .cuartos-breadcrumb a:hover { text-decoration: underline; color: #4A3728; }
        .cuartos-page .cuartos-breadcrumb-sep { margin: 0 0.35rem; opacity: 0.7; }
        .cuartos-page .page-header-inner { display: flex; align-items: flex-start; gap: 1rem; }
        .cuartos-page .page-header-icon { width: 3rem; height: 3rem; border-radius: 0.75rem; background: rgba(111,78,55,.1); color: var(--rh-primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .cuartos-page .page-header-icon svg { width: 1.5rem; height: 1.5rem; }
        .cuartos-page .page-header h1 { font-size: 1.5rem; font-weight: 700; color: #6F4E37; margin: 0; letter-spacing: -0.02em; }
        .cuartos-page .page-header p { font-size: 0.9375rem; color: #5c5e7a; margin: 0.25rem 0 0; line-height: 1.5; }
        .cuartos-page .page-header-hint { font-size: 0.8125rem; color: #6F4E37; margin-top: 0.5rem; padding: 0.5rem 0.75rem; background: rgba(17,20,57,.08); border-radius: 0.5rem; border: 1px solid rgba(17,20,57,.2); }
        .cuartos-page .page-header-map-link { display: inline-flex; align-items: center; gap: 0.375rem; margin-top: 0.75rem; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; color: #fff; background: #6F4E37; text-decoration: none; transition: background .2s; }
        .cuartos-page .page-header-map-link:hover { background: #4A3728; color: #fff; }

        /* Sidebar de filtros: en desktop queda fijo al hacer scroll para seguir viendo los filtros */
        .cuartos-page .filter-card { background: #fff; border-radius: 1rem; box-shadow: 0 2px 8px rgba(17,20,57,.06), 0 1px 2px rgba(17,20,57,.04); border: 1px solid #E4E4ED; overflow: hidden; }
        @media (min-width: 1024px) {
            .cuartos-page .filter-card { position: sticky; top: 1.5rem; box-shadow: 0 4px 16px rgba(44,24,16,.08); border-radius: 1.25rem; max-height: calc(100vh - 3rem); overflow-y: auto; }
        }
        .cuartos-page .filter-card h2 { padding: 1rem 1.25rem; margin: 0; font-size: 0.9375rem; font-weight: 700; color: #6F4E37; border-bottom: 1px solid #E4E4ED; background: linear-gradient(180deg, #fff 0%, #FAF6F0 100%); letter-spacing: 0.02em; text-transform: uppercase; }
        .cuartos-page .filter-body { padding: 1.25rem; display: flex; flex-direction: column; gap: 1rem; }
        .cuartos-page .filter-block { padding-bottom: 0; margin-bottom: 0; border-bottom: none; }
        .cuartos-page .filter-block + .filter-block { margin-top: 0; }
        .cuartos-page .filter-block > label:first-of-type { display: block; font-size: 0.8125rem; font-weight: 600; color: #6F4E37; margin-bottom: 0.375rem; }
        .cuartos-page .filter-card select,
        .cuartos-page .filter-card input[type="number"],
        .cuartos-page .filter-card input[type="text"].filter-input-text { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #E4E4ED; border-radius: 0.5rem; font-size: 0.875rem; color: #6F4E37; background: #fff; transition: border-color .15s, box-shadow .15s; }
        .cuartos-page .filter-card select:focus,
        .cuartos-page .filter-card input[type="number"]:focus,
        .cuartos-page .filter-card .filter-input-text:focus { outline: none; border-color: var(--rh-primary); box-shadow: 0 0 0 3px rgba(17,20,57,.15); }
        /* Bloque precio: más bonito */
        .cuartos-page .filter-block.price-block { padding: 1rem 1rem 1.125rem; margin: 0 -0.25rem; border-radius: 0.75rem; background: linear-gradient(145deg, #fff 0%, #FAF6F0 100%); border: 1px solid rgba(228,228,237,.8); }
        .cuartos-page .filter-card .price-display { font-size: 1rem; font-weight: 700; color: var(--rh-primary); letter-spacing: -0.02em; margin-bottom: 0.875rem; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
        .cuartos-page .filter-card .price-display .price-min, .cuartos-page .filter-card .price-display .price-max { background: rgba(111,78,55,.1); color: #6F4E37; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.875rem; }
        .cuartos-page .filter-card .price-display .price-sep { color: #5c5e7a; font-weight: 500; font-size: 0.8125rem; }
        .cuartos-page .price-slider-container { position: relative; height: 2.5rem; display: flex; align-items: center; padding: 0 2px; }
        .cuartos-page .price-slider-track { position: absolute; left: 0; right: 0; height: 8px; background: #E4E4ED; border-radius: 4px; pointer-events: none; box-shadow: inset 0 1px 2px rgba(17,20,57,.06); }
        .cuartos-page .price-slider-fill { position: absolute; height: 8px; background: linear-gradient(90deg, var(--rh-primary) 0%, var(--rh-secondary) 100%); border-radius: 4px; pointer-events: none; transition: left .12s ease, width .12s ease; box-shadow: 0 1px 2px rgba(17,20,57,.25); }
        .cuartos-page .price-slider-container input[type="range"] { position: absolute; left: 0; width: 100%; height: 2.5rem; margin: 0; -webkit-appearance: none; appearance: none; background: transparent; pointer-events: none; }
        .cuartos-page .price-slider-container input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none; appearance: none; width: 22px; height: 22px; border-radius: 50%;
            background: linear-gradient(180deg, #fff 0%, #F8F6F3 100%); border: 2px solid var(--rh-primary);
            box-shadow: 0 2px 6px rgba(17,20,57,.15), 0 0 0 0 rgba(17,20,57,.2);
            cursor: pointer; pointer-events: auto; transition: transform .2s ease, box-shadow .2s ease;
        }
        .cuartos-page .price-slider-container input[type="range"]::-webkit-slider-thumb:hover {
            transform: scale(1.12); box-shadow: 0 3px 8px rgba(17,20,57,.2), 0 0 0 4px rgba(17,20,57,.15);
        }
        .cuartos-page .price-slider-container input[type="range"]:focus-visible::-webkit-slider-thumb {
            box-shadow: 0 0 0 4px rgba(17,20,57,.25);
        }
        .cuartos-page .price-slider-container input[type="range"]::-webkit-slider-runnable-track { height: 8px; background: transparent; }
        .cuartos-page .price-slider-container input[type="range"]::-moz-range-thumb {
            width: 22px; height: 22px; border-radius: 50%;
            background: linear-gradient(180deg, #fff 0%, #F8F6F3 100%); border: 2px solid var(--rh-primary);
            box-shadow: 0 2px 6px rgba(17,20,57,.15); cursor: pointer; pointer-events: auto;
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .cuartos-page .price-slider-container input[type="range"]::-moz-range-thumb:hover {
            transform: scale(1.12); box-shadow: 0 3px 8px rgba(17,20,57,.2), 0 0 0 4px rgba(17,20,57,.15);
        }
        .cuartos-page .price-slider-container input[type="range"]::-moz-range-track { height: 8px; background: transparent; }
        .cuartos-page .price-slider-container input[type="range"]:nth-of-type(2) { pointer-events: none; }
        .cuartos-page .price-slider-container input[type="range"]:nth-of-type(2)::-webkit-slider-thumb { pointer-events: auto; }
        .cuartos-page .price-slider-container input[type="range"]:nth-of-type(2)::-moz-range-thumb { pointer-events: auto; }
        .cuartos-page .filter-card .services-label { margin-bottom: 0.5rem; }
        .cuartos-page .filter-card .services-grid { display: grid; gap: 0.5rem; grid-template-columns: 1fr; margin-top: 0.375rem; }
        @media (min-width: 380px) { .cuartos-page .filter-card .services-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .cuartos-page .filter-card .services-grid { grid-template-columns: 1fr; } }
        .cuartos-page .filter-card .check-group { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.625rem; border-radius: 0.5rem; border: 1px solid #E4E4ED; background: #FAF6F0; cursor: pointer; transition: background .15s, border-color .15s; }
        .cuartos-page .filter-card .check-group:hover { border-color: #c8c8d8; background: #fff; }
        .cuartos-page .filter-card .check-group:has(input:checked) { background: rgba(17,20,57,.08); border-color: var(--rh-primary); }
        .cuartos-page .filter-card .check-group input[type="checkbox"] { position: absolute; width: 1.25rem; height: 1.25rem; margin: 0; opacity: 0; cursor: pointer; }
        .cuartos-page .filter-card .check-group .check-box { width: 1.125rem; height: 1.125rem; border-radius: 0.25rem; border: 2px solid #c8c8d8; background: #fff; flex-shrink: 0; display: flex; align-items: center; justify-content: center; transition: background .2s, border-color .2s; }
        .cuartos-page .filter-card .check-group input:checked + .check-box { background: var(--rh-primary); border-color: var(--rh-primary); }
        .cuartos-page .filter-card .check-group input:checked + .check-box::after { content: ''; display: block; width: 4px; height: 8px; border-right: 2.5px solid #fff; border-bottom: 2.5px solid #fff; transform: rotate(45deg); margin-top: -2px; }
        .cuartos-page .filter-card .check-group .check-label { font-size: 0.8125rem; font-weight: 500; color: #6F4E37; }
        .cuartos-page .filter-body .filter-actions { display: flex; flex-direction: column; gap: 0.5rem; margin-top: 0.25rem; padding-top: 0.5rem; border-top: 1px solid #E4E4ED; }
        @media (min-width: 380px) { .cuartos-page .filter-body .filter-actions { flex-direction: row; flex-wrap: wrap; } }
        .cuartos-page .filter-card .btn-apply { padding: 0.625rem 1.25rem; border: none; border-radius: 0.5rem; background: var(--rh-primary); color: #fff; font-weight: 600; font-size: 0.875rem; cursor: pointer; transition: background .2s, transform .1s; }
        .cuartos-page .filter-card .btn-apply:hover { background: var(--rh-primary-dark); }
        .cuartos-page .filter-card .btn-clear { padding: 0.5rem 1rem; font-size: 0.875rem; color: #5c5e7a; text-decoration: none; transition: color .15s; }
        .cuartos-page .filter-card .btn-clear:hover { color: var(--rh-primary); }
        .cuartos-page .filter-body .hidden { display: none !important; }
        .cuartos-page .form-help { font-size: 0.8125rem; color: #5c5e7a; margin-top: 0.25rem; }

        /* Contenido principal */
        .cuartos-page .cuartos-main { flex: 1; min-width: 0; }
        .cuartos-page .main-header { margin-bottom: 1.5rem; }
        .cuartos-page .main-header-inner { display: flex; align-items: flex-start; gap: 1rem; }
        .cuartos-page .main-header-icon { width: 3rem; height: 3rem; border-radius: 0.75rem; background: rgba(111,78,55,.1); color: var(--rh-primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .cuartos-page .main-header-icon svg { width: 1.5rem; height: 1.5rem; }
        .cuartos-page .main-header h2 { font-size: 1.5rem; font-weight: 700; color: #6F4E37; margin: 0; letter-spacing: -0.02em; }
        .cuartos-page .main-header p { font-size: 0.9375rem; color: #5c5e7a; margin: 0.25rem 0 0; line-height: 1.5; }
        .cuartos-page .cuartos-main section { margin-top: 0; }
        .cuartos-page .section-title { font-size: 1.125rem; font-weight: 700; color: #6F4E37; margin: 0 0 1rem; letter-spacing: -0.01em; }
        .cuartos-page .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            max-width: 100%;
            gap: 1.5rem;
        }
        @media (min-width: 640px) { .cuartos-page .cards-grid { gap: 2rem; } }
        @media (min-width: 1280px) { .cuartos-page .cards-grid { grid-template-columns: repeat(3, 1fr); } }
        /* Card estilo shop adaptado: fondo pastel café, etiqueta vertical, hover con movimiento */
        .cuartos-page .room-card {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.25rem 1.5rem;
            border-radius: 1rem;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            transition: box-shadow .4s ease, transform .2s ease;
        }
        .cuartos-page .room-card:nth-child(3n+1) { background: #FAF6F0; }
        .cuartos-page .room-card:nth-child(3n+2) { background: #F5EDE4; }
        .cuartos-page .room-card:nth-child(3n+3) { background: #EDE4D8; }
        .cuartos-page .room-card:hover {
            box-shadow: 0 .5rem 1.5rem rgba(111,78,55,.12), 0 0 0 1px rgba(111,78,55,.08);
        }
        .cuartos-page .room-card .card__img {
            width: 100%;
            max-width: 100%;
            padding: 0.75rem 0;
            transition: transform .5s ease, margin-left .5s ease;
            position: relative;
        }
        .cuartos-page .room-card .card__img .room-img-wrap {
            position: relative;
            width: 100%;
            aspect-ratio: 4/3;
            min-height: 200px;
            border-radius: 0.75rem;
            overflow: hidden;
            background: #e8e0d6;
            box-shadow: 0 2px 8px rgba(111,78,55,.08);
        }
        .cuartos-page .room-card .card__img .room-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .5s ease;
        }
        .cuartos-page .room-card .room-badge {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            padding: 0.2rem 0.45rem;
            border-radius: 0.375rem;
            background: var(--rh-primary);
            color: #fff;
            font-size: 0.625rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            z-index: 1;
        }
        .cuartos-page .room-card .room-fav {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            width: 2rem;
            height: 2rem;
            border: none;
            border-radius: 0.5rem;
            background: rgba(255,255,255,.9);
            box-shadow: 0 1px 3px rgba(0,0,0,.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6B5344;
            cursor: pointer;
            transition: color .2s, background .2s;
            z-index: 1;
        }
        .cuartos-page .room-card .room-fav:hover { color: var(--rh-red); background: #fff; }
        .cuartos-page .room-card .room-fav .room-fav-icon-filled { display: none; }
        .cuartos-page .room-card .room-fav .room-fav-icon-outline { display: block; }
        .cuartos-page .room-card .room-fav.is-favorited { color: var(--rh-red); }
        .cuartos-page .room-card .room-fav.is-favorited .room-fav-icon-filled { display: block; }
        .cuartos-page .room-card .room-fav.is-favorited .room-fav-icon-outline { display: none; }
        /* Etiqueta vertical izquierda (nombre) — se desliza al hover; por encima de la imagen */
        .cuartos-page .room-card .card__name {
            position: absolute;
            left: -100%;
            top: 0;
            width: 3.25rem;
            height: 100%;
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            text-align: center;
            background: var(--rh-primary);
            color: #fff;
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 0.02em;
            padding: 1rem 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: left .5s ease;
            line-height: 1.2;
            z-index: 2;
        }
        .cuartos-page .room-card:hover .card__name { left: 0; }
        .cuartos-page .room-card:hover .card__img {
            transform: rotate(4deg);
            margin-left: 2.25rem;
        }
        .cuartos-page .room-card:hover .card__img .room-img-wrap img {
            transform: scale(1.04);
        }
        .cuartos-page .room-card:hover .card__precis {
            margin-left: 2.25rem;
            padding: 0 0.5rem;
        }
        /* Pie de card: favorito, precio, ver */
        .cuartos-page .room-card .card__precis {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 0.5rem;
            margin-top: 0.75rem;
            transition: margin-left .5s ease, padding .5s ease;
        }
        .cuartos-page .room-card .card__precis .card__icon {
            font-size: 1.25rem;
            color: #6F4E37;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .cuartos-page .room-card .card__precis .card__icon:hover { color: var(--rh-red); }
        .cuartos-page .room-card .card__precis .card__prices {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            min-width: 0;
        }
        .cuartos-page .room-card .card__preci--before {
            font-size: 0.75rem;
            color: var(--rh-secondary);
            margin-bottom: 0.15rem;
        }
        .cuartos-page .room-card .card__preci--now {
            font-size: 1rem;
            font-weight: 700;
            color: var(--rh-primary);
        }
        .cuartos-page .room-card .card__precis .card__icon--ver {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--rh-primary);
            text-decoration: none;
        }
        .cuartos-page .room-card .card__precis .card__icon--ver:hover { color: var(--rh-primary-dark); text-decoration: underline; }
        .cuartos-page .room-img-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #6B5344; }
        .cuartos-page .room-img-placeholder svg { width: 2.5rem; height: 2.5rem; }

        /* Estado vacío */
        .cuartos-page .empty-state { background: #fff; border-radius: 1rem; border: 1px solid #E4E4ED; box-shadow: 0 1px 3px rgba(17,20,57,.04); padding: 3rem 1.5rem; text-align: center; }
        .cuartos-page .empty-state-icon { width: 4rem; height: 4rem; margin: 0 auto 1rem; border-radius: 1rem; background: rgba(17,20,57,.08); color: #5c5e7a; display: flex; align-items: center; justify-content: center; }
        .cuartos-page .empty-state-icon svg { width: 2rem; height: 2rem; }
        .cuartos-page .empty-state h3 { font-size: 1.125rem; font-weight: 600; color: #6F4E37; margin: 0 0 0.375rem; }
        .cuartos-page .empty-state p { font-size: 0.9375rem; color: #5c5e7a; margin: 0; line-height: 1.5; }
        .cuartos-page .empty-state a { display: inline-flex; align-items: center; gap: 0.375rem; margin-top: 1rem; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; color: #fff; background: var(--rh-primary); text-decoration: none; transition: background .2s; }
        .cuartos-page .empty-state a:hover { background: var(--rh-primary-dark); }

        /* Paginación */
        .cuartos-page .pagination-wrap { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #E4E4ED; }
        .cuartos-page .pagination-wrap nav { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; }
        .cuartos-page .pagination-wrap nav > div:first-child { flex-shrink: 0; }
        .cuartos-page .pagination-wrap nav p { margin: 0; font-size: 0.875rem; color: #5c5e7a; line-height: 1.5; }
        .cuartos-page .pagination-wrap nav p span { background: none !important; border: none !important; padding: 0 !important; min-width: auto !important; border-radius: 0; font-weight: 600; color: #6F4E37; }
        .cuartos-page .pagination-wrap nav > div:last-child { display: flex; flex-wrap: wrap; gap: 0.375rem; align-items: center; justify-content: flex-end; }
        .cuartos-page .pagination-wrap nav > div:last-child a,
        .cuartos-page .pagination-wrap nav > div:last-child > span > span,
        .cuartos-page .pagination-wrap nav > div:last-child > span > a { display: inline-flex; align-items: center; justify-content: center; min-width: 2.25rem; height: 2.25rem; padding: 0 0.625rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; border: 1px solid #E4E4ED; background: #fff; color: #6F4E37; transition: background .15s, border-color .15s, color .15s; box-sizing: border-box; }
        .cuartos-page .pagination-wrap nav > div:last-child a:hover { background: #FAF6F0; border-color: var(--rh-primary); color: var(--rh-primary); }
        .cuartos-page .pagination-wrap nav > div:last-child > span > span { background: #FAF6F0; color: #5c5e7a; cursor: default; }
        .cuartos-page .pagination-wrap nav span[aria-current="page"] > span { background: var(--rh-primary); border-color: var(--rh-primary); color: #fff; }
        .cuartos-page .pagination-wrap nav > div:last-child > span > span[aria-disabled] { opacity: 0.7; }
        .cuartos-page .pagination-wrap nav > div:last-child svg { width: 1.125rem; height: 1.125rem; }
        .cuartos-page .pagination-wrap nav .relative.z-0 > span { margin-left: 0; }
        .cuartos-page .pagination-wrap nav .relative.z-0 > a { margin-left: 0; }
        .cuartos-page .pagination-wrap nav .relative.z-0 { gap: 0.375rem; }
        .cuartos-page #cuartos-list-container { position: relative; }
        .cuartos-page #cuartos-list-container.is-loading { min-height: 8rem; pointer-events: none; }
        .cuartos-page .cuartos-loader { display: none; position: absolute; inset: 0; background: rgba(248,248,249,.92); align-items: center; justify-content: center; flex-direction: column; gap: 1rem; z-index: 10; border-radius: 1rem; }
        .cuartos-page #cuartos-list-container.is-loading .cuartos-loader { display: flex; }
        .cuartos-page .cuartos-loader-spinner { width: 3rem; height: 3rem; border: 3px solid #E4E4ED; border-top-color: var(--rh-primary); border-radius: 50%; animation: cuartos-spin 0.75s linear infinite; }
        .cuartos-page .cuartos-loader-text { font-size: 0.9375rem; font-weight: 500; color: #5c5e7a; }
        @keyframes cuartos-spin { to { transform: rotate(360deg); } }
        @media (max-width: 639px) {
            .cuartos-page .pagination-wrap nav > div:first-child { display: none; }
            .cuartos-page .pagination-wrap nav > div.flex { display: flex !important; justify-content: center; width: 100%; gap: 0.75rem; }
        }
    </style>
    @endpush

    <div class="cuartos-page min-h-screen">
        <div class="cuartos-wrap">
            {{-- Encabezado de la página --}}
            {{-- Breadcrumb --}}
            <nav class="cuartos-breadcrumb" aria-label="Navegación">
                <a href="{{ url('/') }}">Inicio</a>
                <span class="cuartos-breadcrumb-sep">/</span>
                <span>Alojamientos</span>
            </nav>

            <header class="page-header">
                <div class="page-header-inner">
                    <span class="page-header-icon" aria-hidden="true">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </span>
                    <div>
                        <h1>Alojamientos</h1>
                        <p>Encuentra tu espacio por día o por mes. Filtra por estado, ciudad, municipio, cercanías, precio y servicios.</p>
                        @if($userLocation && ($userLocation['city'] ?? $userLocation['state']))
                            <p class="page-header-hint">Tu ubicación en perfil: {{ trim(implode(', ', array_filter([$userLocation['city'] ?? null, $userLocation['state'] ?? null]))) }} — te mostramos resultados que puedes filtrar por tu zona.</p>
                        @endif
                        <a href="{{ route('cuartos.mapa') }}" class="page-header-map-link">🗺️ Ver todos en el mapa</a>
                    </div>
                </div>
            </header>

            <div class="cuartos-layout">
                {{-- Filtros (se desplaza con el scroll) --}}
                <aside class="cuartos-sidebar">
                    <form method="GET" id="filter-form" class="filter-card">
                        <h2>Filtrar</h2>
                        <div class="filter-body">
                            @if(request()->hasAny(['q','state','city','municipality','cercania','min_rent','max_rent','user_lat','user_lng','has_wifi','has_ac','has_tv','has_kitchen','has_laundry','has_parking','has_heating','has_balcony']))
                                <div class="filter-block">
                                    <a href="{{ route('cuartos.index') }}" class="btn-clear" style="display:inline-block;margin-bottom:0.5rem;">Limpiar filtros</a>
                                </div>
                            @endif
                            <div class="filter-block">
                                <label for="q">Buscar</label>
                                <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Título, dirección, ciudad..." class="filter-input-text" autocomplete="off">
                            </div>
                            <div class="filter-block">
                                <label for="state">Estado</label>
                                <input type="text" name="state" id="state" list="datalist-state" value="{{ request('state') }}" placeholder="Ej. Yucatán" class="filter-input-text">
                                <datalist id="datalist-state">@foreach($states as $s)<option value="{{ $s }}">@endforeach</datalist>
                            </div>
                            <div class="filter-block">
                                <label for="city">Ciudad</label>
                                <input type="text" name="city" id="city" list="datalist-city" value="{{ request('city') }}" placeholder="Ej. Mérida" class="filter-input-text">
                                <datalist id="datalist-city">@foreach($cities as $c)<option value="{{ $c }}">@endforeach</datalist>
                            </div>
                            <div class="filter-block">
                                <label for="municipality">Municipio</label>
                                <input type="text" name="municipality" id="municipality" list="datalist-municipality" value="{{ request('municipality') }}" placeholder="Ej. Mérida" class="filter-input-text">
                                <datalist id="datalist-municipality">@foreach($municipalities as $m)<option value="{{ $m }}">@endforeach</datalist>
                            </div>
                            <div class="filter-block price-block">
                                <label>Precio (renta mensual)</label>
                                <p class="price-display" id="price-display"><span class="price-min" id="price-min-text">${{ number_format($minRent, 0) }}</span><span class="price-sep"> a </span><span class="price-max" id="price-max-text">${{ number_format($maxRent, 0) }}</span></p>
                                <div class="price-slider-container" aria-label="Rango de precio">
                                    <div class="price-slider-track"></div>
                                    <div class="price-slider-fill" id="price-slider-fill"></div>
                                    <input type="range" id="min_rent_slider" min="1000" max="100000" step="1000" value="{{ request('min_rent', 1000) }}" aria-label="Precio mínimo">
                                    <input type="range" id="max_rent_slider" min="1000" max="100000" step="1000" value="{{ request('max_rent', 100000) }}" aria-label="Precio máximo">
                                    <input type="hidden" name="min_rent" id="min_rent" value="{{ request('min_rent', 1000) }}">
                                    <input type="hidden" name="max_rent" id="max_rent" value="{{ request('max_rent', 100000) }}">
                                </div>
                            </div>
                            <div class="filter-block">
                                <label class="services-label">Cercanías</label>
                                <label class="check-group" style="margin-bottom: 0.5rem;">
                                    <input type="checkbox" name="near_me" id="near_me" value="1" {{ request()->filled('user_lat') ? 'checked' : '' }}>
                                    <span class="check-box" aria-hidden="true"></span>
                                    <span class="check-label">Cercanías a mi ubicación actual</span>
                                </label>
                                <div id="near-me-options" class="{{ request()->filled('user_lat') ? '' : 'hidden' }}" style="margin-top: 0.5rem;">
                                    <label for="radius_km" style="font-size: 0.75rem; color: #5c5e7a;">Radio máximo (km)</label>
                                    <select name="radius_km" id="radius_km" class="filter-input-text" style="margin-top: 0.25rem;">
                                        <option value="5" {{ request('radius_km', 10) == 5 ? 'selected' : '' }}>5 km</option>
                                        <option value="10" {{ request('radius_km', 10) == 10 ? 'selected' : '' }}>10 km</option>
                                        <option value="15" {{ request('radius_km') == 15 ? 'selected' : '' }}>15 km</option>
                                        <option value="20" {{ request('radius_km') == 20 ? 'selected' : '' }}>20 km</option>
                                    </select>
                                </div>
                                <input type="hidden" name="user_lat" id="user_lat" value="{{ request('user_lat') }}">
                                <input type="hidden" name="user_lng" id="user_lng" value="{{ request('user_lng') }}">
                                <p class="form-help" style="margin-top: 0.5rem; font-size: 0.75rem;">O filtra por tipo de lugar cercano al inmueble:</p>
                                <select name="cercania" id="cercania" style="margin-top: 0.25rem;">
                                    <option value="">Cualquiera</option>
                                    <option value="Universidad" {{ request('cercania') === 'Universidad' ? 'selected' : '' }}>Universidad</option>
                                    <option value="Hospital" {{ request('cercania') === 'Hospital' ? 'selected' : '' }}>Hospital</option>
                                    <option value="Transporte" {{ request('cercania') === 'Transporte' ? 'selected' : '' }}>Transporte</option>
                                    <option value="Centro" {{ request('cercania') === 'Centro' ? 'selected' : '' }}>Centro</option>
                                    <option value="Centro comercial" {{ request('cercania') === 'Centro comercial' ? 'selected' : '' }}>Centro comercial</option>
                                    <option value="Parque" {{ request('cercania') === 'Parque' ? 'selected' : '' }}>Parque</option>
                                    <option value="Supermercado" {{ request('cercania') === 'Supermercado' ? 'selected' : '' }}>Supermercado</option>
                                    <option value="Gimnasio" {{ request('cercania') === 'Gimnasio' ? 'selected' : '' }}>Gimnasio</option>
                                </select>
                            </div>
                            <div class="filter-block">
                                <label class="services-label">Servicios incluidos</label>
                                <div class="services-grid">
                                    @foreach([
                                        'has_wifi' => 'WiFi',
                                        'has_heating' => 'Agua caliente',
                                        'has_ac' => 'Aire acondicionado',
                                        'has_kitchen' => 'Cocina',
                                        'has_laundry' => 'Lavadora',
                                        'has_tv' => 'TV',
                                        'has_parking' => 'Estacionamiento',
                                        'has_balcony' => 'Balcón',
                                    ] as $key => $label)
                                        <label class="check-group">
                                            <input type="checkbox" name="{{ $key }}" value="1" {{ request($key) ? 'checked' : '' }}>
                                            <span class="check-box" aria-hidden="true"></span>
                                            <span class="check-label">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="filter-actions">
                                <button type="submit" class="btn-apply">Aplicar filtros</button>
                                <a href="{{ route('cuartos.index') }}" class="btn-clear">Limpiar</a>
                            </div>
                        </div>
                    </form>
                </aside>

                {{-- Listado de habitaciones --}}
                <main class="cuartos-main">
                    <div id="cuartos-list-container">
                    <div class="cuartos-loader" aria-hidden="true">
                        <span class="cuartos-loader-spinner"></span>
                        <span class="cuartos-loader-text">Cargando…</span>
                    </div>
                    @if($apartments->isEmpty())
                        <div class="empty-state">
                            <div class="empty-state-icon" aria-hidden="true">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <h3>No hay resultados</h3>
                            <p>No hay alojamientos que coincidan con los filtros. Prueba a ampliar criterios o ver todos.</p>
                            <a href="{{ route('cuartos.index') }}">Ver todos los alojamientos <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                        </div>
                    @else
                        <section>
                            <h3 class="section-title">Alojamientos disponibles</h3>
                            <div class="cards-grid">
                                @foreach($apartments as $a)
                                    @php
                                        $dailyRate = $a->daily_rate ?? $a->monthly_rent / 30;
                                        $isNew = $a->available_from && $a->available_from->isAfter(now()->subDays(14));
                                        $locationParts = array_filter([$a->locality, $a->city, $a->municipality, $a->state]);
                                        $locationLine = implode(', ', array_slice($locationParts, 0, 2)) ?: $a->address;
                                        $avg = round($a->rating_avg ?? 0, 1);
                                        $isFav = in_array($a->id, $favoritedIds ?? []);
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
                        </section>

                        <div class="pagination-wrap">
                            {{ $apartments->withQueryString()->links() }}
                        </div>
                    @endif
                    </div>
                </main>
            </div>
        </div>
    </div>

    <script>
        (function(){
            var PRICE_MAX = 100000;
            var minSlider = document.getElementById('min_rent_slider');
            var maxSlider = document.getElementById('max_rent_slider');
            var minHidden = document.getElementById('min_rent');
            var maxHidden = document.getElementById('max_rent');
            var minText = document.getElementById('price-min-text');
            var maxText = document.getElementById('price-max-text');
            var fill = document.getElementById('price-slider-fill');
            if (!minSlider || !maxSlider || !minHidden || !maxHidden) return;
            function updatePriceRange() {
                var minVal = parseInt(minSlider.value, 10);
                var maxVal = parseInt(maxSlider.value, 10);
                if (minVal > maxVal) {
                    maxVal = minVal;
                    maxSlider.value = maxVal;
                }
                minHidden.value = minVal;
                maxHidden.value = maxVal;
                if (minText) minText.textContent = '$' + minVal.toLocaleString('es-MX');
                if (maxText) maxText.textContent = '$' + maxVal.toLocaleString('es-MX');
                if (fill) {
                    var left = (minVal / PRICE_MAX) * 100;
                    var width = ((maxVal - minVal) / PRICE_MAX) * 100;
                    fill.style.left = left + '%';
                    fill.style.width = width + '%';
                }
            }
            minSlider.addEventListener('input', updatePriceRange);
            maxSlider.addEventListener('input', updatePriceRange);
            updatePriceRange();
        })();

        (function(){
            var nearMe = document.getElementById('near_me');
            var nearMeOptions = document.getElementById('near-me-options');
            var userLat = document.getElementById('user_lat');
            var userLng = document.getElementById('user_lng');
            if (nearMe && nearMeOptions) {
                nearMe.addEventListener('change', function(){
                    nearMeOptions.classList.toggle('hidden', !nearMe.checked);
                    if (!nearMe.checked && userLat && userLng) { userLat.value = ''; userLng.value = ''; }
                });
            }
            var filterForm = document.getElementById('filter-form');
            if (filterForm && nearMe && userLat && userLng) {
                filterForm.addEventListener('submit', function(e){
                    if (!nearMe.checked) return;
                    if (userLat.value && userLng.value) return;
                    e.preventDefault();
                    var btn = filterForm.querySelector('button[type="submit"]');
                    if (btn) { btn.disabled = true; btn.textContent = 'Obteniendo ubicación...'; }
                    navigator.geolocation.getCurrentPosition(
                        function(pos){
                            userLat.value = pos.coords.latitude;
                            userLng.value = pos.coords.longitude;
                            if (btn) { btn.disabled = false; btn.textContent = 'Aplicar filtros'; }
                            filterForm.submit();
                        },
                        function(){
                            if (btn) { btn.disabled = false; btn.textContent = 'Aplicar filtros'; }
                            alert('No se pudo obtener tu ubicación. Revisa que el navegador tenga permiso de ubicación o desactiva "Cercanías a mi ubicación actual".');
                        },
                        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                    );
                });
            }
        })();

        (function(){
            var container = document.getElementById('cuartos-list-container');
            if (!container) return;
            document.addEventListener('click', function(e){
                var link = e.target.closest('.cuartos-page .pagination-wrap a[href]');
                if (!link || !link.href || link.target === '_blank') return;
                var url = link.getAttribute('href');
                if (!url || url === '#' || url.indexOf(window.location.origin) !== 0) return;
                e.preventDefault();
                container.classList.add('is-loading');
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } })
                    .then(function(r){ return r.text(); })
                    .then(function(html){
                        var parser = new DOMParser();
                        var doc = parser.parseFromString(html, 'text/html');
                        var newContainer = doc.getElementById('cuartos-list-container');
                        if (newContainer) container.innerHTML = newContainer.innerHTML;
                        container.classList.remove('is-loading');
                        history.pushState({}, '', url);
                        container.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    })
                    .catch(function(){
                        container.classList.remove('is-loading');
                        window.location.href = url;
                    });
            });
            window.addEventListener('popstate', function(){ window.location.reload(); });
        })();

        (function(){
            var csrfToken = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            if (!csrfToken) {
                var csrfInput = document.querySelector('input[name="_token"]');
                csrfToken = csrfInput ? csrfInput.value : '{{ csrf_token() }}';
            }
            document.addEventListener('click', function(e){
                var btn = e.target.closest('.room-fav');
                if (!btn) return;
                e.preventDefault();
                e.stopPropagation();
                var url = btn.getAttribute('data-url');
                if (!url) return;
                btn.disabled = true;
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({})
                }).then(function(r){ return r.json().then(function(data){ return { ok: r.ok, status: r.status, data: data }; }); })
                  .then(function(res){
                    btn.disabled = false;
                    if (res.status === 401) {
                        if (window.confirm('Para guardar favoritos debes iniciar sesión. ¿Ir a iniciar sesión?')) {
                            window.location.href = '{{ route("login") }}?redirect=' + encodeURIComponent(window.location.href);
                        }
                        return;
                    }
                    if (res.ok && res.data && typeof res.data.favorited !== 'undefined') {
                        btn.classList.toggle('is-favorited', res.data.favorited);
                        btn.setAttribute('aria-label', res.data.favorited ? 'Quitar de favoritos' : 'Añadir a favoritos');
                    }
                  })
                  .catch(function(){ btn.disabled = false; });
            });
        })();
    </script>
</x-app-layout>
