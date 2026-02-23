@extends('layouts.admin')
@section('title', 'Verificación #' . $verification->id)

@section('content')
<div class="d-flex flex-column flex-md-row gap-2 align-items-md-end mb-4">
  <a href="{{ route('admin.verifications.index') }}" class="btn btn-admin-outline text-decoration-none">← Verificaciones</a>
</div>

<div class="row g-3 mb-4">
  <div class="col-12 col-lg-6">
    <div class="admin-card">
      <div class="admin-card-header">Usuario</div>
      <div class="admin-card-body">
        <p class="mb-1 fw-semibold">{{ $verification->user?->name ?? '—' }}</p>
        <p class="mb-0 small text-secondary">{{ $verification->user?->email ?? '—' }}</p>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-6">
    <div class="admin-card">
      <div class="admin-card-header">Estado y fechas</div>
      <div class="admin-card-body">
        <p class="mb-1">
          @if($verification->status === 'pendiente')
            <span class="badge bg-warning text-dark">Pendiente</span>
          @elseif($verification->status === 'en_revision')
            <span class="badge bg-info">En revisión</span>
          @elseif($verification->status === 'aprobado')
            <span class="badge bg-success">Aprobado</span>
          @else
            <span class="badge bg-danger">Rechazado</span>
          @endif
        </p>
        <p class="mb-0 small text-secondary">
          Enviado: {{ $verification->submitted_at?->format('d/m/Y H:i') ?? '—' }}
          @if($verification->reviewed_at)
            · Revisado: {{ $verification->reviewed_at->format('d/m/Y H:i') }}
          @endif
        </p>
      </div>
    </div>
  </div>
</div>

<div class="admin-card mb-4">
  <div class="admin-card-header">Foto INE (frente)</div>
  <div class="admin-card-body">
    @if($verification->ine_photo_path)
      <a href="{{ url('files/' . $verification->ine_photo_path) }}" target="_blank" rel="noopener" class="d-inline-block">
        <img src="{{ url('files/' . $verification->ine_photo_path) }}" alt="INE" class="img-fluid rounded border" style="max-height: 320px;">
      </a>
      <p class="small text-secondary mt-2 mb-0">Haz clic en la imagen para abrir en tamaño completo.</p>
    @else
      <p class="text-secondary mb-0">No hay imagen.</p>
    @endif
  </div>
</div>

<div class="admin-card mb-4">
  <div class="admin-card-header">Respuestas a preguntas de verificación</div>
  <div class="admin-card-body">
    @if($verification->answers && is_array($verification->answers))
      <ul class="list-unstyled mb-0">
        @foreach($verification->answers as $key => $value)
          <li class="mb-2"><strong>{{ $key }}:</strong> {{ $value }}</li>
        @endforeach
      </ul>
    @else
      <p class="text-secondary mb-0">Sin respuestas.</p>
    @endif
  </div>
</div>

@if($verification->isRejected() && $verification->rejection_reason)
  <div class="admin-card mb-4 border-warning">
    <div class="admin-card-header bg-warning bg-opacity-10">Motivo de rechazo</div>
    <div class="admin-card-body">{{ $verification->rejection_reason }}</div>
  </div>
@endif

@if(in_array($verification->status, ['pendiente', 'en_revision'], true))
  <div class="admin-card">
    <div class="admin-card-header">Acciones</div>
    <div class="admin-card-body">
      <div class="d-flex flex-wrap gap-3 align-items-start">
        <form action="{{ route('admin.verifications.approve', $verification) }}" method="POST" class="d-inline" data-confirm="El usuario podrá completar su perfil de anfitrión y publicar alojamientos." data-confirm-title="¿Aprobar esta verificación?">
          @csrf
          <button type="submit" class="btn btn-success">Aprobar</button>
        </form>
        @if($verification->status === 'pendiente')
          <form action="{{ route('admin.verifications.set-under-review', $verification) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-info">Dejar en revisión</button>
          </form>
        @endif
        <form action="{{ route('admin.verifications.reject', $verification) }}" method="POST" class="d-inline">
          @csrf
          <div class="d-flex flex-wrap align-items-end gap-2">
            <div>
              <input type="text" name="rejection_reason" class="form-control" placeholder="Motivo del rechazo (ej.: No se pudo ver bien la imagen del INE)" required maxlength="1000" style="min-width: 280px;">
              @error('rejection_reason')
                <div class="text-danger small">{{ $message }}</div>
              @enderror
            </div>
            <button type="submit" class="btn btn-danger">Rechazar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endif
@endsection
