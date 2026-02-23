@extends('layouts.admin')
@section('title', $client->exists ? 'Editar cliente' : 'Nuevo cliente')

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-lg-10">
    <div class="card shadow-sm">
      <div class="card-body">
        <form method="POST" action="{{ $client->exists ? route('admin.clients.update',$client) : route('admin.clients.store') }}" enctype="multipart/form-data" novalidate>
          @csrf @if($client->exists) @method('PUT') @endif
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="form-label required">Nombre</label>
              <input name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name',$client->name) }}" required minlength="3" maxlength="120">
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label required">Email</label>
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email',$client->email) }}" required>
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-4">
              <label class="form-label required">Teléfono</label>
              <input name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone',$client->phone) }}" required minlength="7" maxlength="20">
              @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-4">
              <label class="form-label required">Género</label>
              <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                @php $g=old('gender',$client->gender) @endphp
                @foreach(['hombre','mujer','otro'] as $opt)
                <option value="{{ $opt }}" @selected($g===$opt)>{{ ucfirst($opt) }}</option>
                @endforeach
              </select>
              @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-4">
              <label class="form-label required d-block">¿Verificado?</label>
              <div class="form-check form-check-inline">
                <input class="form-check-input @error('is_verified') is-invalid @enderror" type="radio" name="is_verified" id="v1" value="1" @checked(old('is_verified',$client->is_verified?'1':'')==='1') required>
                <label class="form-check-label" for="v1">Sí</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input @error('is_verified') is-invalid @enderror" type="radio" name="is_verified" id="v0" value="0" @checked(old('is_verified',$client->is_verified===false?'0':'')==='0') required>
                <label class="form-check-label" for="v0">No</label>
              </div>
              @error('is_verified')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-4">
              <label class="form-label required">Fecha de nacimiento</label>
              <input type="date" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror" value="{{ old('birthdate',optional($client->birthdate)->format('Y-m-d')) }}" required>
              @error('birthdate')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-8">
              <label class="form-label required">Biografía</label>
              <textarea name="bio" rows="3" class="form-control @error('bio') is-invalid @enderror" required minlength="10" maxlength="1000">{{ old('bio',$client->bio) }}</textarea>
              @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
              <label class="form-label required">Identificación (PDF/JPG/PNG/WEBP, máx 4MB)</label>
              <input type="file" name="id_scan" class="form-control @error('id_scan') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png,.webp" {{ $client->exists?'':'required' }}>
              @error('id_scan')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
              @if($client->id_scan_path)
              <div class="mt-2"><a class="btn btn-sm btn-outline-secondary" href="{{ url('files/'.$client->id_scan_path) }}" target="_blank">Ver archivo</a></div>
              @endif
            </div>
            <div class="col-12 d-grid d-md-flex gap-2">
              <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary">Cancelar</a>
              <button class="btn btn-primary">{{ $client->exists?'Guardar cambios':'Crear cliente' }}</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection