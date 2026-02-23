@extends('layouts.auth')

@section('title', 'RoomHub | Iniciar sesión')

@section('nav-link')
  @if (Route::has('register'))
    <a href="{{ route('register') }}" class="nav-link primary">Crear cuenta</a>
  @endif
@endsection

@section('content')
  @if (session('status'))
    <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-2">
      {{ session('status') }}
    </div>
  @endif

  <h1>Bienvenido de vuelta</h1>
  <p class="sub">Inicia sesión en tu cuenta</p>

  <form method="POST" action="{{ route('login') }}" class="space-y-4">
    @csrf

    <div>
      <label for="email">Correo electrónico</label>
      <input
        id="email"
        type="email"
        name="email"
        value="{{ old('email') }}"
        required
        autofocus
        autocomplete="username"
        placeholder="tu@correo.com"
        class="@error('email') is-invalid @enderror"
      >
      @error('email')
        <p class="invalid-feedback">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="password">Contraseña</label>
      <input
        id="password"
        type="password"
        name="password"
        required
        autocomplete="current-password"
        placeholder="••••••••"
        class="@error('password') is-invalid @enderror"
      >
      @error('password')
        <p class="invalid-feedback">{{ $message }}</p>
      @enderror
    </div>

    <div class="flex items-center justify-between">
      <label for="remember_me" class="checkbox-wrap text-sm font-medium text-gray-700 cursor-pointer">
        <input id="remember_me" type="checkbox" name="remember">
        <span>Recordarme</span>
      </label>
      @if (Route::has('password.request'))
        <a class="link" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
      @endif
    </div>

    <button type="submit" class="btn-submit">Iniciar sesión</button>
  </form>

  <div class="flex items-center gap-3 my-6">
    <div class="h-px flex-1 bg-gray-200"></div>
    <span class="text-xs text-gray-400">o</span>
    <div class="h-px flex-1 bg-gray-200"></div>
  </div>

  @if (Route::has('register'))
    <p class="text-sm text-gray-600 text-center">
      ¿Aún no tienes cuenta?
      <a href="{{ route('register') }}" class="link">Crear cuenta</a>
    </p>
  @endif
@endsection
