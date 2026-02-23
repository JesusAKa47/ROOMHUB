<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'RoomHub')</title>

  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { 'roomhub-primary': '#6F4E37', 'roomhub-secondary': '#A67C52', 'roomhub-accent': '#52796F', 'roomhub-red': '#DC2626' },
          fontFamily: { sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui'] }
        }
      }
    };
  </script>
  <style>
    .auth-page { color: #111827; min-height: 100vh; display: flex; flex-direction: column; position: relative; }
    .auth-carousel { position: fixed; inset: 0; z-index: 0; overflow: hidden; }
    .auth-carousel .slide { position: absolute; inset: 0; background-size: cover; background-position: center; background-repeat: no-repeat; opacity: 0; transition: opacity 1.2s ease-in-out; }
    .auth-carousel .slide.active { opacity: 1; z-index: 1; }
    .auth-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.55); z-index: 1; }
    /* Nav igual que welcome/app: barra blanca, acentos café */
    .auth-nav { position: relative; z-index: 2; background: #fff; border-bottom: 1px solid #E8E2DA; box-shadow: 0 1px 3px rgba(111,78,55,.06); color: #1C1917; }
    .auth-nav .nav-wrap { max-width: 80rem; margin: 0 auto; padding: 0 1.25rem; }
    @media (min-width: 1024px) { .auth-nav .nav-wrap { padding: 0 2rem; } }
    .auth-nav .nav-inner { display: flex; justify-content: space-between; align-items: center; min-height: 4rem; gap: 1rem; }
    @media (min-width: 768px) { .auth-nav .nav-inner { min-height: 4.5rem; } }
    .auth-nav .nav-logo { display: inline-flex; align-items: center; gap: 0.5rem; font-size: 1.25rem; font-weight: 700; color: #6F4E37; text-decoration: none; letter-spacing: -0.02em; transition: opacity .15s; }
    .auth-nav .nav-logo:hover { opacity: .88; }
    .auth-nav .nav-logo img, .auth-nav .nav-logo svg { height: 2rem; width: auto; display: block; }
    .auth-nav .nav-links { display: flex; align-items: center; gap: 0.25rem; }
    .auth-nav .nav-link { padding: 0.5rem 0.875rem; border-radius: 9999px; font-size: 0.9375rem; font-weight: 500; text-decoration: none; color: #6B5344; transition: color .15s, background .15s; }
    .auth-nav .nav-link:hover { color: #6F4E37; background: #FAF6F0; }
    .auth-nav .nav-link.outline { color: #6B5344; background: transparent; }
    .auth-nav .nav-link.outline:hover { color: #6F4E37; background: rgba(111,78,55,.1); }
    .auth-nav .nav-link.primary { background: #6F4E37; color: #fff; }
    .auth-nav .nav-link.primary:hover { background: #5a3d2c; color: #fff; }
    .auth-main { position: relative; z-index: 2; flex: 1; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem; }
    .auth-card { width: 100%; max-width: 28rem; background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.25); border-radius: 1rem; box-shadow: 0 8px 32px rgba(0,0,0,.25); padding: 2rem; backdrop-filter: blur(16px); }
    @media (min-width: 640px) { .auth-card { padding: 2.5rem; } }
    .auth-card h1 { font-size: 1.5rem; font-weight: 700; color: #fff; margin: 0 0 0.25rem; text-shadow: 0 1px 2px rgba(0,0,0,.3); }
    .auth-card .sub { font-size: 0.875rem; color: rgba(255,255,255,.85); margin-bottom: 1.5rem; }
    .auth-card label { display: block; font-size: 0.875rem; font-weight: 600; color: rgba(255,255,255,.95); margin-bottom: 0.375rem; }
    .auth-card input[type="email"],
    .auth-card input[type="password"],
    .auth-card input[type="text"],
    .auth-card input[type="tel"],
    .auth-card input[type="date"],
    .auth-card select { width: 100%; padding: 0.625rem 0.75rem; border: 1px solid rgba(255,255,255,.35); border-radius: 0.5rem; font-size: 0.9375rem; color: #111827; background: rgba(255,255,255,.95); }
    .auth-card input::placeholder { color: #9ca3af; }
    .auth-card input:focus { outline: none; border-color: #A67C52; box-shadow: 0 0 0 3px rgba(166,124,82,.25); }
    .auth-card input.is-invalid { border-color: #E53935; }
    .auth-card .invalid-feedback { font-size: 0.8125rem; color: #fecaca; margin-top: 0.25rem; }
    .auth-card .btn-submit { width: 100%; padding: 0.75rem 1rem; border-radius: 0.5rem; font-size: 0.9375rem; font-weight: 600; background: #6F4E37; color: #fff; border: none; cursor: pointer; }
    .auth-card .btn-submit:hover { background: #4A3728; }
    .auth-card .link { color: #D4B896; text-decoration: none; font-size: 0.875rem; }
    .auth-card .link:hover { color: #fff; text-decoration: underline; }
    .auth-card .checkbox-wrap { display: inline-flex; align-items: center; gap: 0.5rem; color: rgba(255,255,255,.95); }
    .auth-card .checkbox-wrap input { width: 1rem; height: 1rem; accent-color: #A67C52; }
    .auth-card .text-gray-600 { color: rgba(255,255,255,.8) !important; }
    .auth-card .text-gray-400 { color: rgba(255,255,255,.6) !important; }
    .auth-page .footer-roomhub { position: relative; z-index: 2; }
  </style>
</head>
<body class="auth-page antialiased global-loading">
  <x-global-loader :dark="true" />

  {{-- Carrusel de fondo --}}
  <div class="auth-carousel" id="authCarousel" role="presentation">
    <div class="slide active" style="background-image: url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=1920');"></div>
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=1920');"></div>
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=1920');"></div>
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=1920');"></div>
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=1920');"></div>
  </div>
  <div class="auth-overlay" aria-hidden="true"></div>

  <header class="auth-nav">
    <div class="nav-wrap">
      <div class="nav-inner">
        <a href="{{ url('/') }}" class="nav-logo">
          <x-roomhub-logo class="nav-logo-img" :white="false" />
          <span>RoomHub</span>
        </a>
        <div class="nav-links">@yield('nav-link')</div>
      </div>
    </div>
  </header>

  <main class="auth-main">
    <div class="auth-card">
      @yield('content')
    </div>
  </main>

  @include('layouts.footer')

  @stack('styles')
  <script>
    (function() {
      var carousel = document.getElementById('authCarousel');
      if (!carousel) return;
      var slides = carousel.querySelectorAll('.slide');
      if (slides.length < 2) return;
      var i = 0;
      setInterval(function() {
        slides[i].classList.remove('active');
        i = (i + 1) % slides.length;
        slides[i].classList.add('active');
      }, 5000);
    })();
    document.addEventListener('DOMContentLoaded', function(){ document.body.classList.remove('global-loading'); });
  </script>
</body>
</html>
