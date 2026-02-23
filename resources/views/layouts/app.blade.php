<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'RoomHub') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('layouts.navigation-styles')
</head>
<body class="font-sans antialiased global-loading bg-roomhub-bg {{ request()->routeIs('cuartos.mapa') ? 'page-mapa' : '' }}">
    <x-global-loader />

    @include('layouts.navigation')

    @isset($header)
        @if(trim((string) $header) !== '')
        <header class="bg-roomhub-card shadow-sm border-b border-roomhub-border">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif
    @endisset

    <main>
        {{ $slot }}
    </main>

    @unless(request()->routeIs('cuartos.mapa'))
        @include('layouts.footer')
    @endunless

    {{-- SweetAlert2 para confirmaciones profesionales (eliminar, etc.) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.body.addEventListener('submit', function(e) {
        var form = e.target;
        var msg = form.getAttribute('data-confirm');
        if (!msg) return;
        e.preventDefault();
        Swal.fire({
          title: form.getAttribute('data-confirm-title') || '¿Estás seguro?',
          text: msg,
          icon: form.getAttribute('data-confirm-icon') || 'question',
          showCancelButton: true,
          confirmButtonColor: '#6F4E37',
          cancelButtonColor: '#6b7280',
          confirmButtonText: 'Sí',
          cancelButtonText: 'Cancelar'
        }).then(function(result) { if (result.isConfirmed) form.submit(); });
      });
    });
    </script>
    {{-- Estilos locales (nav, cuartos, etc.) — al final para que @push desde includes ya se haya ejecutado --}}
    @stack('styles')
    @stack('scripts')
    <script>
        (function(){ document.addEventListener('DOMContentLoaded', function(){ document.body.classList.remove('global-loading'); }); })();
    </script>
</body>
</html>
