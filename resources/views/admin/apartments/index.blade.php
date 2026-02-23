@extends('layouts.admin')
@section('title', 'Inmuebles')

@push('css')
<style>
  .apt-list-page { padding: 0; }
  .apt-list-page__breadcrumb { font-size: 0.8125rem; color: #5c5e7a; margin-bottom: 0.75rem; }
  .apt-list-page__breadcrumb a { color: #6F4E37; text-decoration: none; }
  .apt-list-page__breadcrumb a:hover { text-decoration: underline; color: #4A3728; }
  .apt-list-page__breadcrumb-sep { margin: 0 0.35rem; opacity: 0.7; }
  .apt-list-page__header { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; }
  .apt-list-page__title { font-size: 1.5rem; font-weight: 700; color: #6F4E37; margin: 0; letter-spacing: -0.02em; }
  .apt-list-page__actions { display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center; }
  .apt-list-page__filter-card {
    background: #F8F8F9; border: 1px solid #E4E4ED; border-radius: 0.75rem; padding: 1.25rem; margin-bottom: 1.25rem;
  }
  .apt-list-page__filter-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem; }
  .apt-list-page__filter-title { font-size: 0.8125rem; font-weight: 600; color: #5c5e7a; margin: 0; text-transform: uppercase; letter-spacing: 0.03em; }
  .apt-list-page__filter-clear { font-size: 0.8125rem; color: #6F4E37; text-decoration: none; font-weight: 500; }
  .apt-list-page__filter-clear:hover { text-decoration: underline; color: #4A3728; }
  .apt-list-page__filter-form { margin: 0; }
  .apt-list-page__filter-row { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 0.75rem 1rem; align-items: end; }
  @media (min-width: 900px) {
    .apt-list-page__filter-row { grid-template-columns: 1.2fr 0.9fr 1fr 1fr 1fr 1fr 1fr auto; }
  }
  .apt-list-page__filter-field { min-width: 0; }
  .apt-list-page__filter-label { display: block; font-size: 0.6875rem; font-weight: 600; color: #5c5e7a; margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.03em; }
  .apt-list-page__filter-row .form-control, .apt-list-page__filter-row .form-select {
    width: 100%; border-color: #E4E4ED; border-radius: 0.5rem; font-size: 0.875rem; padding: 0.5rem 0.65rem;
  }
  .apt-list-page__filter-row .form-control:focus, .apt-list-page__filter-row .form-select:focus {
    border-color: #6F4E37; box-shadow: 0 0 0 2px rgba(111,78,55,.15);
  }
  .apt-list-page__filter-actions { display: flex; align-items: flex-end; }
  .apt-list-page__filter-actions .btn { padding: 0.5rem 1rem; }
  .apt-list-page__table-wrap { overflow-x: auto; border: 1px solid #E4E4ED; border-radius: 0.75rem; background: #fff; }
  .apt-list-page .apt-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; color: #6F4E37; }
  .apt-list-page .apt-table th {
    text-align: left; padding: 0.75rem 1rem; background: #F8F8F9; border-bottom: 1px solid #E4E4ED;
    font-weight: 600; color: #5c5e7a; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.03em;
  }
  .apt-list-page .apt-table td { padding: 0.75rem 1rem; border-bottom: 1px solid #f0f0f5; vertical-align: middle; }
  .apt-list-page .apt-table tbody tr:hover { background: #F8F8F9; }
  .apt-list-page .apt-table tbody tr:last-child td { border-bottom: none; }
  .apt-list-page .apt-table .apt-thumb {
    width: 56px; height: 42px; object-fit: cover; border-radius: 0.375rem; border: 1px solid #E4E4ED;
  }
  .apt-list-page .apt-table .apt-thumb-wrap { display: flex; gap: 0.25rem; flex-wrap: wrap; }
  .apt-list-page .apt-table .apt-title { font-weight: 600; color: #6F4E37; max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .apt-list-page .apt-table .apt-address { font-size: 0.8125rem; color: #5c5e7a; max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .apt-list-page .apt-table .apt-location { font-size: 0.8125rem; color: #5c5e7a; white-space: nowrap; }
  .apt-list-page .apt-table .apt-rent { font-weight: 600; color: #6F4E37; white-space: nowrap; }
  .apt-list-page .apt-table .apt-badge { display: inline-block; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
  .apt-list-page .apt-table .apt-badge--si { background: rgba(82,121,111,.15); color: #52796F; }
  .apt-list-page .apt-table .apt-badge--no { background: #f0f0f5; color: #5c5e7a; }
  .apt-list-page .apt-table .apt-badge--activo { background: rgba(111,78,55,.15); color: #6F4E37; }
  .apt-list-page .apt-table .apt-badge--inactivo { background: rgba(245,158,11,.2); color: #b45309; }
  .apt-list-page .apt-table .apt-actions { display: flex; gap: 0.35rem; flex-wrap: wrap; }
  .apt-list-page .apt-table .apt-actions .btn { font-size: 0.8125rem; padding: 0.35rem 0.6rem; }
  .apt-list-page .pagination-wrap { margin-top: 1.25rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; }
  .apt-list-page .pagination-info { font-size: 0.8125rem; color: #5c5e7a; }
  .apt-list-page .pagination { display: flex; flex-wrap: wrap; gap: 0.25rem; list-style: none; padding: 0; margin: 0; }
  .apt-list-page .pagination .page-item .page-link {
    display: inline-block; padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #E4E4ED;
    background: #fff; color: #6F4E37; font-size: 0.875rem; font-weight: 500; text-decoration: none;
  }
  .apt-list-page .pagination .page-item .page-link:hover { background: #F8F8F9; border-color: #c8c8d8; color: #4A3728; }
  .apt-list-page .pagination .page-item.active .page-link { background: #6F4E37; border-color: #6F4E37; color: #fff; }
  .apt-list-page .pagination .page-item.disabled .page-link { opacity: 0.6; pointer-events: none; }
  .apt-list-page .apt-empty { text-align: center; padding: 3rem 1.5rem; color: #5c5e7a; }
  .apt-list-page .apt-empty__icon { font-size: 2.5rem; margin-bottom: 0.5rem; opacity: 0.5; }
</style>
@endpush

@section('content')
<div class="apt-list-page">
  <nav class="apt-list-page__breadcrumb" aria-label="Navegación">
    <a href="{{ route('admin.index') }}">Inicio</a>
    <span class="apt-list-page__breadcrumb-sep">/</span>
    <span>Inmuebles</span>
  </nav>

  <header class="apt-list-page__header">
    <h1 class="apt-list-page__title">Inmuebles</h1>
    <div class="apt-list-page__actions">
      <a href="{{ route('admin.apartments.create') }}" class="btn btn-primary">+ Alta de inmueble</a>
      <a href="{{ route('admin.index') }}" class="btn btn-admin-outline text-decoration-none">← Regresar</a>
      <a href="{{ route('admin.reports.apartments', request()->query()) }}" class="btn btn-admin-outline">Reporte PDF</a>
    </div>
  </header>

  <div class="apt-list-page__filter-card">
    <div class="apt-list-page__filter-header">
      <span class="apt-list-page__filter-title">Filtrar y buscar</span>
      @if(request()->hasAny(['q','postal_code','state','city','municipality','owner_id','status']))
        <a href="{{ route('admin.apartments.index') }}" class="apt-list-page__filter-clear">Limpiar filtros</a>
      @endif
    </div>
    <form method="GET" action="{{ route('admin.apartments.index') }}" class="apt-list-page__filter-form">
      <div class="apt-list-page__filter-row">
        <div class="apt-list-page__filter-field">
          <label class="apt-list-page__filter-label">Título o dirección</label>
          <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Ej: Penthouse, Mérida">
        </div>
        <div class="apt-list-page__filter-field">
          <label class="apt-list-page__filter-label">Código postal</label>
          <input name="postal_code" value="{{ request('postal_code') }}" class="form-control" placeholder="Ej: 97000" maxlength="10" inputmode="numeric">
        </div>
        <div class="apt-list-page__filter-field">
          <label class="apt-list-page__filter-label">Estado (entidad)</label>
          <input type="text" name="state" value="{{ request('state') }}" class="form-control" placeholder="Ej: Yucatán" list="datalist-states">
          <datalist id="datalist-states">
            @foreach($states as $s) <option value="{{ $s }}"> @endforeach
          </datalist>
        </div>
        <div class="apt-list-page__filter-field">
          <label class="apt-list-page__filter-label">Ciudad</label>
          <input type="text" name="city" value="{{ request('city') }}" class="form-control" placeholder="Ej: Mérida" list="datalist-cities">
          <datalist id="datalist-cities">
            @foreach($cities as $c) <option value="{{ $c }}"> @endforeach
          </datalist>
        </div>
        <div class="apt-list-page__filter-field">
          <label class="apt-list-page__filter-label">Municipio</label>
          <input type="text" name="municipality" value="{{ request('municipality') }}" class="form-control" placeholder="Ej: Mérida" list="datalist-municipalities">
          <datalist id="datalist-municipalities">
            @foreach($municipalities as $m) <option value="{{ $m }}"> @endforeach
          </datalist>
        </div>
        <div class="apt-list-page__filter-field">
          <label class="apt-list-page__filter-label">Dueño</label>
          <select name="owner_id" class="form-select">
            <option value="">Todos</option>
            @foreach($owners as $o)
              <option value="{{ $o->id }}" @selected((string)request('owner_id') === (string)$o->id)>{{ $o->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="apt-list-page__filter-field">
          <label class="apt-list-page__filter-label">Estado publ.</label>
          <select name="status" class="form-select">
            <option value="">Todos</option>
            <option value="activo" @selected(request('status') === 'activo')>Activo</option>
            <option value="inactivo" @selected(request('status') === 'inactivo')>Inactivo</option>
          </select>
        </div>
        <div class="apt-list-page__filter-actions">
          <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
      </div>
    </form>
  </div>

  <div class="apt-list-page__table-wrap">
    @if($apartments->isEmpty())
      <div class="apt-empty">
        <div class="apt-empty__icon">🏠</div>
        <p class="mb-0">No hay inmuebles que coincidan con los filtros.</p>
        <a href="{{ route('admin.apartments.create') }}" class="btn btn-primary mt-3">Crear el primero</a>
      </div>
    @else
      <table class="apt-table">
        <thead>
          <tr>
            <th>Fotos</th>
            <th>Título / Dirección</th>
            <th>Ubicación</th>
            <th>Dueño</th>
            <th>Renta</th>
            <th>Disponible</th>
            <th>Amueblado</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($apartments as $a)
          <tr>
            <td>
              <div class="apt-thumb-wrap">
                @forelse(collect($a->photos)->take(2) as $p)
                  <img class="apt-thumb" src="{{ url('files/'.$p) }}" alt="" loading="lazy">
                @empty
                  <span class="apt-thumb" style="display:inline-flex;align-items:center;justify-content:center;background:#f0f0f5;color:#5c5e7a;font-size:0.75rem;">Sin foto</span>
                @endforelse
              </div>
            </td>
            <td>
              <div class="apt-title" title="{{ $a->title }}">{{ $a->title }}</div>
              <div class="apt-address" title="{{ $a->address }}">{{ $a->address ?: '—' }}</div>
            </td>
            <td class="apt-location">{{ $a->state ?: '—' }} / {{ $a->city ?: '—' }}</td>
            <td>{{ $a->owner->name }}</td>
            <td class="apt-rent">${{ number_format($a->monthly_rent, 0) }}</td>
            <td>{{ $a->available_from->format('d/m/Y') }}</td>
            <td>
              <span class="apt-badge apt-badge--{{ $a->is_furnished ? 'si' : 'no' }}">{{ $a->is_furnished ? 'Sí' : 'No' }}</span>
            </td>
            <td>
              <span class="apt-badge apt-badge--{{ $a->status }}">{{ ucfirst($a->status) }}</span>
            </td>
            <td>
              <div class="apt-actions">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.apartments.edit', $a) }}">Editar</a>
                <form action="{{ route('admin.apartments.destroy', $a) }}" method="POST" class="d-inline" data-confirm="¿Eliminar el inmueble #{{ $a->id }}? Esta acción no se puede deshacer." data-confirm-icon="warning">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger">Borrar</button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>

  @if(!$apartments->isEmpty())
    <div class="pagination-wrap">
      <div class="pagination-info">
        Mostrando {{ $apartments->firstItem() }}–{{ $apartments->lastItem() }} de {{ $apartments->total() }} inmuebles
      </div>
      @if($apartments->hasPages())
        <nav aria-label="Paginación de inmuebles">
          <ul class="pagination">
            <li class="page-item {{ $apartments->onFirstPage() ? 'disabled' : '' }}">
              <a class="page-link" href="{{ $apartments->previousPageUrl() }}" aria-label="Anterior">← Anterior</a>
            </li>
            @foreach($apartments->getUrlRange(max(1, $apartments->currentPage() - 2), min($apartments->lastPage(), $apartments->currentPage() + 2)) as $page => $url)
              <li class="page-item {{ $page == $apartments->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
              </li>
            @endforeach
            <li class="page-item {{ $apartments->hasMorePages() ? '' : 'disabled' }}">
              <a class="page-link" href="{{ $apartments->nextPageUrl() }}" aria-label="Siguiente">Siguiente →</a>
            </li>
          </ul>
        </nav>
      @endif
    </div>
  @endif
</div>
@endsection
