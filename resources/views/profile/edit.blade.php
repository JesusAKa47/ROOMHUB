<x-app-layout>
    @push('styles')
    <style>
        .profile-page { background: #F8F8F9; min-height: calc(100vh - 8rem); padding: 1.25rem 1rem 2.5rem; }
        .profile-wrap { max-width: 100%; margin: 0 auto; padding: 0 1rem; }
        @media (min-width: 1024px) { .profile-wrap { max-width: 80rem; padding: 0 1.5rem; } }
        .profile-layout { display: grid; gap: 1.5rem; grid-template-columns: 1fr; }
        @media (min-width: 1024px) {
            .profile-layout { grid-template-columns: 22rem 1fr; gap: 2rem; align-items: start; }
        }
        .profile-sidebar { display: flex; flex-direction: column; gap: 1rem; }
        .profile-main { min-width: 0; display: flex; flex-direction: column; gap: 1.25rem; }
        .profile-grid { display: contents; }
        .profile-grid .profile-card { margin-bottom: 0; min-height: 0; }
        .profile-card .profile-card-inner { display: flex; flex-direction: column; }
        .profile-card-span-2 { grid-column: 1 / -1; }
        .profile-header { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem; }
        .profile-header h1 { font-size: 1.5rem; font-weight: 700; color: #6F4E37; margin: 0; letter-spacing: -0.02em; }
        .profile-header .header-icon { width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: rgba(111,78,55,.12); display: flex; align-items: center; justify-content: center; }
        .profile-header .header-icon svg { color: #6F4E37; }
        .profile-quick-links { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0; }
        .profile-form-grid { display: grid; gap: 0.75rem 1.25rem; }
        @media (min-width: 640px) { .profile-form-grid { grid-template-columns: repeat(2, 1fr); } }
        .profile-form-grid .form-group-full { grid-column: 1 / -1; }
        .profile-form-section { margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #E4E4ED; }
        .profile-form-section h3 { font-size: 0.9375rem; font-weight: 600; color: #6F4E37; margin: 0 0 0.5rem; }
        .profile-form-section p { font-size: 0.8125rem; color: #5c5e7a; margin: 0 0 0.5rem; }
        .profile-form .form-group { margin-bottom: 0.75rem; }
        .profile-form .form-group:last-of-type { margin-bottom: 0; }
        .profile-quick-links a { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 0.875rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; color: #6F4E37; background: #fff; border: 1px solid #E4E4ED; text-decoration: none; transition: background .15s, border-color .15s; }
        .profile-quick-links a:hover { background: #FAF6F0; border-color: #D4B896; }
        .profile-quick-links a svg { width: 1rem; height: 1rem; flex-shrink: 0; }
        .profile-card { background: #fff; border-radius: 1rem; border: 1px solid #E4E4ED; box-shadow: 0 1px 3px rgba(111,78,55,.04); margin-bottom: 1.25rem; overflow: hidden; }
        .profile-card-inner { padding: 1.5rem 1.25rem; }
        @media (min-width: 640px) { .profile-card-inner { padding: 1.75rem 1.5rem; } }
        .profile-card h2 { font-size: 1.125rem; font-weight: 700; color: #6F4E37; margin: 0 0 0.25rem; }
        .profile-card .card-desc { font-size: 0.875rem; color: #5c5e7a; margin: 0 0 1.25rem; }
        .profile-card section header p { font-size: 0.875rem; color: #5c5e7a; margin: 0.25rem 0 0; }
        .profile-alert { padding: 0.75rem 1rem; border-radius: 0.5rem; margin-bottom: 1rem; font-size: 0.875rem; }
        .profile-alert.primary { background: rgba(111,78,55,.08); border: 1px solid rgba(111,78,55,.2); color: #5C4033; }
        .profile-alert.success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
        .profile-alert.info { background: rgba(111,78,55,.1); border: 1px solid rgba(111,78,55,.3); color: #5C4033; }
        .profile-mode { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem 1.25rem; border-radius: 0.75rem; border: 1px solid #E4E4ED; margin-bottom: 0.75rem; background: #fff; }
        .profile-mode.highlight { border-color: #D4B896; background: rgba(111,78,55,.06); }
        .profile-mode:last-child { margin-bottom: 0; }
        .profile-mode-title { font-weight: 600; font-size: 0.9375rem; color: #6F4E37; margin: 0; }
        .profile-mode-desc { font-size: 0.8125rem; color: #5c5e7a; margin: 0.25rem 0 0; }
        .profile-mode-badge { display: inline-flex; align-items: center; padding: 0.375rem 0.75rem; border-radius: 9999px; font-size: 0.8125rem; font-weight: 600; background: #d1fae5; color: #065f46; }
        .profile-mode-btn { display: inline-flex; align-items: center; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; color: #fff; background: #6F4E37; text-decoration: none; border: none; cursor: pointer; transition: background .15s, opacity .15s; }
        .profile-mode-btn:hover { background: #4A3728; opacity: .95; }
        .profile-form .form-label { display: block; font-size: 0.875rem; font-weight: 500; color: #6F4E37; margin-bottom: 0.375rem; }
        .profile-form .form-input { width: 100%; padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #E4E4ED; font-size: 0.9375rem; color: #6F4E37; transition: border-color .15s, box-shadow .15s; }
        .profile-form .form-input:focus { outline: none; border-color: #6F4E37; box-shadow: 0 0 0 3px rgba(111,78,55,.15); }
        .profile-form .form-input::placeholder { color: #94a3b8; }
        .profile-form .form-error { font-size: 0.8125rem; color: #dc2626; margin-top: 0.25rem; }
        .profile-form select.form-input { appearance: auto; cursor: pointer; }
        .profile-btn-primary { display: inline-flex; align-items: center; padding: 0.5rem 1.25rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; color: #fff; background: #6F4E37; border: none; cursor: pointer; transition: background .15s; }
        .profile-btn-primary:hover { background: #4A3728; }
        .profile-btn-danger { display: inline-flex; align-items: center; padding: 0.5rem 1.25rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; color: #fff; background: #dc2626; border: none; cursor: pointer; transition: background .15s; }
        .profile-btn-danger:hover { background: #b91c1c; }
        .profile-btn-secondary { display: inline-flex; align-items: center; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; color: #475569; background: #fff; border: 1px solid #cbd5e1; cursor: pointer; transition: background .15s, border-color .15s; }
        .profile-btn-secondary:hover { background: #f8fafc; border-color: #94a3b8; }
        .profile-saved { font-size: 0.875rem; color: #52796F; font-weight: 500; }
        .profile-link { color: #A67C52; font-weight: 500; text-decoration: none; }
        .profile-link:hover { text-decoration: underline; }
        /* Inputs y labels dentro de las cards */
        .profile-card input[type="text"],
        .profile-card input[type="email"],
        .profile-card input[type="password"],
        .profile-card select { width: 100%; padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #E4E4ED; font-size: 0.9375rem; color: #6F4E37; transition: border-color .15s, box-shadow .15s; }
        .profile-card input:focus, .profile-card select:focus { outline: none; border-color: #6F4E37; box-shadow: 0 0 0 3px rgba(111,78,55,.15); }
        .profile-card label { font-size: 0.875rem; font-weight: 500; color: #6F4E37; }
        .profile-card button[type="submit"] { background: #6F4E37 !important; color: #fff !important; border-radius: 0.5rem; border: none; font-weight: 500; }
        .profile-card button[type="submit"]:hover { background: #4A3728 !important; color: #fff !important; }
        .profile-avatar-wrap { width: 96px; height: 96px; border-radius: 50%; overflow: hidden; background: rgba(111,78,55,.12); flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
        .profile-avatar-img { width: 100%; height: 100%; object-fit: cover; }
        .profile-avatar-initial { font-size: 2.25rem; font-weight: 700; color: #6F4E37; }
        .profile-avatar-row { align-items: flex-start; }
        .profile-avatar-form { flex: 1; min-width: 15rem; }
        .profile-avatar-file-wrap { display: flex; align-items: center; flex-wrap: wrap; gap: 0.5rem; }
        .profile-avatar-file-input { position: absolute; width: 0.1px; height: 0.1px; opacity: 0; overflow: hidden; z-index: -1; margin: 0; padding: 0; }
        .profile-avatar-file-btn { display: inline-flex; align-items: center; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; border: 1px solid #cbd5e1; background: #fff; color: #475569; cursor: pointer; flex-shrink: 0; transition: background .15s, border-color .15s; }
        .profile-avatar-file-btn:hover { background: #f8fafc; border-color: #94a3b8; }
        .profile-avatar-file-label { font-size: 0.875rem; color: #64748b; word-break: break-word; }
        .profile-avatar-form button[type="submit"] { padding: 0.375rem 0.875rem; font-size: 0.8125rem; width: auto; align-self: flex-start; border-radius: 0.5rem; }
        .profile-card-compact .profile-card-inner { padding: 1rem 1.25rem; }
        .profile-section-compact header + div { margin-top: 1rem; }
        .profile-list-compact { margin: 0; padding-left: 1.25rem; list-style: disc; line-height: 1.5; }
        .profile-list-compact li { margin-bottom: 0.25rem; }
        .profile-card-compact .card-desc { margin-bottom: 0.75rem; }
        .profile-payment-tips { margin-top: 0.75rem; padding: 0.75rem; border-radius: 0.5rem; background: rgba(111,78,55,.06); border: 1px solid rgba(111,78,55,.12); font-size: 0.8125rem; color: #5c5e7a; line-height: 1.5; }
    </style>
    @endpush
    @push('scripts')
    <script>
    (function() {
      var apiCpUrl = '{{ url("/api/codigo-postal") }}';
      var cpInput = document.getElementById('profile_codigo_postal');
      var stateEl = document.getElementById('profile_field_state');
      var cityEl = document.getElementById('profile_field_city');
      var municipalityEl = document.getElementById('profile_field_municipality');
      var localityEl = document.getElementById('profile_field_locality');
      var datalistColonias = document.getElementById('profile-datalist-colonias');
      if (cpInput && stateEl && cityEl && municipalityEl && localityEl) {
        function onCpLookup() {
          var cp = (cpInput.value || '').replace(/\D/g, '');
          if (cp.length !== 5) return;
          cpInput.disabled = true;
          var msg = document.createElement('span');
          msg.className = 'text-sm text-gray-500';
          msg.style.marginLeft = '0.5rem';
          msg.textContent = 'Buscando...';
          cpInput.parentNode.appendChild(msg);
          fetch(apiCpUrl + '/' + cp, { method: 'GET', headers: { 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
              if (data.ok) {
                if (data.state) stateEl.value = data.state;
                if (data.city) cityEl.value = data.city;
                if (data.municipality) municipalityEl.value = data.municipality;
                if (data.localities && data.localities.length && datalistColonias) {
                  datalistColonias.innerHTML = '';
                  data.localities.forEach(function(col) {
                    var opt = document.createElement('option');
                    opt.value = col;
                    datalistColonias.appendChild(opt);
                  });
                }
                msg.textContent = 'Listo.';
                setTimeout(function() { msg.remove(); }, 2000);
              } else {
                msg.textContent = data.message || 'No encontrado.';
                setTimeout(function() { msg.remove(); }, 3000);
              }
            })
            .catch(function() {
              msg.textContent = 'Error al consultar. Intenta de nuevo.';
              setTimeout(function() { msg.remove(); }, 3000);
            })
            .finally(function() { cpInput.disabled = false; });
        }
        cpInput.addEventListener('blur', onCpLookup);
        cpInput.addEventListener('input', function() {
          if ((cpInput.value || '').replace(/\D/g, '').length === 5) onCpLookup();
        });
      }
    })();
    (function() {
      var input = document.getElementById('avatar-upload');
      var label = document.getElementById('avatar-file-label');
      var trigger = document.getElementById('avatar-upload-trigger');
      if (trigger && input) trigger.addEventListener('click', function() { input.click(); });
      if (input && label) {
        input.addEventListener('change', function() {
          label.textContent = this.files && this.files.length ? this.files[0].name : 'Ningún archivo elegido';
        });
      }
    })();
    </script>
    @endpush

    <div class="profile-page">
        <div class="profile-wrap">
            <header class="profile-header">
                <span class="header-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                </span>
                <div>
                    <h1>Perfil</h1>
                    <nav class="profile-quick-links" aria-label="Accesos rápidos">
                        @if(Auth::user()->canRent() || Auth::user()->client_id)
                            <a href="{{ route('favorites.index') }}"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg> Mis favoritos</a>
                        @endif
                        @if(Auth::user()->client_id)
                            <a href="{{ route('reservations.history') }}"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> Historial de rentas</a>
                        @endif
                        @if(Auth::user()->isOwner() || Auth::user()->isClient())
                            <a href="{{ route('messages.index') }}"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> Mensajes</a>
                        @endif
                        @if(Auth::user()->isOwner())
                            <a href="{{ route('owner.dashboard') }}"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg> Mis alojamientos</a>
                        @endif
                    </nav>
                </div>
            </header>

            <div class="profile-layout">
                <aside class="profile-sidebar">
                <div class="profile-card">
                    <div class="profile-card-inner">
                        @include('profile.partials.update-avatar-form')
                    </div>
                </div>

                @if(!Auth::user()->isAdmin())
                <div id="modos-de-uso" class="profile-card scroll-mt-6">
                    <div class="profile-card-inner">
                        @if(request()->get('activate') === 'client' && !Auth::user()->client_id)
                            <div class="profile-alert primary">
                                Para reservar alojamientos necesitas <strong>activar el Modo Cliente</strong>. Pulsa el botón «Activar modo cliente» de abajo.
                            </div>
                        @endif
                        @if(session('status') && str_contains(session('status'), 'cliente'))
                            <div class="profile-alert success">{{ session('status') }}</div>
                        @endif
                        <h2>Modos de uso</h2>
                        <p class="card-desc">Puedes usar RoomHub como <strong>cliente</strong> (para rentar alojamientos) y como <strong>anfitrión</strong> (para publicar los tuyos). Activa los modos que necesites.</p>
                        <div>
                            <div class="profile-mode {{ !Auth::user()->client_id ? 'highlight' : '' }}">
                                <div>
                                    <p class="profile-mode-title">Modo Cliente</p>
                                    <p class="profile-mode-desc">Explorar y rentar alojamientos</p>
                                </div>
                                <div class="flex-shrink-0">
                                    @if(Auth::user()->client_id)
                                        <span class="profile-mode-badge">✓ Activo</span>
                                    @else
                                        <form method="POST" action="{{ route('profile.activate-client') }}" class="inline">
                                            @csrf
                                            <button type="submit" class="profile-mode-btn">Activar modo cliente</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <div class="profile-mode">
                                <div>
                                    <p class="profile-mode-title">Modo Anfitrión</p>
                                    <p class="profile-mode-desc">Publicar y gestionar tus alojamientos</p>
                                </div>
                                <div class="flex-shrink-0">
                                    @if(Auth::user()->owner_id)
                                        <span class="profile-mode-badge">✓ Activo</span>
                                    @else
                                        <a href="{{ route('become-host.show') }}" class="profile-mode-btn">Convertirse en anfitrión</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if(Auth::user()->hasBothProfiles())
                            <div class="profile-alert info mt-4">
                                <strong>¡Perfecto!</strong> Tienes ambos modos activos. Puedes rentar alojamientos y también publicar los tuyos.
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Verificar correo (solo anfitriones sin verificar) --}}
                @include('profile.partials.verify-email-card')

                {{-- Seguridad y pagos --}}
                <div class="profile-card profile-card-compact">
                    <div class="profile-card-inner">
                        @include('profile.partials.two-factor-form')
                    </div>
                </div>

                @if(Auth::user()->client_id && config('services.stripe.secret'))
                <div class="profile-card profile-card-compact">
                    <div class="profile-card-inner">
                        <h2>Métodos de pago</h2>
                        <p class="card-desc">Gestiona tus tarjetas y métodos de pago de forma segura en Stripe.</p>
                        <ul class="profile-list-compact text-sm text-gray-600 mb-3">
                            <li>Añadir o quitar tarjetas</li>
                            <li>Ver métodos guardados</li>
                            <li>Revisar facturación</li>
                        </ul>
                        <a href="{{ route('profile.payment-methods') }}" class="profile-mode-btn" target="_blank" rel="noopener">Abrir portal de pagos</a>
                        <p class="profile-payment-tips">Se abrirá una ventana segura de Stripe. Al terminar, cierra la pestaña para volver aquí.</p>
                    </div>
                </div>
                @endif

                {{-- Eliminar cuenta --}}
                <div class="profile-card profile-card-compact">
                    <div class="profile-card-inner">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
                </aside>

                <main class="profile-main">
                {{-- Información del perfil y contraseña --}}
                <div class="profile-card">
                    <div class="profile-card-inner">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
                <div class="profile-card">
                    <div class="profile-card-inner">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
                </main>
            </div>
        </div>
    </div>
</x-app-layout>
