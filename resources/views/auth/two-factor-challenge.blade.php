@extends('layouts.auth')

@section('title', 'RoomHub | Código de verificación')

@section('nav-link')
  <a href="{{ route('login') }}" class="nav-link">Volver al inicio de sesión</a>
@endsection

@section('content')
  <h1>Verificación en dos pasos</h1>
  <p class="sub">Introduce el código de 6 dígitos que muestra tu app de autenticación (Google Authenticator, Authy, etc.).</p>

  <form method="POST" action="{{ route('two-factor.verify') }}" class="space-y-4">
    @csrf

    <div>
      <label for="code">Código</label>
      <input
        id="code"
        type="text"
        name="code"
        inputmode="numeric"
        pattern="[0-9]*"
        maxlength="6"
        autocomplete="one-time-code"
        placeholder="000000"
        required
        autofocus
        class="@error('code') is-invalid @enderror"
      >
      @error('code')
        <p class="invalid-feedback">{{ $message }}</p>
      @enderror
    </div>

    <button type="submit" class="btn-submit">Verificar y continuar</button>
  </form>
@endsection
