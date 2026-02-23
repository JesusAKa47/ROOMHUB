@extends('layouts.admin')
@section('title', 'Verificaciones de anfitriones')

@section('content')
<div class="d-flex flex-column flex-md-row gap-2 align-items-md-end mb-4">
  <a href="{{ route('admin.index') }}" class="btn btn-admin-outline text-decoration-none">← Regresar</a>
</div>

<p class="admin-subtitle mb-4">
  Verificaciones de identidad (INE + preguntas) enviadas por usuarios que quieren ser anfitriones. Aprueba o rechaza; si rechazas, indica el motivo (ej. imagen del INE no legible).
</p>

@if($errors->has('general'))
  <div class="alert alert-danger">{{ $errors->first('general') }}</div>
@endif

<div class="table-responsive">
  <table class="table table-striped table-hover align-middle w-100">
    <thead>
      <tr>
        <th>#</th>
        <th>Usuario</th>
        <th>Email</th>
        <th>Estado</th>
        <th>Enviado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @forelse($verifications as $v)
        <tr>
          <td>{{ $v->id }}</td>
          <td>{{ $v->user?->name ?? '—' }}</td>
          <td>{{ $v->user?->email ?? '—' }}</td>
          <td>
            @if($v->status === 'pendiente')
              <span class="badge bg-warning text-dark">Pendiente</span>
            @elseif($v->status === 'en_revision')
              <span class="badge bg-info">En revisión</span>
            @elseif($v->status === 'aprobado')
              <span class="badge bg-success">Aprobado</span>
            @else
              <span class="badge bg-danger">Rechazado</span>
            @endif
          </td>
          <td class="small">{{ $v->submitted_at?->format('d/m/Y H:i') ?? '—' }}</td>
          <td>
            <a href="{{ route('admin.verifications.show', $v) }}" class="btn btn-sm btn-outline-primary">Ver</a>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="text-center text-secondary py-4">No hay verificaciones.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
