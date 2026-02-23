@extends('layouts.admin')
@section('title', 'Historial de actividad')

@section('content')
<div class="d-flex flex-column flex-md-row gap-2 align-items-md-end mb-4">
  <a href="{{ route('admin.users.index') }}" class="btn btn-admin-outline text-decoration-none">← Usuarios</a>
</div>

<div class="admin-card mb-4">
  <div class="admin-card-header">Usuario</div>
  <div class="admin-card-body">
    <div class="row g-2 small">
      <div class="col-md-4"><strong>Nombre:</strong> {{ $user->name }}</div>
      <div class="col-md-4"><strong>Email:</strong> {{ $user->email }}</div>
      <div class="col-md-2"><strong>Rol:</strong>
        @if($user->role === 'admin') Admin
        @elseif($user->role === 'owner') Dueño
        @else Cliente
        @endif
      </div>
      <div class="col-md-2"><strong>Estado:</strong>
        @if($user->isSuspended()) Suspendido
        @else Activo
        @endif
      </div>
      <div class="col-md-4"><strong>Registro:</strong> {{ $user->created_at?->format('d/m/Y H:i') ?? '—' }}</div>
      <div class="col-md-4"><strong>Último acceso:</strong> {{ $user->last_login_at?->format('d/m/Y H:i') ?? '—' }}</div>
    </div>
  </div>
</div>

<h2 class="h6 text-secondary text-uppercase fw-semibold mb-3">Historial de actividad</h2>

@if($activities->isEmpty())
  <p class="text-muted small">No hay registros de actividad para este usuario.</p>
@else
  <div class="admin-card">
    <div class="list-group list-group-flush">
      @foreach($activities as $activity)
        <div class="list-group-item d-flex flex-wrap justify-content-between align-items-center gap-2 py-3" style="border-color: #e5e7eb;">
          <div>
            <span class="fw-medium">{{ $activity->action_label }}</span>
            @if($activity->ip_address)
              <span class="small text-muted ms-2">{{ $activity->ip_address }}</span>
            @endif
          </div>
          <span class="small text-secondary">{{ $activity->created_at->format('d/m/Y H:i') }}</span>
        </div>
      @endforeach
    </div>
  </div>
@endif
@endsection
