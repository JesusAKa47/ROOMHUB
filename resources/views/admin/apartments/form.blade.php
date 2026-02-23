@extends('layouts.admin')
@section('title', $apartment->exists ? 'Editar inmueble' : 'Alta de inmueble')

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
<style>
  /* Formulario inmueble — alineado con diseño RoomHub (admin) */
  body.admin-page .admin-wrap { max-width: 52rem; }
  .form-inmueble { padding: 0; }
  .form-inmueble__breadcrumb { font-size: 0.8125rem; color: #5c5e7a; margin-bottom: 1rem; }
  .form-inmueble__breadcrumb a { color: #6F4E37; text-decoration: none; }
  .form-inmueble__breadcrumb a:hover { text-decoration: underline; color: #4A3728; }
  .form-inmueble__breadcrumb-sep { margin: 0 0.35rem; opacity: 0.7; }
  .form-inmueble__title { font-size: 1.5rem; font-weight: 700; color: #6F4E37; letter-spacing: -0.02em; margin: 0 0 1.5rem; }
  .form-inmueble .form-section { margin-bottom: 2rem; }
  .form-inmueble .form-section:last-of-type { margin-bottom: 0; }
  .form-inmueble .form-section__header {
    display: flex; align-items: flex-start; gap: 0.75rem;
    margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid #E4E4ED;
  }
  .form-inmueble .form-section__icon {
    width: 2.25rem; height: 2.25rem; border-radius: 0.5rem;
    background: rgba(111,78,55,.1); color: #6F4E37;
    display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;
  }
  .form-inmueble .form-section__title { font-size: 1rem; font-weight: 700; color: #6F4E37; margin: 0 0 0.15rem; }
  .form-inmueble .form-section__desc { font-size: 0.8125rem; color: #5c5e7a; margin: 0; }
  .form-inmueble .form-label {
    display: block; font-size: 0.8125rem; font-weight: 600; color: #5c5e7a; margin-bottom: 0.375rem;
  }
  .form-inmueble .form-label.required::after { content: " *"; color: #dc3545; }
  .form-inmueble .form-control-custom,
  .form-inmueble input[type="text"], .form-inmueble input[type="number"], .form-inmueble input[type="date"],
  .form-inmueble input[type="file"], .form-inmueble select, .form-inmueble textarea {
    width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #E4E4ED; border-radius: 0.5rem;
    font-size: 0.9375rem; color: #6F4E37; background: #fff;
  }
  .form-inmueble .form-control-custom:focus, .form-inmueble input:focus, .form-inmueble select:focus, .form-inmueble textarea:focus {
    outline: none; border-color: #6F4E37; box-shadow: 0 0 0 3px rgba(111,78,55,.12);
  }
  .form-inmueble input.is-invalid, .form-inmueble select.is-invalid, .form-inmueble textarea.is-invalid { border-color: #dc3545; }
  .form-inmueble .invalid-feedback { font-size: 0.8125rem; color: #dc3545; margin-top: 0.25rem; }
  .form-inmueble .form-help { font-size: 0.8125rem; color: #5c5e7a; margin-top: 0.25rem; }
  .form-inmueble .form-row { display: grid; gap: 1rem; margin-bottom: 1rem; }
  .form-inmueble .form-row:last-child { margin-bottom: 0; }
  .form-inmueble .form-row--2 { grid-template-columns: 1fr 1fr; }
  .form-inmueble .form-row--3 { grid-template-columns: 1fr 1fr 1fr; }
  @media (max-width: 767px) {
    .form-inmueble .form-row--2, .form-inmueble .form-row--3 { grid-template-columns: 1fr; }
  }
  .form-inmueble .form-group { margin-bottom: 1rem; }
  .form-inmueble .form-group:last-child { margin-bottom: 0; }
  .form-inmueble .map-wrap { height: 320px; border-radius: 0.5rem; overflow: hidden; background: #F0EBE3; border: 1px solid #E4E4ED; }
  .form-inmueble .map-toolbar { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.75rem; }
  .form-inmueble .map-toolbar-btn {
    display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.5rem 0.75rem; border-radius: 0.5rem;
    border: 1px solid #E4E4ED; background: #FAF6F0; color: #6F4E37; font-weight: 600; font-size: 0.875rem; cursor: pointer;
  }
  .form-inmueble .map-toolbar-btn:hover:not(:disabled) { background: rgba(111,78,55,.08); border-color: #c8c8d8; }
  .form-inmueble .map-toolbar-btn:disabled { opacity: 0.6; cursor: not-allowed; }
  .form-inmueble .map-toolbar-btn-icon { font-size: 1rem; }
  .form-inmueble .map-info { margin-top: 0.5rem; font-size: 0.8125rem; color: #5c5e7a; display: flex; flex-direction: column; gap: 0.25rem; }
  .form-inmueble .map-coords { font-family: ui-monospace, monospace; }
  .form-inmueble .map-address { font-style: italic; }
  .form-inmueble .map-nearby-panel { margin-top: 1rem; padding: 1rem; background: #FAF6F0; border: 1px solid #E4E4ED; border-radius: 0.5rem; }
  .form-inmueble .map-nearby-panel-title { font-size: 0.9375rem; font-weight: 700; color: #6F4E37; margin: 0 0 0.35rem; }
  .form-inmueble .map-nearby-list { list-style: none; padding: 0; margin: 0.5rem 0 0; max-height: 200px; overflow-y: auto; }
  .form-inmueble .map-nearby-list li {
    display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; padding: 0.5rem 0;
    border-bottom: 1px solid #F0EBE3; font-size: 0.875rem;
  }
  .form-inmueble .map-nearby-list li:last-child { border-bottom: none; }
  .form-inmueble .map-nearby-list .add-nearby-btn {
    padding: 0.25rem 0.5rem; border-radius: 0.375rem; border: 1px solid #6F4E37; background: #6F4E37; color: #fff;
    font-size: 0.75rem; font-weight: 600; cursor: pointer; white-space: nowrap;
  }
  .form-inmueble .map-nearby-list .add-nearby-btn:hover { background: #4A3728; }
  .form-inmueble .map-nearby-list .distance { color: #52796F; font-weight: 600; }
  .form-inmueble .check-row { display: flex; flex-wrap: wrap; gap: 0.75rem 1.5rem; align-items: center; }
  .form-inmueble .check-row label { display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 500; margin: 0; cursor: pointer; font-size: 0.9375rem; color: #6F4E37; }
  .form-inmueble .check-row input[type="checkbox"], .form-inmueble .check-row input[type="radio"] { width: 1.125rem; height: 1.125rem; accent-color: #6F4E37; }
  .form-inmueble .amenities-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 0.5rem 1rem; }
  .form-inmueble .amenities-grid label { margin: 0; }
  .form-inmueble .nearby-entries { display: flex; flex-direction: column; gap: 0.5rem; }
  .form-inmueble .nearby-row { display: grid; grid-template-columns: 1fr 2fr 5rem auto; gap: 0.5rem; align-items: center; }
  @media (max-width: 767px) { .form-inmueble .nearby-row { grid-template-columns: 1fr 1fr 4rem auto; } }
  @media (max-width: 575px) { .form-inmueble .nearby-row { grid-template-columns: 1fr auto; } .form-inmueble .nearby-row .nearby-row-meta { grid-column: 1; } }
  .form-inmueble .nearby-row input[type="text"] { padding: 0.5rem 0.75rem; border: 1px solid #E4E4ED; border-radius: 0.5rem; font-size: 0.9375rem; }
  .form-inmueble .nearby-row-meta { display: flex; align-items: center; gap: 0.25rem; }
  .form-inmueble .nearby-metros-input { width: 4rem; padding: 0.5rem 0.35rem; border: 1px solid #E4E4ED; border-radius: 0.5rem; font-size: 0.875rem; }
  .form-inmueble .nearby-metros-label { font-size: 0.8125rem; color: #5c5e7a; }
  .form-inmueble .btn-remove-nearby {
    padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #E4E4ED; background: #fff; color: #5c5e7a;
    font-weight: 600; cursor: pointer; font-size: 1rem; line-height: 1;
  }
  .form-inmueble .btn-remove-nearby:hover { background: #FAF6F0; color: #6F4E37; border-color: #c8c8d8; }
  .form-inmueble .btn-add-nearby {
    margin-top: 0.5rem; padding: 0.5rem 1rem; border-radius: 0.5rem; border: 1px solid #E4E4ED;
    background: #FAF6F0; color: #6F4E37; font-weight: 600; font-size: 0.875rem; cursor: pointer;
  }
  .form-inmueble .btn-add-nearby:hover { background: rgba(111,78,55,.08); border-color: #c8c8d8; }
  .form-inmueble .thumbs { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem; }
  .form-inmueble .thumbs img { width: 70px; height: 50px; object-fit: cover; border-radius: 0.375rem; border: 1px solid #E4E4ED; }
  .form-inmueble .form-actions {
    display: flex; flex-wrap: wrap; gap: 0.75rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #E4E4ED;
  }
  .form-inmueble .form-actions .btn-primary { background: #6F4E37; color: #fff; border: none; padding: 0.625rem 1.25rem; border-radius: 0.5rem; font-weight: 600; font-size: 0.9375rem; }
  .form-inmueble .form-actions .btn-primary:hover { background: #4A3728; color: #fff; }
  .form-inmueble .form-actions .btn-outline { background: #fff; color: #5c5e7a; border: 1px solid #E4E4ED; padding: 0.625rem 1.25rem; border-radius: 0.5rem; font-weight: 600; font-size: 0.9375rem; text-decoration: none; }
  .form-inmueble .form-actions .btn-outline:hover { background: #FAF6F0; color: #6F4E37; }
  .form-inmueble .cp-block { max-width: 10rem; }
  .form-inmueble .rules-suggested { display: flex; flex-wrap: wrap; gap: 0.35rem; margin-bottom: 1rem; }
  .form-inmueble .rules-suggested-btn {
    padding: 0.35rem 0.65rem; border-radius: 9999px; border: 1px solid #E4E4ED; background: #FAF6F0;
    color: #5c5e7a; font-size: 0.8125rem; cursor: pointer; white-space: nowrap;
  }
  .form-inmueble .rules-suggested-btn:hover { background: rgba(111,78,55,.08); border-color: #c8c8d8; color: #6F4E37; }
  .form-inmueble .rules-entries { display: flex; flex-direction: column; gap: 0.5rem; }
  .form-inmueble .rules-row { display: flex; gap: 0.5rem; align-items: center; }
  .form-inmueble .rules-row .rules-input { flex: 1; min-width: 0; }
  .form-inmueble .btn-remove-rule {
    padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #E4E4ED; background: #fff; color: #5c5e7a;
    font-size: 0.8125rem; font-weight: 500; cursor: pointer; flex-shrink: 0;
  }
  .form-inmueble .btn-remove-rule:hover { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
</style>
@endpush

@section('content')
@php
  $forOwner = isset($forOwner) && $forOwner;
  $formAction = $forOwner
    ? ($apartment->exists ? route('owner.apartments.update', $apartment) : route('owner.apartments.store'))
    : ($apartment->exists ? route('admin.apartments.update', $apartment) : route('admin.apartments.store'));
  $cancelUrl = $forOwner ? route('owner.dashboard') : route('admin.apartments.index');
@endphp

<div class="form-inmueble">
  <nav class="form-inmueble__breadcrumb" aria-label="Navegación">
    <a href="{{ $forOwner ? route('owner.dashboard') : route('admin.index') }}">Inicio</a>
    <span class="form-inmueble__breadcrumb-sep">/</span>
    <a href="{{ $cancelUrl }}">Inmuebles</a>
    <span class="form-inmueble__breadcrumb-sep">/</span>
    <span>{{ $apartment->exists ? 'Editar' : 'Alta' }}</span>
  </nav>
  <h1 class="form-inmueble__title">{{ $apartment->exists ? 'Editar inmueble' : 'Alta de inmueble' }}</h1>

  <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" novalidate id="apartment-form">
    @csrf
    @if($apartment->exists) @method('PUT') @endif
    @if($forOwner)
      <input type="hidden" name="owner_id" value="{{ auth()->user()->owner_id }}">
    @endif

    {{-- Datos básicos --}}
    <section class="form-section">
      <div class="form-section__header">
        <span class="form-section__icon" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </span>
        <div>
          <h2 class="form-section__title">Datos básicos</h2>
          <p class="form-section__desc">Título, dueño, dirección y renta del inmueble.</p>
        </div>
      </div>
      <div class="form-row form-row--2">
        <div class="form-group">
          <label class="form-label required">Título</label>
          <input name="title" type="text" class="form-control-custom @error('title') is-invalid @enderror" value="{{ old('title', $apartment->title) }}" required minlength="5" maxlength="120" placeholder="Ej. Departamento céntrico 2 recámaras">
          @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        @if(!$forOwner)
        <div class="form-group">
          <label class="form-label required">Dueño</label>
          <select name="owner_id" class="form-control-custom @error('owner_id') is-invalid @enderror" required>
            <option value="">Selecciona dueño</option>
            @foreach($owners as $o)
              <option value="{{ $o->id }}" @selected(old('owner_id', $apartment->owner_id) == $o->id)>{{ $o->name }}</option>
            @endforeach
          </select>
          @error('owner_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        @endif
      </div>
      <div class="form-row form-row--2">
        <div class="form-group">
          <label class="form-label required">Dirección</label>
          <input name="address" type="text" class="form-control-custom @error('address') is-invalid @enderror" value="{{ old('address', $apartment->address) }}" required maxlength="200" placeholder="Calle, número, colonia">
          @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label required">Renta mensual (MXN)</label>
          <input type="number" step="0.01" min="1000" max="200000" name="monthly_rent" class="form-control-custom @error('monthly_rent') is-invalid @enderror" value="{{ old('monthly_rent', $apartment->monthly_rent) }}" required>
          @error('monthly_rent')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>
    </section>

    {{-- Ubicación del inmueble (estado, ciudad, municipio, localidad) --}}
    <section class="form-section">
      <div class="form-section__header">
        <span class="form-section__icon" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </span>
        <div>
          <h2 class="form-section__title">Ubicación del inmueble</h2>
          <p class="form-section__desc">Estado, ciudad, municipio y localidad o colonia del alojamiento.</p>
        </div>
      </div>
      <div class="form-row form-row--2">
        <div class="form-group">
          <label class="form-label">Estado (entidad federativa)</label>
          <input name="state" type="text" id="field-state" class="form-control-custom @error('state') is-invalid @enderror" value="{{ old('state', $apartment->state) }}" maxlength="100" placeholder="Ej. Yucatán">
          @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Ciudad</label>
          <input name="city" type="text" id="field-city" class="form-control-custom @error('city') is-invalid @enderror" value="{{ old('city', $apartment->city) }}" maxlength="100" placeholder="Ej. Mérida">
          @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Municipio</label>
          <input name="municipality" type="text" id="field-municipality" class="form-control-custom @error('municipality') is-invalid @enderror" value="{{ old('municipality', $apartment->municipality) }}" maxlength="100" placeholder="Ej. Mérida">
          @error('municipality')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Localidad / Colonia</label>
          <input name="locality" type="text" id="field-locality" class="form-control-custom @error('locality') is-invalid @enderror" value="{{ old('locality', $apartment->locality) }}" maxlength="150" placeholder="Colonia o zona">
          @error('locality')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>
    </section>

    {{-- Descripción y cercanías --}}
    <section class="form-section">
      <div class="form-section__header">
        <span class="form-section__icon" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
        </span>
        <div>
          <h2 class="form-section__title">Descripción y cercanías</h2>
          <p class="form-section__desc">Describe el inmueble y lugares cercanos de interés.</p>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label required">Descripción del inmueble</label>
        <textarea name="description" rows="4" class="form-control-custom @error('description') is-invalid @enderror" required minlength="30" maxlength="3000" placeholder="Describe el espacio, distribución, ambiente...">{{ old('description', $apartment->description) }}</textarea>
        <p class="form-help">Mínimo 30 caracteres. Incluye lo que hace especial el espacio.</p>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">Cercanías</label>
        <p class="form-help" style="margin-bottom: 0.5rem;">Lugares cercanos (universidad, hospital, transporte, etc.). Puedes escribirlos a mano o detectarlos desde el mapa con distancia en metros.</p>
        <div id="nearby-list" class="nearby-entries">
          @php
            $tipos = old('nearby_tipo', []);
            $nombres = old('nearby_nombre', []);
            $metrosArr = old('nearby_metros', []);
            if (empty($tipos) && !empty($apartment->nearby) && is_array($apartment->nearby)) {
              $tipos = array_column($apartment->nearby, 'tipo');
              $nombres = array_column($apartment->nearby, 'nombre');
              $metrosArr = array_map(function ($n) { return $n['metros'] ?? ''; }, $apartment->nearby);
            }
            if (empty($tipos)) { $tipos = ['']; $nombres = ['']; $metrosArr = ['']; }
          @endphp
          @foreach($tipos as $idx => $tipo)
            <div class="nearby-row">
              <input type="text" name="nearby_tipo[]" value="{{ $tipo }}" placeholder="Ej. Universidad, Hospital" maxlength="80">
              <input type="text" name="nearby_nombre[]" value="{{ $nombres[$idx] ?? '' }}" placeholder="Nombre del lugar" maxlength="120">
              <span class="nearby-row-meta">
                <input type="number" name="nearby_metros[]" value="{{ $metrosArr[$idx] ?? '' }}" placeholder="m" min="0" max="50000" class="nearby-metros-input" title="Distancia en metros">
                <span class="nearby-metros-label">m</span>
              </span>
              <button type="button" class="btn-remove-nearby" aria-label="Quitar">−</button>
            </div>
          @endforeach
        </div>
        <button type="button" id="btn-add-nearby" class="btn-add-nearby">+ Agregar cercanía</button>
      </div>
    </section>

    {{-- Mapa --}}
    <section class="form-section">
      <div class="form-section__header">
        <span class="form-section__icon" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
        </span>
        <div>
          <h2 class="form-section__title">Ubicación en el mapa</h2>
          <p class="form-section__desc">Usa tu ubicación actual, haz clic en el mapa o busca lugares cercanos con distancia en metros.</p>
        </div>
      </div>
      <div class="map-toolbar">
        <button type="button" id="btn-my-location" class="map-toolbar-btn">
          <span class="map-toolbar-btn-icon">📍</span> Usar mi ubicación actual
        </button>
        <button type="button" id="btn-detect-nearby" class="map-toolbar-btn" disabled title="Marca un punto en el mapa primero">
          <span class="map-toolbar-btn-icon">🔍</span> Detectar cercanías (radio 1.5 km)
        </button>
      </div>
      <div id="map" class="map-wrap"></div>
      <div id="map-info" class="map-info">
        <span id="map-coords" class="map-coords">Coordenadas: —</span>
        <span id="map-address" class="map-address"></span>
      </div>
      <div id="map-nearby-panel" class="map-nearby-panel" style="display: none;">
        <h3 class="map-nearby-panel-title">Lugares cercanos detectados</h3>
        <p class="form-help">Haz clic en «Añadir» para agregar a la lista de cercanías con la distancia.</p>
        <ul id="map-nearby-list" class="map-nearby-list"></ul>
      </div>
      <input type="hidden" name="lat" id="lat" value="{{ old('lat', $apartment->lat) }}">
      <input type="hidden" name="lng" id="lng" value="{{ old('lng', $apartment->lng) }}">
      @error('lat')<div class="invalid-feedback">{{ $message }}</div>@enderror
      @error('lng')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </section>

    {{-- Disponibilidad y capacidad --}}
    <section class="form-section">
      <div class="form-section__header">
        <span class="form-section__icon" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </span>
        <div>
          <h2 class="form-section__title">Disponibilidad y capacidad</h2>
          <p class="form-section__desc">Fecha disponible, máximo de personas y si está amueblado.</p>
        </div>
      </div>
      <div class="form-row form-row--3">
        <div class="form-group">
          <label class="form-label required">Disponible desde</label>
          <input type="date" name="available_from" class="form-control-custom @error('available_from') is-invalid @enderror" value="{{ old('available_from', optional($apartment->available_from)->format('Y-m-d')) }}" required>
          @error('available_from')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label required">Máx. personas</label>
          <input type="number" name="max_people" min="1" max="50" class="form-control-custom @error('max_people') is-invalid @enderror" value="{{ old('max_people', $apartment->max_people ?? 1) }}" required>
          @error('max_people')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label required">¿Amueblado?</label>
          <div class="check-row" style="margin-top: 0.5rem;">
            <label><input type="radio" name="is_furnished" value="1" @checked(old('is_furnished', $apartment->is_furnished) == 1 || old('is_furnished', $apartment->is_furnished) === true)> Sí</label>
            <label><input type="radio" name="is_furnished" value="0" @checked(old('is_furnished', $apartment->is_furnished) == 0 || old('is_furnished', $apartment->is_furnished) === false)> No</label>
          </div>
          @error('is_furnished')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>
    </section>

    {{-- Servicios --}}
    <section class="form-section">
      <div class="form-section__header">
        <span class="form-section__icon" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
        </span>
        <div>
          <h2 class="form-section__title">Servicios que ofrece el inmueble</h2>
          <p class="form-section__desc">Marca las amenidades incluidas.</p>
        </div>
      </div>
      <div class="amenities-grid">
        <label><input type="checkbox" name="has_ac" value="1" @checked(old('has_ac', $apartment->has_ac ?? false))> Aire acondicionado</label>
        <label><input type="checkbox" name="has_tv" value="1" @checked(old('has_tv', $apartment->has_tv ?? false))> TV</label>
        <label><input type="checkbox" name="has_wifi" value="1" @checked(old('has_wifi', $apartment->has_wifi ?? false))> WiFi</label>
        <label><input type="checkbox" name="has_kitchen" value="1" @checked(old('has_kitchen', $apartment->has_kitchen ?? false))> Cocina</label>
        <label><input type="checkbox" name="has_parking" value="1" @checked(old('has_parking', $apartment->has_parking ?? false))> Estacionamiento</label>
        <label><input type="checkbox" name="has_laundry" value="1" @checked(old('has_laundry', $apartment->has_laundry ?? false))> Lavadora</label>
        <label><input type="checkbox" name="has_heating" value="1" @checked(old('has_heating', $apartment->has_heating ?? false))> Calefacción</label>
        <label><input type="checkbox" name="has_balcony" value="1" @checked(old('has_balcony', $apartment->has_balcony ?? false))> Balcón</label>
      </div>
    </section>

    @if(!$forOwner)
    <section class="form-section">
      <div class="form-section__header">
        <span class="form-section__icon" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        </span>
        <div>
          <h2 class="form-section__title">Estado de publicación</h2>
          <p class="form-section__desc">Visible o no en el listado público.</p>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label required">Estado</label>
        <select name="status" class="form-control-custom @error('status') is-invalid @enderror" required>
          @php $st = old('status', $apartment->status ?? 'activo'); @endphp
          <option value="activo" @selected($st == 'activo')>Activo</option>
          <option value="inactivo" @selected($st == 'inactivo')>Inactivo</option>
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    </section>
    @else
      <input type="hidden" name="status" value="activo">
    @endif

    {{-- Reglas y políticas (unificado: mín. 3, máx. 10) --}}
    <section class="form-section">
      <div class="form-section__header">
        <span class="form-section__icon" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        </span>
        <div>
          <h2 class="form-section__title">Reglas y políticas</h2>
          <p class="form-section__desc">Normas del inmueble (mínimo 3, máximo 10). Añade las que apliquen o escribe las tuyas.</p>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label required">Reglas (mín. 3, máx. 10)</label>
        <p class="form-help" style="margin-bottom: 0.5rem;">Haz clic en una sugerencia para añadirla o escribe tu propia regla y elimina las que no apliquen.</p>
        <div class="rules-suggested">
          @php
            $suggested = [
              'No fiestas ni eventos',
              'Horario de silencio 22:00-08:00',
              'No se permiten mascotas',
              'Mascotas permitidas (consultar)',
              'No fumar en el interior',
              'Se permite fumar en zona indicada',
              'Check-out antes de las 11:00',
              'No invitados sin avisar',
              'Prohibido subarrendar',
              'Mantener limpio al salir',
            ];
          @endphp
          @foreach($suggested as $s)
            <button type="button" class="rules-suggested-btn" data-rule="{{ e($s) }}">{{ $s }}</button>
          @endforeach
        </div>
        <div id="rules-list" class="rules-entries">
          @php
            $rulesArr = old('rules', $apartment->rules ?? []);
            if (is_string($rulesArr)) {
              $rulesArr = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $rulesArr)));
            }
            $rulesArr = is_array($rulesArr) ? $rulesArr : [];
            if (empty($rulesArr)) {
              $rulesArr = ['', '', ''];
            }
          @endphp
          @foreach($rulesArr as $idx => $rule)
            <div class="rules-row">
              <input type="text" name="rules[]" value="{{ is_string($rule) ? $rule : '' }}" placeholder="Ej: No fiestas..." maxlength="200" class="form-control-custom rules-input">
              <button type="button" class="btn-remove-rule" aria-label="Eliminar regla">Eliminar</button>
            </div>
          @endforeach
        </div>
        <button type="button" id="btn-add-rule" class="btn-add-nearby">+ Añadir regla</button>
        @error('rules')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @error('rules.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label class="form-label {{ $apartment->exists ? '' : 'required' }}">Fotos del inmueble</label>
        <p class="form-help">Al menos 1 foto, hasta 10. JPG, PNG o WebP. Máx. 4 MB cada una.</p>
        <input type="file" name="photos[]" class="form-control-custom @error('photos') is-invalid @enderror @error('photos.*') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" multiple {{ $apartment->exists ? '' : 'required' }}>
        @error('photos')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @error('photos.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @if($apartment->exists && !empty($apartment->photos))
          <div class="thumbs">
            @foreach($apartment->photos as $p)
              <img src="{{ url('files/'.$p) }}" alt="">
            @endforeach
          </div>
        @endif
      </div>
    </section>

    <div class="form-actions">
      <a href="{{ $cancelUrl }}" class="btn-outline">Cancelar</a>
      <button type="submit" class="btn btn-primary">{{ $apartment->exists ? 'Guardar cambios' : 'Crear inmueble' }}</button>
    </div>
  </form>
</div>
@endsection

@push('js')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
(function() {
  var latEl = document.getElementById('lat');
  var lngEl = document.getElementById('lng');
  var initialLat = parseFloat(latEl.value) || 20.97;
  var initialLng = parseFloat(lngEl.value) || -89.62;
  var map = L.map('map').setView([initialLat, initialLng], 12);
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
  var marker = null;
  if (latEl.value && lngEl.value) {
    marker = L.marker([initialLat, initialLng]).addTo(map);
  }
  map.on('click', function(e) {
    var lat = e.latlng.lat;
    var lng = e.latlng.lng;
    if (marker) map.removeLayer(marker);
    marker = L.marker([lat, lng]).addTo(map);
    latEl.value = lat.toFixed(7);
    lngEl.value = lng.toFixed(7);
  });

  function addNearbyRow(tipo, nombre, metros) {
    var list = document.getElementById('nearby-list');
    var row = document.createElement('div');
    row.className = 'nearby-row';
    var metrosVal = (metros !== undefined && metros !== null && metros !== '') ? metros : '';
    function esc(s) { return (s || '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;'); }
    row.innerHTML = '<input type="text" name="nearby_tipo[]" placeholder="Ej. Universidad, Hospital" maxlength="80" value="' + esc(tipo) + '">' +
      '<input type="text" name="nearby_nombre[]" placeholder="Nombre del lugar" maxlength="120" value="' + esc(nombre) + '">' +
      '<span class="nearby-row-meta"><input type="number" name="nearby_metros[]" placeholder="m" min="0" max="50000" class="nearby-metros-input" title="Distancia en metros" value="' + metrosVal + '"><span class="nearby-metros-label">m</span></span>' +
      '<button type="button" class="btn-remove-nearby" aria-label="Quitar">−</button>';
    list.appendChild(row);
    row.querySelector('.btn-remove-nearby').addEventListener('click', function() {
      if (list.querySelectorAll('.nearby-row').length > 1) row.remove();
    });
  }
  document.getElementById('btn-add-nearby')?.addEventListener('click', function() {
    addNearbyRow('', '', '');
  });
  document.getElementById('nearby-list')?.querySelectorAll('.btn-remove-nearby').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var row = btn.closest('.nearby-row');
      if (document.querySelectorAll('#nearby-list .nearby-row').length > 1) row.remove();
    });
  });

  var rulesList = document.getElementById('rules-list');
  var MIN_RULES = 3;
  var MAX_RULES = 10;
  function rulesCount() { return rulesList ? rulesList.querySelectorAll('.rules-row').length : 0; }
  function addRuleRow(text) {
    if (!rulesList || rulesCount() >= MAX_RULES) return;
    var row = document.createElement('div');
    row.className = 'rules-row';
    row.innerHTML = '<input type="text" name="rules[]" value="' + (text || '').replace(/"/g, '&quot;').replace(/</g, '&lt;') + '" placeholder="Ej: No fiestas..." maxlength="200" class="form-control-custom rules-input">' +
      '<button type="button" class="btn-remove-rule" aria-label="Eliminar regla">Eliminar</button>';
    rulesList.appendChild(row);
    row.querySelector('.btn-remove-rule').addEventListener('click', function() {
      if (rulesCount() > MIN_RULES) row.remove();
    });
  }
  document.getElementById('btn-add-rule')?.addEventListener('click', function() {
    addRuleRow('');
  });
  rulesList?.querySelectorAll('.btn-remove-rule').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var row = btn.closest('.rules-row');
      if (rulesCount() > MIN_RULES) row.remove();
    });
  });
  document.querySelectorAll('.rules-suggested-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var text = btn.getAttribute('data-rule');
      if (rulesCount() < MAX_RULES) addRuleRow(text);
    });
  });

  function haversineDistance(lat1, lon1, lat2, lon2) {
    var R = 6371000;
    var dLat = (lat2 - lat1) * Math.PI / 180;
    var dLon = (lon2 - lon1) * Math.PI / 180;
    var a = Math.sin(dLat/2)*Math.sin(dLat/2) + Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLon/2)*Math.sin(dLon/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return Math.round(R * c);
  }
  function updateMapInfo(lat, lng) {
    var coordsEl = document.getElementById('map-coords');
    var addressEl = document.getElementById('map-address');
    if (coordsEl) coordsEl.textContent = 'Coordenadas: ' + parseFloat(lat).toFixed(5) + ', ' + parseFloat(lng).toFixed(5);
    if (addressEl) {
      addressEl.textContent = 'Obteniendo dirección...';
      fetch('https://nominatim.openstreetmap.org/reverse?lat=' + lat + '&lon=' + lng + '&format=json', {
        headers: { 'Accept': 'application/json', 'User-Agent': 'RoomHub/1.0 (contacto@roomhub.local)' }
      }).then(function(r) { return r.json(); })
        .then(function(data) {
          addressEl.textContent = data.display_name || '';
        })
        .catch(function() { addressEl.textContent = ''; });
    }
    var btnDetect = document.getElementById('btn-detect-nearby');
    if (btnDetect) btnDetect.disabled = false;
  }
  document.getElementById('btn-my-location')?.addEventListener('click', function() {
    var btn = this;
    if (!navigator.geolocation) {
      alert('Tu navegador no soporta geolocalización.');
      return;
    }
    btn.disabled = true;
    btn.textContent = 'Obteniendo ubicación...';
    navigator.geolocation.getCurrentPosition(
      function(pos) {
        var lat = pos.coords.latitude;
        var lng = pos.coords.longitude;
        map.setView([lat, lng], 16);
        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng]).addTo(map);
        latEl.value = lat.toFixed(7);
        lngEl.value = lng.toFixed(7);
        updateMapInfo(lat, lng);
        btn.disabled = false;
        btn.innerHTML = '<span class="map-toolbar-btn-icon">📍</span> Usar mi ubicación actual';
      },
      function() {
        alert('No se pudo obtener tu ubicación. Comprueba permisos o marca el punto en el mapa.');
        btn.disabled = false;
        btn.innerHTML = '<span class="map-toolbar-btn-icon">📍</span> Usar mi ubicación actual';
      }
    );
  });
  map.on('click', function(e) {
    var lat = e.latlng.lat;
    var lng = e.latlng.lng;
    if (marker) map.removeLayer(marker);
    marker = L.marker([lat, lng]).addTo(map);
    latEl.value = lat.toFixed(7);
    lngEl.value = lng.toFixed(7);
    updateMapInfo(lat, lng);
  });
  if (latEl.value && lngEl.value) {
    updateMapInfo(parseFloat(latEl.value), parseFloat(lngEl.value));
  }

  document.getElementById('btn-detect-nearby')?.addEventListener('click', function() {
    var lat = parseFloat(latEl.value);
    var lng = parseFloat(lngEl.value);
    if (isNaN(lat) || isNaN(lng)) {
      alert('Marca primero un punto en el mapa o usa «Usar mi ubicación actual».');
      return;
    }
    var btn = this;
    var panel = document.getElementById('map-nearby-panel');
    var listEl = document.getElementById('map-nearby-list');
    if (!panel || !listEl) return;
    btn.disabled = true;
    listEl.innerHTML = '<li class="form-help">Buscando lugares cercanos...</li>';
    panel.style.display = 'block';
    var radius = 1500;
    var query = '[out:json][timeout:25];(node["amenity"~"university|college|school|hospital|clinic|pharmacy|bus_station|restaurant|bank|atm"](around:' + radius + ',' + lat + ',' + lng + '););out body;';
    fetch('https://overpass-api.de/api/interpreter', {
      method: 'POST',
      body: 'data=' + encodeURIComponent(query)
    }).then(function(r) { return r.json(); })
      .then(function(data) {
        listEl.innerHTML = '';
        var elements = data.elements || [];
        var seen = {};
        var amenityLabels = { university: 'Universidad', college: 'Universidad', school: 'Escuela', hospital: 'Hospital', clinic: 'Clínica', pharmacy: 'Farmacia', bus_station: 'Transporte', restaurant: 'Restaurante', bank: 'Banco', atm: 'Cajero' };
        elements.forEach(function(el) {
          var elat = el.lat;
          var elng = el.lon;
          if (el.center) { elat = el.center.lat; elng = el.center.lon; }
          var dist = haversineDistance(lat, lng, elat, elng);
          var name = (el.tags && el.tags.name) ? el.tags.name : 'Sin nombre';
          var amenity = (el.tags && el.tags.amenity) ? el.tags.amenity : '';
          var key = name + '|' + amenity + '|' + dist;
          if (seen[key]) return;
          seen[key] = true;
          var tipo = amenityLabels[amenity] || amenity || 'Lugar';
          if (tipo === amenity && amenity.length > 1) tipo = amenity.charAt(0).toUpperCase() + amenity.slice(1);
          var li = document.createElement('li');
          li.innerHTML = '<span><strong>' + tipo + ':</strong> ' + name.replace(/</g, '&lt;') + ' <span class="distance">(' + dist + ' m)</span></span><button type="button" class="add-nearby-btn">Añadir</button>';
          li.querySelector('.add-nearby-btn').addEventListener('click', function() {
            addNearbyRow(tipo, name, dist);
          });
          listEl.appendChild(li);
        });
        if (elements.length === 0) listEl.innerHTML = '<li class="form-help">No se encontraron lugares en un radio de 1.5 km. Prueba en otra zona.</li>';
        btn.disabled = false;
      })
      .catch(function() {
        listEl.innerHTML = '<li class="form-help">Error al buscar. Intenta de nuevo.</li>';
        btn.disabled = false;
      });
  });

})();
</script>
@endpush
