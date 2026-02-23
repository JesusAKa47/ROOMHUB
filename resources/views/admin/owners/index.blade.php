@extends('layouts.admin')
@section('title','Dueños')

@section('content')
<div class="d-flex flex-column flex-md-row gap-2 align-items-md-end mb-4">
  <a href="{{ route('admin.owners.create') }}" class="btn btn-primary">+ Nuevo dueño</a>
  <a href="{{ route('admin.index') }}" class="btn btn-admin-outline text-decoration-none">← Regresar</a>
  <form method="GET" class="row g-2 ms-md-auto">
    <div class="col-12 col-md"><input name="s" value="{{ request('s') }}" class="form-control" placeholder="Buscar nombre/email"></div>
    <div class="col-6 col-md">
      <select name="type" class="form-select">
        <option value="">-- Tipo --</option>
        <option value="persona" @selected(request('type')=='persona')>Persona</option>
        <option value="empresa" @selected(request('type')=='empresa')>Empresa</option>
      </select>
    </div>
    <div class="col-6 col-md">
      <select name="is_active" class="form-select">
        <option value="">-- Estado --</option>
        <option value="1" @selected(request('is_active')==='1')>Activo</option>
        <option value="0" @selected(request('is_active')==='0')>Inactivo</option>
      </select>
    </div>
    <div class="col-12 col-md-auto"><button class="btn btn-outline-secondary w-100">Filtrar</button></div>
  </form>
</div>

<div class="table-responsive">
  <table id="dt-owners" class="table table-striped table-hover align-middle w-100">
    <thead><tr><th>#</th><th>Avatar</th><th>Nombre</th><th>Email</th><th>Tipo</th><th>Desde</th><th>Activo</th><th>Acciones</th></tr></thead>
    <tbody>
      @foreach($owners as $o)
      <tr>
        <td>{{ $o->id }}</td>
        <td>@if($o->avatar_path)<img src="{{ url('files/'.$o->avatar_path) }}" class="thumb">@endif</td>
        <td>{{ $o->name }}</td>
        <td>{{ $o->email }}</td>
        <td>{{ ucfirst($o->type) }}</td>
        <td>{{ optional($o->since)->format('Y-m-d') }}</td>
        <td><span class="badge bg-{{ $o->is_active?'success':'secondary' }}">{{ $o->is_active?'Sí':'No' }}</span></td>
        <td class="text-nowrap">
          <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.owners.edit',$o) }}">Editar</a>
          <form action="{{ route('admin.owners.destroy',$o) }}" method="POST" class="d-inline" data-confirm="¿Eliminar este dueño? Esta acción no se puede deshacer." data-confirm-icon="warning">
            @csrf @method('DELETE') <button class="btn btn-sm btn-outline-danger">Borrar</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection

@push('js')
<script>
new DataTable('#dt-owners',{responsive:true,pageLength:10,order:[[0,'desc']],
 language:{url:'https://cdn.datatables.net/plug-ins/2.1.8/i18n/es-MX.json'}});
</script>
@endpush
