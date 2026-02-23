@extends('layouts.admin')
@section('title', 'Panel')

@section('content')
<div class="admin-dashboard">
  {{-- Breadcrumb + Título --}}
  <nav class="admin-dashboard__breadcrumb mb-2" aria-label="Navegación">
    <a href="{{ route('dashboard') }}">Inicio</a>
    <span class="admin-dashboard__breadcrumb-sep" aria-hidden="true">/</span>
    <span>Panel</span>
  </nav>
  <header class="admin-dashboard__header mb-4">
    <div class="admin-dashboard__header-inner">
      <span class="admin-dashboard__header-icon" aria-hidden="true">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
      </span>
      <div>
        <h1 class="admin-dashboard__title">Panel de administración</h1>
        <p class="admin-dashboard__subtitle">Resumen y acceso a inmuebles, dueños, clientes, finanzas y verificaciones.</p>
      </div>
    </div>
  </header>

  @if(($stats['verifications_pending'] ?? 0) > 0)
    <div class="admin-dashboard__alert mb-4" role="alert">
      <span class="admin-dashboard__alert-icon" aria-hidden="true">!</span>
      <div class="admin-dashboard__alert-body">
        <strong>Verificaciones pendientes:</strong> {{ $stats['verifications_pending'] }} solicitud(es) de anfitriones en espera de revisión.
        <a href="{{ route('admin.verifications.index') }}" class="admin-dashboard__alert-link">Revisar ahora</a>
      </div>
    </div>
  @endif

  {{-- KPIs --}}
  <section class="admin-dashboard__kpis mb-4" aria-labelledby="resumen-heading">
    <h2 id="resumen-heading" class="admin-dashboard__section-title">Resumen</h2>
    <div class="row g-3 g-lg-4">
      <div class="col-6 col-lg-3">
        <a href="{{ route('admin.apartments.index') }}" class="admin-kpi admin-kpi--link">
          <div class="admin-kpi__icon admin-kpi__icon--primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="22" height="22"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
          </div>
          <div class="admin-kpi__label">Inmuebles</div>
          <div class="admin-kpi__value">{{ $stats['apartments'] ?? 0 }}</div>
        </a>
      </div>
      <div class="col-6 col-lg-3">
        <a href="{{ route('admin.owners.index') }}" class="admin-kpi admin-kpi--link">
          <div class="admin-kpi__icon admin-kpi__icon--primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="22" height="22"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          </div>
          <div class="admin-kpi__label">Dueños</div>
          <div class="admin-kpi__value">{{ $stats['owners'] ?? 0 }}</div>
        </a>
      </div>
      <div class="col-6 col-lg-3">
        <a href="{{ route('admin.clients.index') }}" class="admin-kpi admin-kpi--link">
          <div class="admin-kpi__icon admin-kpi__icon--primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="22" height="22"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          </div>
          <div class="admin-kpi__label">Clientes</div>
          <div class="admin-kpi__value">{{ $stats['clients'] ?? 0 }}</div>
        </a>
      </div>
      <div class="col-6 col-lg-3">
        <a href="{{ route('admin.finances.index') }}" class="admin-kpi admin-kpi--link admin-kpi--highlight">
          <div class="admin-kpi__icon admin-kpi__icon--accent">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="22" height="22"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <div class="admin-kpi__label">Ingresos acumulados</div>
          <div class="admin-kpi__value admin-kpi__value--currency">${{ number_format($stats['revenue_total'] ?? 0, 0) }} <span class="admin-kpi__currency">MXN</span></div>
          <div class="admin-kpi__meta">{{ $stats['revenue_count'] ?? 0 }} reserva{{ ($stats['revenue_count'] ?? 0) === 1 ? '' : 's' }} pagada{{ ($stats['revenue_count'] ?? 0) === 1 ? '' : 's' }}</div>
        </a>
      </div>
    </div>
  </section>

  {{-- Ocupación --}}
  <section class="admin-card admin-card--section mb-4" aria-labelledby="ocupacion-heading">
    <div class="admin-card-header admin-card-header--with-desc">
      <div class="admin-card-header__main">
        <span class="admin-card-header__icon" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </span>
        <div>
          <h2 id="ocupacion-heading" class="admin-card-header__title">Ocupación</h2>
          <p class="admin-card-header__desc">Estancias activas hoy y próximas entradas y salidas (14 días).</p>
        </div>
      </div>
    </div>
    <div class="admin-occupancy">
      <div class="admin-occupancy__col">
        <h3 class="admin-occupancy__col-title">
          <span class="admin-occupancy__col-badge admin-occupancy__col-badge--active">{{ count($occupancyActive ?? []) }}</span>
          Ocupados ahora
        </h3>
        <ul class="admin-occupancy__list">
          @forelse($occupancyActive ?? [] as $r)
            <li class="admin-occupancy__item">
              <a href="{{ route('admin.apartments.edit', $r->apartment) }}" class="admin-occupancy__link">
                <span class="admin-occupancy__item-title">{{ $r->apartment?->title ?? 'Inmueble #'.$r->apartment_id }}</span>
                <span class="admin-occupancy__item-dates">hasta {{ $r->check_out?->format('d/m/Y') }}</span>
              </a>
            </li>
          @empty
            <li class="admin-occupancy__empty">Ninguna estancia activa hoy.</li>
          @endforelse
        </ul>
      </div>
      <div class="admin-occupancy__col">
        <h3 class="admin-occupancy__col-title">
          <span class="admin-occupancy__col-badge">{{ count($upcomingCheckIns ?? []) }}</span>
          Próximos check-in
        </h3>
        <ul class="admin-occupancy__list">
          @forelse($upcomingCheckIns ?? [] as $r)
            <li class="admin-occupancy__item">
              <a href="{{ route('admin.apartments.edit', $r->apartment) }}" class="admin-occupancy__link">
                <span class="admin-occupancy__item-title">{{ $r->apartment?->title ?? 'Inmueble #'.$r->apartment_id }}</span>
                <span class="admin-occupancy__item-dates">{{ $r->check_in?->format('d/m/Y') }}</span>
              </a>
            </li>
          @empty
            <li class="admin-occupancy__empty">No hay entradas en los próximos 14 días.</li>
          @endforelse
        </ul>
      </div>
      <div class="admin-occupancy__col">
        <h3 class="admin-occupancy__col-title">
          <span class="admin-occupancy__col-badge">{{ count($upcomingCheckOuts ?? []) }}</span>
          Próximos check-out
        </h3>
        <ul class="admin-occupancy__list">
          @forelse($upcomingCheckOuts ?? [] as $r)
            <li class="admin-occupancy__item">
              <a href="{{ route('admin.apartments.edit', $r->apartment) }}" class="admin-occupancy__link">
                <span class="admin-occupancy__item-title">{{ $r->apartment?->title ?? 'Inmueble #'.$r->apartment_id }}</span>
                <span class="admin-occupancy__item-dates">{{ $r->check_out?->format('d/m/Y') }}</span>
              </a>
            </li>
          @empty
            <li class="admin-occupancy__empty">No hay salidas en los próximos 14 días.</li>
          @endforelse
        </ul>
      </div>
    </div>
  </section>

  <div class="row g-4 g-lg-5">
    <div class="col-12 col-lg-8">
      {{-- Reporte de ingresos --}}
      <section class="admin-card admin-card--section mb-4" aria-labelledby="reporte-heading">
        <div class="admin-card-header admin-card-header--with-desc">
          <div class="admin-card-header__main">
            <span class="admin-card-header__icon" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </span>
            <div>
              <h2 id="reporte-heading" class="admin-card-header__title">Reporte de ingresos</h2>
              <p class="admin-card-header__desc">Descarga PDF o Excel por rango de fechas.</p>
            </div>
          </div>
        </div>
        <div class="admin-card-body">
          <form method="GET" action="{{ route('admin.reports.revenue') }}" class="admin-revenue-form" id="form-revenue-pdf">
            <div class="row g-3 align-items-end">
              <div class="col-12 col-sm-4">
                <label class="admin-form-label">Desde</label>
                <input type="date" name="from" class="form-control form-control-sm admin-form-input" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
              </div>
              <div class="col-12 col-sm-4">
                <label class="admin-form-label">Hasta</label>
                <input type="date" name="to" class="form-control form-control-sm admin-form-input" value="{{ now()->format('Y-m-d') }}">
              </div>
              <div class="col-12 col-sm-4 d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-admin-primary btn-sm">
                  <svg xmlns="http://www.w3.org/2000/svg" class="me-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                  Descargar PDF
                </button>
                <a href="#" class="btn btn-admin-outline btn-sm" id="btn-revenue-excel">Excel</a>
              </div>
            </div>
          </form>
          <script>
            document.getElementById('btn-revenue-excel')?.addEventListener('click', function(e) {
              e.preventDefault();
              var form = document.getElementById('form-revenue-pdf');
              var from = form.querySelector('input[name="from"]').value;
              var to = form.querySelector('input[name="to"]').value;
              var base = '{{ route("admin.reports.revenue.excel") }}';
              var url = base + (base.indexOf('?') >= 0 ? '&' : '?') + 'from=' + encodeURIComponent(from) + '&to=' + encodeURIComponent(to);
              window.location.href = url;
            });
          </script>
        </div>
      </section>

      {{-- Módulos --}}
      <section class="admin-card admin-card--section" aria-labelledby="modulos-heading">
        <div class="admin-card-header admin-card-header--with-desc">
          <div class="admin-card-header__main">
            <span class="admin-card-header__icon" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            </span>
            <div>
              <h2 id="modulos-heading" class="admin-card-header__title">Módulos</h2>
              <p class="admin-card-header__desc">Acceso rápido a cada área de gestión.</p>
            </div>
          </div>
        </div>
        <div class="admin-modules admin-modules--grid">
          <a href="{{ route('admin.apartments.index') }}" class="admin-modules__item">
            <span class="admin-modules__icon admin-modules__icon--primary">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </span>
            <span class="admin-modules__label">Inmuebles</span>
            <span class="admin-modules__badge">{{ $stats['apartments'] ?? 0 }}</span>
          </a>
          <a href="{{ route('admin.owners.index') }}" class="admin-modules__item">
            <span class="admin-modules__icon admin-modules__icon--primary">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </span>
            <span class="admin-modules__label">Dueños</span>
            <span class="admin-modules__badge">{{ $stats['owners'] ?? 0 }}</span>
          </a>
          <a href="{{ route('admin.clients.index') }}" class="admin-modules__item">
            <span class="admin-modules__icon admin-modules__icon--primary">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </span>
            <span class="admin-modules__label">Clientes</span>
            <span class="admin-modules__badge">{{ $stats['clients'] ?? 0 }}</span>
          </a>
          <a href="{{ route('admin.finances.index') }}" class="admin-modules__item">
            <span class="admin-modules__icon admin-modules__icon--primary">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
            <span class="admin-modules__label">Finanzas</span>
          </a>
          <a href="{{ route('messages.index') }}" class="admin-modules__item">
            <span class="admin-modules__icon admin-modules__icon--primary">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </span>
            <span class="admin-modules__label">Mensajes</span>
          </a>
          <a href="{{ route('admin.users.index') }}" class="admin-modules__item">
            <span class="admin-modules__icon admin-modules__icon--gray">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </span>
            <span class="admin-modules__label">Usuarios (cuentas)</span>
          </a>
          <a href="{{ route('admin.verifications.index') }}" class="admin-modules__item">
            <span class="admin-modules__icon admin-modules__icon--primary">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
            <span class="admin-modules__label">Verificaciones anfitriones</span>
            @if(($stats['verifications_pending'] ?? 0) > 0)
              <span class="admin-modules__badge admin-modules__badge--warning">{{ $stats['verifications_pending'] }}</span>
            @endif
          </a>
        </div>
      </section>
    </div>

    {{-- Columna lateral --}}
    <div class="col-12 col-lg-4">
      {{-- Centro de notificaciones (alertas para admin) --}}
      <section class="admin-card admin-card--section mb-4" aria-labelledby="admin-notif-heading">
        <div class="admin-card-header admin-card-header--with-desc d-flex align-items-start justify-content-between flex-wrap gap-2">
          <div class="admin-card-header__main">
            <span class="admin-card-header__icon" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </span>
            <div>
              <h2 id="admin-notif-heading" class="admin-card-header__title">Notificaciones recientes</h2>
              <p class="admin-card-header__desc">Nueva verificación, reserva pagada, mensaje reportado.</p>
            </div>
          </div>
          <a href="{{ route('notifications.index') }}" class="admin-card-header__link">Ver todas</a>
        </div>
        <div class="admin-notif-list">
          @forelse($adminNotifications ?? [] as $notification)
            @php
              $data = $notification->data ?? [];
              $type = $data['type'] ?? 'default';
              $title = $data['title'] ?? 'Notificación';
              $message = $data['message'] ?? '';
              $url = $data['url'] ?? route('admin.index');
              $isUnread = is_null($notification->read_at);
            @endphp
            <a href="{{ route('notifications.read', $notification->id) }}" class="admin-notif-item {{ $isUnread ? 'admin-notif-item--unread' : '' }}">
              <span class="admin-notif-item__icon admin-notif-item__icon--{{ $type }}" aria-hidden="true">
                @if($type === 'verification_pending')
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @elseif($type === 'reservation_paid')
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @elseif($type === 'problem_reported')
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                @elseif($type === 'new_reservation')
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                @else
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                @endif
              </span>
              <div class="admin-notif-item__body">
                <span class="admin-notif-item__title">{{ $title }}</span>
                @if($message)
                  <span class="admin-notif-item__message">{{ Str::limit($message, 60) }}</span>
                @endif
                <span class="admin-notif-item__time">{{ $notification->created_at->diffForHumans() }}</span>
              </div>
            </a>
          @empty
            <div class="admin-notif-empty">
              <p class="mb-0">No hay notificaciones recientes.</p>
            </div>
          @endforelse
        </div>
      </section>

      <section class="admin-card admin-card--section" aria-labelledby="recientes-heading">
        <div class="admin-card-header admin-card-header--with-desc d-flex align-items-start justify-content-between flex-wrap gap-2">
          <div class="admin-card-header__main">
            <span class="admin-card-header__icon" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </span>
            <div>
              <h2 id="recientes-heading" class="admin-card-header__title">Rentados y pagados</h2>
              <p class="admin-card-header__desc">
                @if(isset($paidReservations) && $paidReservations->isNotEmpty())
                  {{ $paidReservations->count() }} reciente{{ $paidReservations->count() === 1 ? '' : 's' }}
                @else
                  Últimas reservas cobradas
                @endif
              </p>
            </div>
          </div>
          <a href="{{ route('admin.finances.index') }}" class="admin-card-header__link">Ver finanzas</a>
        </div>
        <div class="admin-paid-list">
          @forelse($paidReservations ?? [] as $reservation)
            <div class="admin-paid-list__row">
              <div class="admin-paid-list__info">
                <span class="admin-paid-list__title">{{ $reservation->apartment?->title ?? 'Inmueble #'.$reservation->apartment_id }}</span>
                <span class="admin-paid-list__dates">{{ $reservation->check_in?->format('d/m/Y') }} – {{ $reservation->check_out?->format('d/m/Y') }}</span>
              </div>
              <div class="admin-paid-list__amount">
                <span class="admin-paid-list__plus">+</span>
                <span class="admin-paid-list__value">${{ number_format($reservation->total_amount_cents / 100, 0) }} MXN</span>
              </div>
            </div>
          @empty
            <div class="admin-paid-list__empty">
              <span class="admin-paid-list__empty-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="32" height="32"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </span>
              <p class="admin-paid-list__empty-text">Aún no hay reservas pagadas.</p>
              <a href="{{ route('admin.finances.index') }}" class="admin-paid-list__empty-link">Ir a Finanzas</a>
            </div>
          @endforelse
        </div>
      </section>
    </div>
  </div>
</div>

@push('css')
<style>
  /* Breadcrumb */
  .admin-dashboard__breadcrumb {
    font-size: 0.8125rem;
    color: #6B5344;
  }
  .admin-dashboard__breadcrumb a {
    color: #6F4E37;
    text-decoration: none;
  }
  .admin-dashboard__breadcrumb a:hover { text-decoration: underline; color: #4A3728; }
  .admin-dashboard__breadcrumb-sep { margin: 0 0.35rem; opacity: 0.7; }

  .admin-dashboard .admin-card {
    border-color: #E8E2DA;
    border-radius: 1rem;
    box-shadow: 0 1px 3px rgba(44,24,16,.04);
  }

  /* Header */
  .admin-dashboard__header-inner {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
  }
  .admin-dashboard__header-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 0.75rem;
    background: rgba(111,78,55,.1);
    color: #6F4E37;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .admin-dashboard__title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2C1810;
    letter-spacing: -0.02em;
    margin: 0 0 0.25rem;
  }
  .admin-dashboard__subtitle {
    font-size: 0.9375rem;
    color: #6B5344;
    margin: 0;
    line-height: 1.45;
  }

  /* Alerta verificaciones */
  .admin-dashboard__alert {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    background: rgba(245,158,11,.12);
    border: 1px solid rgba(245,158,11,.35);
    border-radius: 0.75rem;
    color: #92400e;
    font-size: 0.9375rem;
  }
  .admin-dashboard__alert-icon {
    width: 1.5rem;
    height: 1.5rem;
    border-radius: 50%;
    background: #f59e0b;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
    flex-shrink: 0;
  }
  .admin-dashboard__alert-body { flex: 1; }
  .admin-dashboard__alert-link {
    display: inline-block;
    margin-top: 0.5rem;
    font-weight: 600;
    color: #b45309;
    text-decoration: none;
  }
  .admin-dashboard__alert-link:hover { text-decoration: underline; color: #92400e; }

  /* Sección Resumen */
  .admin-dashboard__section-title {
    font-size: 0.8125rem;
    font-weight: 600;
    color: #6B5344;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 1rem;
  }

  /* KPIs (clickeables) */
  .admin-kpi {
    background: #fff;
    border: 1px solid #E8E2DA;
    border-radius: 1rem;
    padding: 1.25rem 1rem;
    height: 100%;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
  .admin-kpi--link {
    display: block;
    color: inherit;
    text-decoration: none;
  }
  .admin-kpi--link:hover {
    border-color: #D4C4B0;
    box-shadow: 0 4px 12px rgba(44,24,16,.06);
    color: inherit;
  }
  .admin-kpi__icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
  }
  .admin-kpi__icon--primary { background: rgba(111,78,55,.1); color: #6F4E37; }
  .admin-kpi__icon--accent { background: rgba(82,121,111,.12); color: #52796F; }
  .admin-kpi__label {
    font-size: 0.8125rem;
    font-weight: 500;
    color: #6B5344;
    margin-bottom: 0.25rem;
  }
  .admin-kpi__value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2C1810;
    letter-spacing: -0.02em;
  }
  .admin-kpi__value--currency { font-size: 1.25rem; }
  .admin-kpi__currency { font-size: 0.75rem; font-weight: 600; color: #6B5344; }
  .admin-kpi__meta {
    font-size: 0.75rem;
    color: #6B5344;
    margin-top: 0.25rem;
  }
  .admin-kpi--highlight {
    background: #FFFDFB;
    border-color: rgba(82,121,111,.25);
  }
  .admin-kpi--highlight:hover { border-color: rgba(82,121,111,.4); }

  /* Cards con cabecera con icono */
  .admin-card--section .admin-card-header {
    background: #FFFDFB;
    border-bottom: 1px solid #E8E2DA;
    padding: 1rem 1.25rem;
  }
  .admin-card-header--with-desc .admin-card-header__main {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
  }
  .admin-card-header__icon {
    width: 2.25rem;
    height: 2.25rem;
    border-radius: 0.5rem;
    background: rgba(111,78,55,.1);
    color: #6F4E37;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .admin-card-header__title {
    font-size: 1rem;
    font-weight: 700;
    color: #2C1810;
    margin: 0 0 0.15rem;
  }
  .admin-card-header__desc {
    font-size: 0.8125rem;
    color: #6B5344;
    margin: 0;
  }
  .admin-card-header__link {
    font-size: 0.8125rem;
    font-weight: 600;
    color: #6F4E37;
    text-decoration: none;
    white-space: nowrap;
  }
  .admin-card-header__link:hover { color: #4A3728; text-decoration: underline; }

  .admin-form-label {
    display: block;
    font-size: 0.8125rem;
    font-weight: 500;
    color: #6B5344;
    margin-bottom: 0.25rem;
  }
  .admin-form-input { border-color: #E8E2DA !important; border-radius: 0.5rem !important; }
  .admin-form-input:focus { border-color: #6F4E37 !important; box-shadow: 0 0 0 3px rgba(111,78,55,.12) !important; }
  .admin-revenue-form .form-control { max-width: 100%; }

  /* Módulos en grid en desktop */
  .admin-modules { display: flex; flex-direction: column; }
  .admin-modules--grid { display: grid; grid-template-columns: 1fr; }
  @media (min-width: 768px) {
    .admin-modules--grid { grid-template-columns: repeat(2, 1fr); }
  }
  .admin-modules__item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.25rem;
    color: #2C1810;
    text-decoration: none;
    border-bottom: 1px solid #F0EBE3;
    transition: background 0.15s, border-color 0.15s;
  }
  .admin-modules__item:last-child { border-bottom: none; }
  .admin-modules__item:hover {
    background: #FAF6F0;
    border-color: #E8E2DA;
    color: #2C1810;
  }
  .admin-modules__icon {
    width: 2.25rem;
    height: 2.25rem;
    border-radius: 0.5rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .admin-modules__icon--primary { background: rgba(111,78,55,.1); color: #6F4E37; }
  .admin-modules__icon--gray { background: #F0EBE3; color: #6B5344; }
  .admin-modules__label { flex: 1; font-weight: 500; font-size: 0.9375rem; min-width: 0; }
  .admin-modules__badge {
    background: #6F4E37;
    color: #fff;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    min-width: 1.75rem;
    text-align: center;
    flex-shrink: 0;
  }
  .admin-modules__badge--warning { background: #f59e0b; color: #fff; }

  /* Rentados y pagados */
  .admin-paid-list {
    max-height: 300px;
    overflow-y: auto;
    padding: 0.25rem 0;
  }
  .admin-paid-list__row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 0.75rem 1.25rem;
    border-bottom: 1px solid #F0EBE3;
  }
  .admin-paid-list__row:last-child { border-bottom: none; }
  .admin-paid-list__info {
    display: flex;
    flex-direction: column;
    min-width: 0;
  }
  .admin-paid-list__title {
    font-size: 0.875rem;
    font-weight: 500;
    color: #2C1810;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .admin-paid-list__dates {
    font-size: 0.75rem;
    color: #6B5344;
    margin-top: 0.125rem;
  }
  .admin-paid-list__amount {
    flex-shrink: 0;
    font-size: 0.875rem;
    font-weight: 600;
    color: #52796F;
  }
  .admin-paid-list__plus { margin-right: 0.125rem; }
  .admin-paid-list__empty {
    padding: 2rem 1.5rem;
    text-align: center;
  }
  .admin-paid-list__empty-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 0.75rem;
    background: rgba(111,78,55,.08);
    color: #6F4E37;
    margin-bottom: 0.75rem;
  }
  .admin-paid-list__empty-text {
    font-size: 0.9375rem;
    color: #6B5344;
    margin: 0 0 0.75rem;
  }
  .admin-paid-list__empty-link {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    background: #6F4E37;
    color: #fff;
    text-decoration: none;
    transition: background 0.2s;
  }
  .admin-paid-list__empty-link:hover { background: #4A3728; color: #fff; }

  /* Ocupación */
  .admin-occupancy {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0;
    padding: 0;
  }
  @media (min-width: 768px) {
    .admin-occupancy { grid-template-columns: repeat(3, 1fr); gap: 0; }
  }
  .admin-occupancy__col {
    padding: 1rem 1.25rem;
    border-right: 1px solid #F0EBE3;
  }
  .admin-occupancy__col:last-child { border-right: none; }
  @media (max-width: 767px) {
    .admin-occupancy__col { border-right: none; border-bottom: 1px solid #F0EBE3; }
    .admin-occupancy__col:last-child { border-bottom: none; }
  }
  .admin-occupancy__col-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 700;
    color: #2C1810;
    margin: 0 0 0.75rem;
  }
  .admin-occupancy__col-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 1.5rem;
    height: 1.5rem;
    padding: 0 0.35rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 700;
    background: #E8E2DA;
    color: #6B5344;
  }
  .admin-occupancy__col-badge--active {
    background: rgba(82,121,111,.2);
    color: #52796F;
  }
  .admin-occupancy__list {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  .admin-occupancy__item {
    margin-bottom: 0.5rem;
  }
  .admin-occupancy__item:last-child { margin-bottom: 0; }
  .admin-occupancy__link {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
    padding: 0.5rem 0;
    color: #2C1810;
    text-decoration: none;
    font-size: 0.875rem;
    border-radius: 0.375rem;
    transition: background 0.15s;
  }
  .admin-occupancy__link:hover {
    background: #FAF6F0;
    color: #2C1810;
  }
  .admin-occupancy__item-title {
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .admin-occupancy__item-dates {
    font-size: 0.75rem;
    color: #6B5344;
  }
  .admin-occupancy__empty {
    font-size: 0.8125rem;
    color: #6B5344;
    padding: 0.5rem 0;
    margin: 0;
  }

  /* Notificaciones recientes (admin) */
  .admin-notif-list {
    max-height: 320px;
    overflow-y: auto;
    padding: 0.25rem 0;
  }
  .admin-notif-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.75rem 1.25rem;
    color: inherit;
    text-decoration: none;
    border-bottom: 1px solid #F0EBE3;
    transition: background 0.15s;
  }
  .admin-notif-item:last-child { border-bottom: none; }
  .admin-notif-item:hover { background: #FAF6F0; }
  .admin-notif-item--unread { background: rgba(111,78,55,.04); }
  .admin-notif-item--unread:hover { background: rgba(111,78,55,.07); }
  .admin-notif-item__icon {
    width: 2rem;
    height: 2rem;
    border-radius: 0.5rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .admin-notif-item__icon--verification_pending { background: rgba(245,158,11,.15); color: #b45309; }
  .admin-notif-item__icon--reservation_paid,
  .admin-notif-item__icon--new_reservation { background: rgba(82,121,111,.15); color: #52796F; }
  .admin-notif-item__icon--problem_reported { background: rgba(229,57,53,.12); color: #c62828; }
  .admin-notif-item__icon--default { background: rgba(111,78,55,.12); color: #6F4E37; }
  .admin-notif-item__body {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
  }
  .admin-notif-item__title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #2C1810;
  }
  .admin-notif-item__message {
    font-size: 0.8125rem;
    color: #6B5344;
    line-height: 1.35;
  }
  .admin-notif-item__time {
    font-size: 0.75rem;
    color: #8B7355;
    margin-top: 0.25rem;
  }
  .admin-notif-empty {
    padding: 1.25rem 1.25rem;
    font-size: 0.875rem;
    color: #6B5344;
    text-align: center;
  }
</style>
@endpush
@endsection
