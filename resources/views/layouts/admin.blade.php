<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Admin') | RoomHub</title>

<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

@include('layouts.navigation-styles')
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/v/bs5/dt-2.1.8/r-3.0.3/datatables.min.css" rel="stylesheet"/>

<style>
  /* Paleta café: Primario #6F4E37, Secundario #A67C52, Acento #52796F, Fondo #FAF6F0 */
  body.admin-page {
    background: #FAF6F0;
    color: #1C1917;
    min-height: 100vh;
  }
  .admin-page .admin-wrap {
    max-width: 80rem;
    margin: 0 auto;
    padding: 1.5rem 1rem 2rem;
  }
  @media (min-width: 1024px) {
    .admin-page .admin-wrap { padding: 2rem 2rem 2.5rem; }
  }
  .admin-page .admin-panel {
    background: #FFFFFF;
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,.06);
    padding: 1.25rem 1.5rem;
  }
  @media (min-width: 768px) {
    .admin-page .admin-panel { padding: 1.5rem 2rem; }
  }
  .admin-page .admin-card {
    background: #FFFFFF;
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,.06);
    overflow: hidden;
  }
  .admin-page .admin-card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #e2e8f0;
    background: #F8FAFC;
    font-weight: 600;
    color: #0F172A;
  }
  .admin-page .admin-card-body { padding: 1.25rem 1.5rem; }
  .admin-page .admin-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0F172A;
    margin-bottom: 0.5rem;
  }
  .admin-page .admin-subtitle {
    font-size: 0.875rem;
    color: #64748B;
    margin-bottom: 1rem;
  }

  /* Botones: primario azul profundo, hover más oscuro */
  .admin-page .btn-admin-primary {
    background: #6F4E37;
    color: #fff;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 600;
    font-size: 0.875rem;
  }
  .admin-page .btn-admin-primary:hover {
    background: #4A3728;
    color: #fff;
  }
  .admin-page .btn-admin-outline {
    background: #fff;
    color: #0F172A;
    border: 1px solid #cbd5e1;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    font-size: 0.875rem;
  }
  .admin-page .btn-admin-outline:hover {
    background: #F8FAFC;
    color: #0F172A;
    border-color: #94a3b8;
  }
  .admin-page .btn-primary { background: #6F4E37 !important; border-color: #6F4E37 !important; }
  .admin-page .btn-primary:hover { background: #4A3728 !important; border-color: #4A3728 !important; }
  .admin-page .btn-outline-primary { color: #6F4E37; border-color: #6F4E37; }
  .admin-page .btn-outline-primary:hover { background: rgba(111,78,55,.08); color: #4A3728; border-color: #4A3728; }

  /* Tablas */
  .admin-page .table {
    color: #0F172A;
  }
  .admin-page .table thead th {
    background: #F8FAFC;
    border-bottom: 1px solid #e2e8f0;
    color: #64748B;
    font-weight: 600;
    font-size: 0.8125rem;
  }
  .admin-page .table tbody tr:hover {
    background: #F8FAFC;
  }
  .admin-page .table td, .admin-page .table th {
    padding: 0.75rem 1rem;
    vertical-align: middle;
  }
  .admin-page .badge { font-size: 0.75rem; font-weight: 600; }
  .admin-page .form-control, .admin-page .form-select {
    border-color: #cbd5e1;
    border-radius: 0.5rem;
  }
  .admin-page .form-control:focus, .admin-page .form-select:focus {
    border-color: #6F4E37;
    box-shadow: 0 0 0 3px rgba(111,78,55,.15);
  }
  .admin-page .alert-success { background: #ecfdf5; border-color: #6ee7b7; color: #065f46; }
  .admin-page .alert-danger { background: #fef2f2; border-color: #fecaca; color: #b91c1c; }
  .admin-page .alert-warning { background: #fffbeb; border-color: #fde68a; color: #92400e; }
  .admin-page .alert-info { background: #eff6ff; border-color: #93c5fd; color: #1e40af; }
  .required:after { content: " *"; color: #dc3545; }
  .thumb { width: 70px; height: 50px; object-fit: cover; border-radius: 0.375rem; }
</style>

@stack('css')
</head>
<body class="admin-page font-sans antialiased global-loading">
  <x-global-loader />

  @include('layouts.navigation')

  <main class="admin-wrap">
    @if(session('ok'))
      <div class="alert alert-success mb-4">{{ session('ok') }}</div>
    @endif
    @if(session('info'))
      <div class="alert alert-info mb-4">{{ session('info') }}</div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger mb-4">
        <ul class="mb-0 list-unstyled">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="admin-panel">
      @yield('content')
    </div>
  </main>

  @include('layouts.footer')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/v/bs5/dt-2.1.8/r-3.0.3/datatables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    document.body.classList.remove('global-loading');
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
        cancelButtonColor: '#64748B',
        confirmButtonText: 'Sí',
        cancelButtonText: 'Cancelar'
      }).then(function(result) { if (result.isConfirmed) form.submit(); });
    });
  });
  </script>
  @stack('js')
  @stack('styles')
</body>
</html>
