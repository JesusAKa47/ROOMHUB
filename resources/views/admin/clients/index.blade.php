@extends('layouts.admin')
@section('title','Clientes')

@section('content')
<div class="d-flex flex-column flex-md-row gap-2 align-items-md-end mb-4">
  <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">+ Nuevo cliente</a>
  <a href="{{ route('admin.index') }}" class="btn btn-admin-outline text-decoration-none">← Regresar</a>
  <form method="GET" class="row g-2 ms-md-auto">
    <div class="col-12 col-md"><input name="s" value="{{ request('s') }}" class="form-control" placeholder="Buscar nombre/email"></div>
    <div class="col-6 col-md">
      <select name="gender" class="form-select">
        <option value="">-- Género --</option>
        @foreach(['hombre','mujer','otro'] as $g)
        <option value="{{ $g }}" @selected(request('gender')===$g)>{{ ucfirst($g) }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-6 col-md">
      <select name="is_verified" class="form-select">
        <option value="">-- Verificado --</option>
        <option value="1" @selected(request('is_verified')==='1' )>Sí</option>
        <option value="0" @selected(request('is_verified')==='0' )>No</option>
      </select>
    </div>
    <div class="col-12 col-md-auto"><button class="btn btn-outline-secondary w-100">Filtrar</button></div>
  </form>
</div>

<div class="table-responsive">
  <table id="dt-clients" class="table table-striped table-hover align-middle w-100">
    <thead>
      <tr>
        <th>#</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Género</th>
        <th>Nacimiento</th>
        <th>Verificado</th>
        <th>ID</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($clients as $c)
      <tr>
        <td>{{ $c->id }}</td>
        <td>{{ $c->name }}</td>
        <td>{{ $c->email }}</td>
        <td>{{ ucfirst($c->gender) }}</td>
        <td>{{ optional($c->birthdate)->format('Y-m-d') }}</td>
        <td><span class="badge bg-{{ $c->is_verified?'success':'secondary' }}">{{ $c->is_verified?'Sí':'No' }}</span></td>
        <td>
          @if($c->id_scan_path)
          <a class="btn btn-sm btn-outline-secondary" href="{{ url('files/'.$c->id_scan_path) }}" target="_blank">Ver</a>
          @endif
        </td>
        <td class="text-nowrap">
          <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.clients.edit',$c) }}">Editar</a>
          <form action="{{ route('admin.clients.destroy',$c) }}" method="POST" class="d-inline" data-confirm="¿Eliminar este cliente? Esta acción no se puede deshacer." data-confirm-icon="warning">
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
  new DataTable('#dt-clients', {
    responsive: true,
    pageLength: 10,
    order: [
      [0, 'desc']
    ],
    language: {
      url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/es-MX.json'
    }
  });
</script>
@endpush