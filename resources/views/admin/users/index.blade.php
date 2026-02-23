@extends('layouts.admin')
@section('title', 'Usuarios')

@section('content')
<div class="d-flex flex-column flex-md-row gap-2 align-items-md-end mb-4">
  <a href="{{ route('admin.index') }}" class="btn btn-admin-outline text-decoration-none">← Regresar</a>
</div>

<p class="admin-subtitle mb-4">
  Listado de cuentas de acceso. Estado, fecha de registro, último acceso y acciones. Las contraseñas están cifradas y no pueden verse.
</p>

<div class="table-responsive">
  <table class="table table-striped table-hover align-middle w-100">
    <thead>
      <tr>
        <th>#</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Rol</th>
        <th>Estado</th>
        <th>Registro</th>
        <th>Último acceso</th>
        <th>Vinculado a</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($users as $u)
        <tr>
          <td>{{ $u->id }}</td>
          <td>{{ $u->name }}</td>
          <td>{{ $u->email }}</td>
          <td>
            @if($u->role === 'admin')
              <span class="badge bg-danger">Admin</span>
            @elseif($u->role === 'owner')
              <span class="badge bg-primary">Dueño</span>
            @else
              <span class="badge bg-info text-dark">Cliente</span>
            @endif
          </td>
          <td>
            @if($u->isSuspended())
              <span class="badge bg-secondary">Suspendido</span>
            @else
              <span class="badge bg-success">Activo</span>
            @endif
          </td>
          <td class="small text-secondary">
            {{ $u->created_at?->format('d/m/Y H:i') ?? '—' }}
          </td>
          <td class="small text-secondary">
            @if($u->last_login_at)
              {{ $u->last_login_at->format('d/m/Y H:i') }}
            @else
              —
            @endif
          </td>
          <td class="small">
            @if($u->owner_id)
              Dueño #{{ $u->owner_id }}
            @elseif($u->client_id)
              Cliente #{{ $u->client_id }}
            @else
              —
            @endif
          </td>
          <td>
            <div class="d-flex flex-wrap gap-1 align-items-center">
              <a href="{{ route('admin.users.activity', $u) }}" class="btn btn-sm btn-outline-secondary">Historial</a>
              @if(!$u->isAdmin())
                @if($u->isSuspended())
                  <form action="{{ route('admin.users.unblock', $u) }}" method="POST" class="d-inline" data-confirm="¿Desbloquear la cuenta de {{ $u->email }}?">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-success">Desbloquear</button>
                  </form>
                @else
                  <form action="{{ route('admin.users.block', $u) }}" method="POST" class="d-inline" data-confirm="¿Bloquear la cuenta de {{ $u->email }}? El usuario no podrá iniciar sesión.">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">Bloquear</button>
                  </form>
                @endif
              @endif
              <form action="{{ route('admin.users.send-password-reset', $u) }}" method="POST" class="d-inline" data-confirm="¿Enviar enlace para restablecer contraseña a {{ $u->email }}?">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-warning">Restablecer contraseña</button>
              </form>
            </div>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
