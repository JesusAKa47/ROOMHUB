@extends('layouts.admin')
@section('title', 'Finanzas')

@section('content')
<div class="d-flex flex-column flex-md-row gap-2 align-items-md-end justify-content-md-between mb-4">
  <a href="{{ route('admin.index') }}" class="btn btn-admin-outline text-decoration-none">← Regresar</a>
</div>

<h1 class="admin-title mb-2">Módulo financiero</h1>
<p class="admin-subtitle mb-4">Comisión 10% RoomHub, ingresos por dueño, filtro por método de pago y estado de pagos pendientes.</p>

{{-- Filtros --}}
<div class="admin-card mb-4">
  <div class="admin-card-header">Filtros</div>
  <div class="admin-card-body">
    <form method="GET" action="{{ route('admin.finances.index') }}" class="row g-3 align-items-end">
      <div class="col-12 col-sm-6 col-md-3">
        <label class="form-label small mb-1">Desde</label>
        <input type="date" name="from" class="form-control form-control-sm" value="{{ $from->format('Y-m-d') }}">
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <label class="form-label small mb-1">Hasta</label>
        <input type="date" name="to" class="form-control form-control-sm" value="{{ $to->format('Y-m-d') }}">
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <label class="form-label small mb-1">Método de pago</label>
        <select name="payment_method" class="form-select form-select-sm">
          <option value="">Todos</option>
          <option value="stripe" {{ $paymentMethod === 'stripe' ? 'selected' : '' }}>Tarjeta (Stripe)</option>
          <option value="transfer" {{ $paymentMethod === 'transfer' ? 'selected' : '' }}>Transferencia</option>
          <option value="cash" {{ $paymentMethod === 'cash' ? 'selected' : '' }}>Efectivo</option>
          <option value="other" {{ $paymentMethod === 'other' ? 'selected' : '' }}>Otro</option>
        </select>
      </div>
      <div class="col-12 col-sm-6 col-md-3 d-flex flex-wrap gap-2">
        <button type="submit" class="btn btn-admin-primary btn-sm">Filtrar</button>
        <a href="{{ route('admin.finances.index') }}" class="btn btn-admin-outline btn-sm">Limpiar</a>
      </div>
    </form>
  </div>
</div>

{{-- Resumen: total, comisión, dueños --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-md-4">
    <div class="admin-card h-100">
      <div class="admin-card-body">
        <div class="small text-secondary mb-1">Ingreso bruto (periodo)</div>
        <div class="fs-4 fw-bold">${{ number_format($totalCents / 100, 0) }} MXN</div>
        <div class="small text-muted">{{ $reservationsPaid->count() }} reserva(s)</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-4">
    <div class="admin-card h-100">
      <div class="admin-card-body">
        <div class="small text-secondary mb-1">Comisión RoomHub (10%)</div>
        <div class="fs-4 fw-bold text-success">${{ number_format($commissionCents / 100, 0) }} MXN</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-4">
    <div class="admin-card h-100">
      <div class="admin-card-body">
        <div class="small text-secondary mb-1">Ingresos para dueños</div>
        <div class="fs-4 fw-bold">${{ number_format($ownersTotalCents / 100, 0) }} MXN</div>
      </div>
    </div>
  </div>
</div>

{{-- Exportar PDF / Excel --}}
<div class="mb-4">
  @php
    $queryExport = ['from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')];
    if ($paymentMethod) $queryExport['payment_method'] = $paymentMethod;
  @endphp
  <a href="{{ route('admin.reports.revenue', $queryExport) }}" class="btn btn-admin-outline btn-sm me-2" target="_blank">
    <svg xmlns="http://www.w3.org/2000/svg" class="me-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    Descargar PDF
  </a>
  <a href="{{ route('admin.reports.revenue.excel', $queryExport) }}" class="btn btn-admin-outline btn-sm">
    <svg xmlns="http://www.w3.org/2000/svg" class="me-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    Exportar Excel (CSV)
  </a>
</div>

{{-- Ingresos por dueño --}}
<div class="admin-card mb-4">
  <div class="admin-card-header">Ingresos por dueño (periodo filtrado)</div>
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr>
          <th>Dueño</th>
          <th class="text-end">Reservas</th>
          <th class="text-end">Bruto</th>
          <th class="text-end">Comisión 10%</th>
          <th class="text-end">Para dueño</th>
        </tr>
      </thead>
      <tbody>
        @forelse($byOwner as $row)
          <tr>
            <td>
              @if($row['owner'])
                {{ $row['owner']->name }}
                <span class="small text-muted">#{{ $row['owner_id'] }}</span>
              @else
                <span class="text-muted">— Sin dueño</span>
              @endif
            </td>
            <td class="text-end">{{ $row['count'] }}</td>
            <td class="text-end">${{ number_format($row['total_cents'] / 100, 0) }}</td>
            <td class="text-end text-success">${{ number_format($row['commission_cents'] / 100, 0) }}</td>
            <td class="text-end">${{ number_format($row['owner_amount_cents'] / 100, 0) }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-muted small">No hay ingresos por dueño en el periodo.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Pagos pendientes --}}
<div class="admin-card mb-4">
  <div class="admin-card-header d-flex align-items-center justify-content-between">
    <span>Estado de pagos pendientes</span>
    <span class="badge bg-warning text-dark">{{ $pending->count() }}</span>
  </div>
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Alojamiento</th>
          <th>Cliente</th>
          <th>Entrada / Salida</th>
          <th class="text-end">Monto</th>
        </tr>
      </thead>
      <tbody>
        @forelse($pending as $r)
          <tr>
            <td class="small">{{ $r->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $r->apartment->title ?? '—' }}</td>
            <td class="small">{{ $r->user->name ?? '—' }} ({{ $r->user->email ?? '—' }})</td>
            <td class="small">{{ $r->check_in?->format('d/m/Y') }} – {{ $r->check_out?->format('d/m/Y') }}</td>
            <td class="text-end">${{ number_format($r->total_amount_cents / 100, 0) }} MXN</td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-muted small">No hay pagos pendientes.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Detalle reservas pagadas (periodo) --}}
<div class="admin-card">
  <div class="admin-card-header">Detalle reservas pagadas (periodo)</div>
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Alojamiento</th>
          <th>Cliente</th>
          <th>Método</th>
          <th class="text-end">Total</th>
          <th class="text-end">Comisión</th>
          <th class="text-end">Dueño</th>
        </tr>
      </thead>
      <tbody>
        @forelse($reservationsPaid as $r)
          <tr>
            <td class="small">{{ $r->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $r->apartment->title ?? '—' }}</td>
            <td class="small">{{ $r->user->name ?? '—' }}</td>
            <td class="small">{{ \App\Models\Reservation::paymentMethodLabel($r->payment_method ?? 'stripe') }}</td>
            <td class="text-end">${{ number_format($r->total_amount_cents / 100, 0) }}</td>
            <td class="text-end text-success">${{ number_format($r->commission_cents / 100, 0) }}</td>
            <td class="text-end">${{ number_format($r->owner_amount_cents / 100, 0) }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-muted small">No hay reservas pagadas en el periodo.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
