@extends('layouts.admin')
@section('title', $owner->exists ? 'Editar dueño' : 'Nuevo dueño')

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-lg-10">
    <div class="card shadow-sm">
      <div class="card-body">
        <form method="POST" action="{{ $owner->exists ? route('admin.owners.update',$owner) : route('admin.owners.store') }}" enctype="multipart/form-data" novalidate>
          @csrf @if($owner->exists) @method('PUT') @endif
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="form-label required">Nombre</label>
              <input name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name',$owner->name) }}" required minlength="3" maxlength="120">
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label required">Email</label>
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email',$owner->email) }}" required>
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-4">
              <label class="form-label required">Teléfono</label>
              <input name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone',$owner->phone) }}" required minlength="7" maxlength="20">
              @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-4">
              <label class="form-label required">Tipo</label>
              <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                @php $t=old('type',$owner->type) @endphp
                <option value="persona" @selected($t=='persona' )>Persona</option>
                <option value="empresa" @selected($t=='empresa' )>Empresa</option>
              </select>
              @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-4">
              <label class="form-label required d-block">Activo</label>
              <div class="form-check form-check-inline">
                <input class="form-check-input @error('is_active') is-invalid @enderror" type="radio" name="is_active" id="a1" value="1" @checked(old('is_active',$owner->is_active?'1':'')==='1') required>
                <label class="form-check-label" for="a1">Sí</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input @error('is_active') is-invalid @enderror" type="radio" name="is_active" id="a0" value="0" @checked(old('is_active',$owner->is_active===false?'0':'')==='0') required>
                <label class="form-check-label" for="a0">No</label>
              </div>
              @error('is_active')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label required">Avatar (jpg/png/webp, máx 4MB)</label>
              <input type="file" name="avatar" accept=".jpg,.jpeg,.png,.webp" class="form-control @error('avatar') is-invalid @enderror" {{ $owner->exists?'':'required' }}>
              @error('avatar')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
              @if($owner->avatar_path)<img src="{{ url('files/'.$owner->avatar_path) }}" class="thumb mt-2">@endif
            </div>
            <div class="col-12 col-md-3">
              <label class="form-label required">Cliente desde</label>
              <input type="date" name="since" class="form-control @error('since') is-invalid @enderror" value="{{ old('since',optional($owner->since)->format('Y-m-d')) }}" required>
              @error('since')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
              <label class="form-label required">Notas</label>
              <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" required minlength="10" maxlength="1000">{{ old('notes',$owner->notes) }}</textarea>
              @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 d-grid d-md-flex gap-2">
              <a href="{{ route('admin.owners.index') }}" class="btn btn-outline-secondary">Cancelar</a>
              <button class="btn btn-primary">{{ $owner->exists?'Guardar cambios':'Crear dueño' }}</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection