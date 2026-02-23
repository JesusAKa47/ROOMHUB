@php
  $featuredApartments = \App\Models\Apartment::with('owner')
    ->withAvg('comments as rating_avg', 'rating')
    ->where('status', 'activo')
    ->orderByDesc('rating_avg')
    ->orderByDesc('id')
    ->take(10)
    ->get();
  $featuredForJs = $featuredApartments->map(function ($apt) {
    $photos = is_array($apt->photos) ? $apt->photos : (json_decode($apt->photos, true) ?: []);
    return [
      'id' => $apt->id,
      'title' => $apt->title,
      'monthly_rent' => $apt->monthly_rent,
      'address' => $apt->address,
      'available_from' => $apt->available_from?->format('d/m/Y') ?? '',
      'owner_name' => $apt->owner?->name ?? '',
      'photos' => $photos,
      'show_url' => route('cuartos.show', $apt),
      'rating_avg' => round($apt->rating_avg ?? 0, 1),
      'files_base' => rtrim(url('files'), '/'),
    ];
  })->values()->all();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RoomHub</title>

  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'roomhub-primary': '#6F4E37',
            'roomhub-secondary': '#A67C52',
            'roomhub-accent': '#52796F',
            'roomhub-red': '#DC2626',
          },
          fontFamily: { sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui'] }
        }
      }
    };
  </script>
  <style>
    .welcome-page { background: #FAF6F0; color: #1C1917; min-height: 100vh; font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
    /* Misma barra que el resto de la app: fondo blanco, acentos café */
    .welcome-nav { background: #fff; border-bottom: 1px solid #E8E2DA; box-shadow: 0 1px 3px rgba(111,78,55,.06); color: #1C1917; }
    .welcome-nav .nav-wrap { max-width: 80rem; margin: 0 auto; padding: 0 1.25rem; }
    @media (min-width: 1024px) { .welcome-nav .nav-wrap { padding: 0 2rem; } }
    .welcome-nav .nav-inner { display: flex; justify-content: space-between; align-items: center; min-height: 4rem; gap: 1rem; }
    @media (min-width: 768px) { .welcome-nav .nav-inner { min-height: 4.5rem; } }
    .welcome-nav .nav-logo { display: inline-flex; align-items: center; gap: 0.5rem; font-size: 1.25rem; font-weight: 700; color: #6F4E37; text-decoration: none; transition: opacity .15s; letter-spacing: -0.02em; }
    .welcome-nav .nav-logo:hover { opacity: .88; }
    .welcome-nav .nav-logo img, .welcome-nav .nav-logo svg { height: 2rem; width: auto; display: block; }
    .welcome-nav .nav-links { display: flex; align-items: center; gap: 0.25rem; }
    .welcome-nav .nav-link { padding: 0.5rem 0.875rem; border-radius: 9999px; font-size: 0.9375rem; font-weight: 500; text-decoration: none; color: #6B5344; transition: color .15s, background .15s; }
    .welcome-nav .nav-link:hover { color: #6F4E37; background: #FAF6F0; }
    .welcome-nav .nav-link.outline { color: #6B5344; background: transparent; }
    .welcome-nav .nav-link.outline:hover { color: #6F4E37; background: rgba(111,78,55,.1); }
    .welcome-nav .nav-link.primary { background: #6F4E37; color: #fff; }
    .welcome-nav .nav-link.primary:hover { background: #5a3d2c; color: #fff; }

    .welcome-hero { max-width: 72rem; margin: 0 auto; padding: 3rem 1.25rem 4rem; }
    .hero-logo { margin-bottom: 1rem; }
    .hero-logo img { max-width: 100%; height: auto; display: block; }
    @media (min-width: 1024px) { .welcome-hero { padding: 4rem 2rem 5rem; } }
    .hero-grid { display: grid; gap: 2.5rem; align-items: center; }
    @media (min-width: 1024px) { .hero-grid { grid-template-columns: 1fr 1fr; gap: 4rem; } }
    .hero-content { }
    .kicker { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.12em; color: #6B5344; margin-bottom: 0.75rem; }
    .hero-content h1 { font-size: 2rem; font-weight: 700; line-height: 1.2; color: #1C1917; margin: 0 0 1rem; letter-spacing: -0.02em; }
    @media (min-width: 640px) { .hero-content h1 { font-size: 2.5rem; } }
    @media (min-width: 1024px) { .hero-content h1 { font-size: 2.75rem; } }
    .hero-content h1 .accent { color: #6F4E37; }
    .hero-content .lead { font-size: 1.0625rem; line-height: 1.65; color: #64748B; max-width: 28rem; margin: 0 0 1.75rem; }
    .btn-wrap { display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 2rem; }
    .btn { display: inline-flex; align-items: center; padding: 0.75rem 1.5rem; border-radius: 0.625rem; font-size: 0.9375rem; font-weight: 600; text-decoration: none; transition: background .15s, border-color .15s, color .15s; }
    .btn-primary { background: #6F4E37; color: #fff; border: none; }
    .btn-primary:hover { background: #4A3728; }
    .btn-secondary { background: #fff; color: #0F172A; border: 1px solid #e2e8f0; }
    .btn-secondary:hover { background: #F8F8F9; border-color: #A67C52; color: #6F4E37; }

    .features { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; }
    .feature-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1rem; text-align: center; box-shadow: 0 1px 2px rgba(0,0,0,.04); transition: box-shadow .15s, border-color .15s; }
    .feature-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.06); border-color: #cbd5e1; }
    .feature-card .icon { width: 2rem; height: 2rem; margin: 0 auto 0.5rem; border-radius: 0.5rem; background: rgba(111,78,55,.1); display: flex; align-items: center; justify-content: center; }
    .feature-card .icon svg { color: #6F4E37; }
    .feature-card .label { font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.06em; color: #64748B; margin-bottom: 0.125rem; }
    .feature-card .value { font-size: 0.8125rem; font-weight: 600; color: #0F172A; }

    .welcome-preview { background: #fff; border: 1px solid #e2e8f0; border-radius: 1rem; box-shadow: 0 4px 16px rgba(0,0,0,.06); overflow: hidden; }
    .welcome-preview .preview-header { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #FAFBFC; }
    .welcome-preview .preview-title { font-size: 0.9375rem; font-weight: 600; color: #0F172A; margin: 0; }
    .welcome-preview .badge { font-size: 0.6875rem; font-weight: 600; padding: 0.25rem 0.5rem; border-radius: 9999px; background: #d1fae5; color: #065f46; text-transform: uppercase; letter-spacing: 0.05em; }
    .welcome-preview .preview-body { padding: 1.25rem; }
    .welcome-preview .img-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; margin-bottom: 1rem; }
    .welcome-preview .img-thumbs-row { grid-column: 1 / -1; display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; }
    .welcome-preview .img-main { grid-column: 1 / -1; aspect-ratio: 16/9; border-radius: 0.625rem; overflow: hidden; background: #f1f5f9; }
    .welcome-preview .img-main img { width: 100%; height: 100%; object-fit: cover; }
    .welcome-preview .img-thumb { aspect-ratio: 4/3; border-radius: 0.5rem; overflow: hidden; background: #f1f5f9; }
    .welcome-preview .img-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .welcome-preview .img-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 0.8125rem; }
    .welcome-preview .apt-title { font-size: 1rem; font-weight: 600; color: #0F172A; margin-bottom: 0.25rem; }
    .welcome-preview .apt-price { font-size: 1.25rem; font-weight: 700; color: #6F4E37; }
    .welcome-preview .apt-price span { font-size: 0.875rem; font-weight: 400; color: #64748B; }
    .welcome-preview .apt-meta { font-size: 0.8125rem; color: #64748B; margin-top: 0.375rem; line-height: 1.4; }
    .welcome-preview .preview-cta { display: inline-block; margin-top: 1rem; font-size: 0.875rem; font-weight: 600; color: #6F4E37; text-decoration: none; }
    .welcome-preview .preview-cta:hover { color: #4A3728; text-decoration: underline; }
    .welcome-preview .empty-state { text-align: center; padding: 2.5rem 1rem; color: #64748B; font-size: 0.9375rem; }

    .welcome-trust { max-width: 72rem; margin: 0 auto; padding: 3rem 1.25rem 4rem; border-top: 1px solid #e2e8f0; background: #fff; }
    .welcome-trust .trust-title { font-size: 1.25rem; font-weight: 700; color: #0F172A; text-align: center; margin: 0 0 2rem; }
    .welcome-trust .trust-grid { display: grid; gap: 1.5rem; grid-template-columns: 1fr; }
    @media (min-width: 640px) { .welcome-trust .trust-grid { grid-template-columns: repeat(3, 1fr); } }
    .welcome-trust .trust-item { text-align: center; padding: 1.5rem 1rem; }
    .welcome-trust .trust-icon { width: 3rem; height: 3rem; margin: 0 auto 1rem; border-radius: 0.75rem; background: rgba(111,78,55,.1); display: flex; align-items: center; justify-content: center; }
    .welcome-trust .trust-icon svg { color: #6F4E37; }
    .welcome-trust .trust-item h3 { font-size: 1rem; font-weight: 600; color: #0F172A; margin: 0 0 0.375rem; }
    .welcome-trust .trust-item p { font-size: 0.875rem; color: #64748B; margin: 0; line-height: 1.5; }

    .footer-roomhub { background: #6F4E37; color: rgba(255,255,255,.85); }
    .footer-roomhub .footer-wrap { max-width: 72rem; margin: 0 auto; padding: 3rem 1.25rem 2rem; }
    .footer-roomhub .footer-grid { display: grid; gap: 2rem; grid-template-columns: 1fr; }
    @media (min-width: 640px) { .footer-roomhub .footer-grid { grid-template-columns: 1.5fr 1fr 1fr; } }
    @media (min-width: 1024px) { .footer-roomhub .footer-grid { grid-template-columns: 2fr 1fr 1fr 1fr; gap: 2.5rem; } }
    .footer-roomhub .footer-brand .logo-link { display: inline-flex; align-items: center; gap: 0.5rem; color: #F8F8F9; font-size: 1.125rem; font-weight: 700; margin-bottom: 0.75rem; text-decoration: none; transition: color .15s; }
    .footer-roomhub .footer-brand .logo-link:hover { color: #fff; }
    .footer-roomhub .footer-brand .logo-link img { height: 2.25rem; width: auto; object-fit: contain; display: block; border-radius: 0.375rem; border: 1px solid rgba(255,255,255,.2); box-shadow: 0 1px 2px rgba(0,0,0,.2); }
    .footer-roomhub .footer-brand p { font-size: 0.875rem; line-height: 1.6; color: rgba(255,255,255,.75); margin: 0; max-width: 20rem; }
    .footer-roomhub .footer-col h4 { color: #F8F8F9; font-size: 0.8125rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; margin: 0 0 0.75rem; }
    .footer-roomhub .footer-col ul { list-style: none; padding: 0; margin: 0; }
    .footer-roomhub .footer-col li { margin-bottom: 0.5rem; }
    .footer-roomhub .footer-col a { color: rgba(255,255,255,.75); text-decoration: none; font-size: 0.875rem; transition: color .15s; }
    .footer-roomhub .footer-col a:hover { color: #fff; }
    .footer-roomhub .footer-bottom { border-top: 1px solid rgba(255,255,255,.15); margin-top: 2rem; padding-top: 1.5rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; }
    .footer-roomhub .footer-bottom .copy { font-size: 0.8125rem; color: rgba(255,255,255,.6); margin: 0; }
    .footer-roomhub .footer-bottom .legal { display: flex; gap: 1rem; }
    .footer-roomhub .footer-bottom .legal a { color: rgba(255,255,255,.6); font-size: 0.8125rem; text-decoration: none; }
    .footer-roomhub .footer-bottom .legal a:hover { color: #fff; }
  </style>
</head>

<body class="welcome-page antialiased">

  <header class="welcome-nav">
    <div class="nav-wrap">
      <div class="nav-inner">
        <a href="{{ url('/') }}" class="nav-logo">
          <x-roomhub-logo class="nav-logo-img" :white="false" />
          <span>RoomHub</span>
        </a>
        <div class="nav-links">
          @auth
            @if(Auth::user()->isAdmin())
              <a href="{{ route('admin.index') }}" class="nav-link outline">Admin</a>
            @endif
            <a href="{{ route('dashboard') }}" class="nav-link primary">Ir al Dashboard</a>
          @else
            @if (Route::has('login'))
              <a href="{{ route('login') }}" class="nav-link outline">Iniciar sesión</a>
            @endif
            @if (Route::has('register'))
              <a href="{{ route('register') }}" class="nav-link primary">Crear cuenta</a>
            @endif
          @endauth
        </div>
      </div>
    </div>
  </header>

  <main>
    <section class="welcome-hero">
      <div class="hero-grid">
        <div class="hero-content">
          @if(file_exists(public_path('images/roomhub-logo-full.png')))
          <div class="hero-logo">
            <x-roomhub-logo class="h-12 w-auto md:h-14" :full="true" />
          </div>
          @endif
          <p class="kicker">Bienvenido a RoomHub</p>
          <h1>
            Tu espacio para
            <span class="accent">rentar por día o por mes</span>
            con seguridad.
          </h1>
          <p class="lead">
            Encuentra alojamientos, reserva con pagos seguros y gestiona tus propiedades desde un solo lugar.
          </p>
          <div class="btn-wrap">
            @auth
              <a href="{{ route('cuartos.index') }}" class="btn btn-primary">Explorar alojamientos</a>
              @if(Auth::user()->isOwner())
                <a href="{{ route('owner.dashboard') }}" class="btn btn-secondary">Mis alojamientos</a>
              @endif
              @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.apartments.index') }}" class="btn btn-secondary">Panel inmuebles</a>
              @endif
            @else
              <a href="{{ route('register') }}" class="btn btn-primary">Crear cuenta gratis</a>
              <a href="{{ route('login') }}" class="btn btn-secondary">Iniciar sesión</a>
            @endauth
          </div>
          <div class="features">
            <div class="feature-card">
              <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
              </div>
              <div class="label">Publicación</div>
              <div class="value">Fotos y estado</div>
            </div>
            <div class="feature-card">
              <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
              </div>
              <div class="label">Pagos</div>
              <div class="value">Día o mes</div>
            </div>
            <div class="feature-card">
              <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
              </div>
              <div class="label">Gestión</div>
              <div class="value">Reservas y dueños</div>
            </div>
          </div>
        </div>

        <div class="welcome-preview">
          <div class="preview-header">
            <h3 class="preview-title">Vista previa</h3>
            <span class="badge">Activo</span>
          </div>
          <div class="preview-body" id="welcome-preview-body">
            @if ($featuredApartments->isEmpty())
              <div class="empty-state">
                Aún no hay alojamientos publicados. Crea una cuenta y sé el primero en publicar.
              </div>
            @else
              <div id="welcome-preview-slide">
                @php $first = $featuredApartments->first(); $photos = is_array($first->photos) ? $first->photos : (json_decode($first->photos, true) ?: []); @endphp
                <div class="img-grid">
                  <div class="img-main">
                    @if(!empty($photos))
                      <img src="{{ url('files/' . $photos[0]) }}" alt="{{ $first->title }}" id="welcome-img-main">
                    @else
                      <div class="img-placeholder" id="welcome-img-main">Sin imagen</div>
                    @endif
                  </div>
                  <div class="img-thumbs-row">
                    @foreach(array_slice($photos, 1, 3) as $photo)
                      <div class="img-thumb">
                        <img src="{{ url('files/' . $photo) }}" alt="{{ $first->title }}">
                      </div>
                    @endforeach
                    @for ($i = count($photos); $i < 4; $i++)
                      <div class="img-thumb"><div class="img-placeholder">—</div></div>
                    @endfor
                  </div>
                </div>
                <div class="apt-title" id="welcome-apt-title">{{ $first->title }}</div>
                <div class="apt-price" id="welcome-apt-price">${{ number_format($first->monthly_rent, 0) }}<span>/mes</span></div>
                <div class="apt-meta" id="welcome-apt-meta">
                  Disponible desde {{ $first->available_from->format('d/m/Y') }} · {{ $first->address }}
                </div>
                @if($first->owner)
                  <div class="apt-meta" id="welcome-apt-owner">Anfitrión: {{ $first->owner->name }}</div>
                @else
                  <div class="apt-meta" id="welcome-apt-owner" style="display:none;"></div>
                @endif
                <a href="{{ route('cuartos.show', $first) }}" class="preview-cta" id="welcome-preview-link">Ver alojamiento →</a>
              </div>
              @if($featuredApartments->count() > 1)
                <script>
                  document.addEventListener('DOMContentLoaded', function() {
                    var apartments = @json($featuredForJs);
                    var index = 0;
                    var body = document.getElementById('welcome-preview-body');
                    var thumbsRow = body && body.querySelector('.img-thumbs-row');
                    var aptTitle = document.getElementById('welcome-apt-title');
                    var aptPrice = document.getElementById('welcome-apt-price');
                    var aptMeta = document.getElementById('welcome-apt-meta');
                    var aptOwner = document.getElementById('welcome-apt-owner');
                    var previewLink = document.getElementById('welcome-preview-link');

                    function renderSlide(apt) {
                      var photos = apt.photos || [];
                      var base = apt.files_base || (window.location.origin + '/files');
                      var imgMainEl = document.getElementById('welcome-img-main');
                      if (imgMainEl) {
                        if (photos.length) {
                          imgMainEl.outerHTML = '<img src="' + (base + '/' + photos[0]) + '" alt="' + (apt.title || '').replace(/"/g, '&quot;') + '" id="welcome-img-main">';
                        } else {
                          imgMainEl.outerHTML = '<div class="img-placeholder" id="welcome-img-main">Sin imagen</div>';
                        }
                      }
                      if (thumbsRow) {
                        var html = '';
                        for (var i = 0; i < 3; i++) {
                          if (photos[i + 1]) {
                            html += '<div class="img-thumb"><img src="' + (base + '/' + photos[i + 1]) + '" alt="' + (apt.title || '').replace(/"/g, '&quot;') + '"></div>';
                          } else {
                            html += '<div class="img-thumb"><div class="img-placeholder">—</div></div>';
                          }
                        }
                        thumbsRow.innerHTML = html;
                      }
                      if (aptTitle) aptTitle.textContent = apt.title || '';
                      if (aptPrice) aptPrice.innerHTML = '$' + (apt.monthly_rent || 0).toLocaleString('es-MX') + '<span>/mes</span>';
                      if (aptMeta) aptMeta.textContent = 'Disponible desde ' + apt.available_from + ' · ' + (apt.address || '');
                      if (aptOwner) {
                        aptOwner.textContent = apt.owner_name ? 'Anfitrión: ' + apt.owner_name : '';
                        aptOwner.style.display = apt.owner_name ? '' : 'none';
                      }
                      if (previewLink) previewLink.href = apt.show_url || '#';
                    }

                    setInterval(function() {
                      index = (index + 1) % apartments.length;
                      renderSlide(apartments[index]);
                    }, 5000);
                  });
                </script>
              @endif
            @endif
          </div>
        </div>
      </div>
    </section>

    <section class="welcome-trust" aria-label="Por qué RoomHub">
      <h2 class="trust-title">Simple, seguro y pensado para ti</h2>
      <div class="trust-grid">
        <div class="trust-item">
          <div class="trust-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
          </div>
          <h3>Pagos seguros</h3>
          <p>Reserva y paga con total confianza. Tus datos están protegidos.</p>
        </div>
        <div class="trust-item">
          <div class="trust-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
          </div>
          <h3>Flexibilidad</h3>
          <p>Renta por día o por mes según lo que necesites.</p>
        </div>
        <div class="trust-item">
          <div class="trust-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-7.5v.008"/></svg>
          </div>
          <h3>Atención directa</h3>
          <p>Habla con anfitriones y soporte cuando lo necesites.</p>
        </div>
      </div>
    </section>
  </main>

  <footer class="footer-roomhub" role="contentinfo">
    <div class="footer-wrap">
      <div class="footer-grid">
        <div class="footer-brand">
          <a href="{{ url('/') }}" class="logo-link">
            <x-roomhub-logo class="block h-8 w-auto" />
            <span>RoomHub</span>
          </a>
          <p>Encuentra tu espacio. Alquila por día o por mes con total seguridad.</p>
        </div>
        <div class="footer-col">
          <h4>Navegación</h4>
          <ul>
            <li><a href="{{ url('/') }}">Inicio</a></li>
            @auth
              <li><a href="{{ route('cuartos.index') }}">Explorar</a></li>
            @endauth
            <li><a href="{{ route('quienes') }}">Quiénes somos</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Legal</h4>
          <ul>
            <li><a href="{{ url('/') }}">Términos de uso</a></li>
            <li><a href="{{ url('/') }}">Aviso de privacidad</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Contacto</h4>
          <ul>
            <li><a href="mailto:contacto@roomhub.com">contacto@roomhub.com</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p class="copy">&copy; {{ date('Y') }} RoomHub. Todos los derechos reservados.</p>
        <div class="legal">
          <a href="{{ url('/') }}">Términos</a>
          <a href="{{ url('/') }}">Privacidad</a>
        </div>
      </div>
    </div>
  </footer>

</body>
</html>
