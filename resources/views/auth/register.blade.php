@extends('layouts.auth')

@section('title', 'RoomHub | Crear cuenta')

@section('nav-link')
  <a href="{{ route('login') }}" class="nav-link outline">Iniciar sesión</a>
@endsection

@push('styles')
<style>
  .auth-card .msf-wrap { margin: -0.5rem 0 0; }
  .auth-card .stepper-wrap { position: relative; padding: 0 0 1.5rem; margin-bottom: 1rem; }
  .auth-card .stepper-row { display: flex; align-items: center; justify-content: space-between; font-size: 0.8125rem; gap: 0.5rem; flex-wrap: wrap; }
  .auth-card .stepper-row > div { display: flex; align-items: center; gap: 0.75rem; }
  .auth-card .step-dot { height: 2rem; width: 2rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; transition: background .25s, border-color .25s; border: 1px solid rgba(255,255,255,.3); }
  .auth-card .step-dot.active { background: rgba(166,124,82,.35); border-color: rgba(255,255,255,.5); }
  .auth-card .step-dot.pending { background: rgba(255,255,255,.1); border-color: rgba(255,255,255,.2); }
  .auth-card .step-info { display: none; }
  @media (min-width: 640px) { .auth-card .step-info { display: block; } }
  .auth-card .step-info .title { font-weight: 600; color: rgba(255,255,255,.95); }
  .auth-card .step-info .sub { font-size: 0.75rem; color: rgba(255,255,255,.6); }
  .auth-card .progress-track { position: absolute; left: 0; right: 0; top: 3.25rem; height: 4px; background: rgba(255,255,255,.15); border-radius: 999px; overflow: hidden; }
  .auth-card .progress-fill { height: 100%; background: linear-gradient(90deg, #A67C52, #6F4E37); border-radius: 999px; transition: width .4s ease; }
  .auth-card .step-panel { display: none; }
  .auth-card .step-panel.active { display: block; }
  .auth-card .step-panel .input-wrap { position: relative; }
  .auth-card .step-panel .input-wrap svg { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); width: 1.25rem; height: 1.25rem; color: rgba(255,255,255,.5); pointer-events: none; }
  .auth-card .step-panel .input-wrap input,
  .auth-card .step-panel .input-wrap select { padding-left: 2.5rem; }
  .auth-card .step-panel .input-wrap select { appearance: none; cursor: pointer; }
  .auth-card .step-panel select option { background: #374151; color: #fff; }
  .auth-card .review-box { background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.2); border-radius: 0.75rem; padding: 1rem; margin-bottom: 1rem; }
  .auth-card .review-box dt { color: rgba(255,255,255,.6); font-size: 0.8125rem; }
  .auth-card .review-box dd { color: rgba(255,255,255,.95); font-weight: 500; margin: 0 0 0.5rem; }
  .auth-card .review-box dd:last-child { margin-bottom: 0; }
  .auth-card .review-box dl { display: grid; grid-template-columns: auto 1fr; gap: 0.25rem 1rem; align-items: baseline; }
  .auth-card .review-box dt { grid-column: 1; }
  .auth-card .review-box dd { grid-column: 2; margin: 0; }
  .auth-card .msf-actions { display: flex; align-items: center; justify-content: space-between; padding-top: 1rem; gap: 0.75rem; flex-wrap: wrap; }
  .auth-card .msf-actions .btn-back { padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; background: rgba(255,255,255,.2); color: #fff; border: 1px solid rgba(255,255,255,.3); cursor: pointer; }
  .auth-card .msf-actions .btn-back:hover:not(:disabled) { background: rgba(255,255,255,.3); }
  .auth-card .msf-actions .btn-back:disabled { opacity: .5; cursor: not-allowed; }
  .auth-card .msf-actions .btn-next { padding: 0.5rem 1.25rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; background: #6F4E37; color: #fff; border: none; cursor: pointer; }
  .auth-card .msf-actions .btn-next:hover { background: #5a3d2c; }
  .auth-card .msf-actions .btn-submit-msf { padding: 0.5rem 1.25rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; background: #52796F; color: #fff; border: none; cursor: pointer; }
  .auth-card .msf-actions .btn-submit-msf:hover { background: #456b62; }
  .auth-card .msf-actions .btn-submit-msf:disabled { opacity: .7; cursor: wait; }
  .auth-card .msf-actions-next { display: flex; gap: 0.5rem; }
  .auth-card .msf-success { display: none; text-align: center; padding: 1.5rem 0; }
  .auth-card .msf-success.visible { display: block; }
  .auth-card .msf-success .icon-wrap { width: 3rem; height: 3rem; margin: 0 auto 1rem; border-radius: 50%; background: rgba(82,121,111,.3); display: flex; align-items: center; justify-content: center; }
  .auth-card .msf-success .icon-wrap svg { width: 1.5rem; height: 1.5rem; color: #8BBDAD; }
  .auth-card .msf-success h3 { font-size: 1.25rem; font-weight: 700; color: #fff; margin: 0 0 0.25rem; }
  .auth-card .msf-success p { font-size: 0.875rem; color: rgba(255,255,255,.8); margin: 0; }
  .auth-card [data-error] { font-size: 0.75rem; color: #fecaca; margin-top: 0.25rem; }
  .auth-card [data-error].hidden { display: none !important; }
</style>
@endpush

@section('content')
  <div class="msf-wrap">
    <h1>Crear cuenta</h1>
    <p class="sub">Regístrate en unos pasos. Serás cliente para rentar y luego podrás ser anfitrión.</p>

    <!-- Stepper -->
    <div class="stepper-wrap">
      <div class="stepper-row">
        <div>
          <div class="step-dot active" data-step="0"><span>1</span></div>
          <div class="step-info">
            <div class="title">Cuenta</div>
            <div class="sub">Correo y contraseña</div>
          </div>
        </div>
        <div>
          <div class="step-dot pending" data-step="1"><span>2</span></div>
          <div class="step-info">
            <div class="title">Perfil</div>
            <div class="sub">Tu nombre</div>
          </div>
        </div>
        <div>
          <div class="step-dot pending" data-step="2"><span>3</span></div>
          <div class="step-info">
            <div class="title">Ubicación</div>
            <div class="sub">Código postal y más</div>
          </div>
        </div>
        <div>
          <div class="step-dot pending" data-step="3"><span>4</span></div>
          <div class="step-info">
            <div class="title">Revisar</div>
            <div class="sub">Confirmar y enviar</div>
          </div>
        </div>
      </div>
      <div class="progress-track">
        <div id="msf-progress" class="progress-fill" style="width: 0%"></div>
      </div>
    </div>

    <form id="msf" method="POST" action="{{ route('register') }}" class="space-y-4" novalidate>
      @csrf

      <!-- Paso 1: Cuenta -->
      <section class="step-panel active" data-step="0">
        <div class="space-y-4">
          <div>
            <label for="email" class="block text-sm font-medium mb-1">Correo electrónico</label>
            <div class="input-wrap">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L20 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required placeholder="tu@correo.com" class="w-full @error('email') is-invalid @enderror" />
            </div>
            <p class="hidden" data-error="email">Ingresa un correo válido.</p>
            @error('email')<p class="invalid-feedback">{{ $message }}</p>@enderror
          </div>
          <div>
            <label for="password" class="block text-sm font-medium mb-1">Contraseña</label>
            <div class="input-wrap">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
              <input id="password" name="password" type="password" minlength="8" required autocomplete="new-password" placeholder="Mín. 8 caracteres" class="w-full @error('password') is-invalid @enderror" />
            </div>
            <p class="hidden" data-error="password">La contraseña debe tener al menos 8 caracteres.</p>
            @error('password')<p class="invalid-feedback">{{ $message }}</p>@enderror
          </div>
          <div>
            <label for="password_confirmation" class="block text-sm font-medium mb-1">Confirmar contraseña</label>
            <div class="input-wrap">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
              <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="Repite la contraseña" />
            </div>
            <p class="hidden" data-error="password_confirmation">Las contraseñas no coinciden.</p>
          </div>
          <label class="checkbox-wrap inline-flex items-start gap-2 text-sm cursor-pointer">
            <input id="tos1" type="checkbox" class="mt-0.5" style="accent-color: #A67C52;" required />
            <span>Acepto los <a href="{{ url('/') }}" class="link">términos de uso</a> y el aviso de privacidad.</span>
          </label>
          <p class="hidden" data-error="tos1">Debes aceptar los términos para continuar.</p>
        </div>
      </section>

      <!-- Paso 2: Perfil -->
      <section class="step-panel" data-step="1">
        <div class="space-y-4">
          <div>
            <label for="name" class="block text-sm font-medium mb-1">Nombre completo</label>
            <div class="input-wrap">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              <input id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name" placeholder="Ej. Ana Pérez" class="w-full @error('name') is-invalid @enderror" />
            </div>
            <p class="hidden" data-error="name">Ingresa tu nombre.</p>
            @error('name')<p class="invalid-feedback">{{ $message }}</p>@enderror
          </div>
          <div>
            <label for="phone" class="block text-sm font-medium mb-1">Teléfono</label>
            <div class="input-wrap">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
              <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required placeholder="Ej. 961 172 4435" class="w-full @error('phone') is-invalid @enderror" />
            </div>
            <p class="hidden" data-error="phone">Ingresa un teléfono válido (mín. 7 dígitos).</p>
            @error('phone')<p class="invalid-feedback">{{ $message }}</p>@enderror
          </div>
          <div>
            <label for="gender" class="block text-sm font-medium mb-1">Género</label>
            <div class="input-wrap">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              <select id="gender" name="gender" required class="w-full @error('gender') is-invalid @enderror">
                <option value="">Selecciona</option>
                <option value="hombre" {{ old('gender') === 'hombre' ? 'selected' : '' }}>Hombre</option>
                <option value="mujer" {{ old('gender') === 'mujer' ? 'selected' : '' }}>Mujer</option>
                <option value="otro" {{ old('gender', 'otro') === 'otro' ? 'selected' : '' }}>Otro</option>
              </select>
            </div>
            @error('gender')<p class="invalid-feedback">{{ $message }}</p>@enderror
          </div>
          <div>
            <label for="birthdate" class="block text-sm font-medium mb-1">Fecha de nacimiento (opcional)</label>
            <div class="input-wrap">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              <input id="birthdate" name="birthdate" type="date" value="{{ old('birthdate') }}" class="w-full @error('birthdate') is-invalid @enderror" />
            </div>
            @error('birthdate')<p class="invalid-feedback">{{ $message }}</p>@enderror
          </div>
        </div>
      </section>

      <!-- Paso 3: Ubicación -->
      <section class="step-panel" data-step="2">
        <div class="space-y-4">
          <p class="text-sm text-white/80 mb-2">Opcional. Puedes completarlo después en tu perfil.</p>
          <div>
            <label for="postal_code" class="block text-sm font-medium mb-1">Código postal</label>
            <div class="input-wrap">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              <input id="postal_code" name="postal_code" type="text" value="{{ old('postal_code') }}" placeholder="Ej. 29950" maxlength="10" class="w-full @error('postal_code') is-invalid @enderror" />
            </div>
            @error('postal_code')<p class="invalid-feedback">{{ $message }}</p>@enderror
          </div>
          <div>
            <label for="state" class="block text-sm font-medium mb-1">Estado</label>
            <input id="state" name="state" type="text" value="{{ old('state') }}" placeholder="Ej. Chiapas" maxlength="100" class="w-full @error('state') is-invalid @enderror" />
            @error('state')<p class="invalid-feedback">{{ $message }}</p>@enderror
          </div>
          <div>
            <label for="city" class="block text-sm font-medium mb-1">Ciudad</label>
            <input id="city" name="city" type="text" value="{{ old('city') }}" placeholder="Ej. Ocosingo" maxlength="100" class="w-full @error('city') is-invalid @enderror" />
            @error('city')<p class="invalid-feedback">{{ $message }}</p>@enderror
          </div>
          <div>
            <label for="municipality" class="block text-sm font-medium mb-1">Municipio</label>
            <input id="municipality" name="municipality" type="text" value="{{ old('municipality') }}" placeholder="Ej. Ocosingo" maxlength="100" class="w-full @error('municipality') is-invalid @enderror" />
            @error('municipality')<p class="invalid-feedback">{{ $message }}</p>@enderror
          </div>
          <div>
            <label for="locality" class="block text-sm font-medium mb-1">Localidad</label>
            <input id="locality" name="locality" type="text" value="{{ old('locality') }}" placeholder="Ej. Ocosingo" maxlength="150" class="w-full @error('locality') is-invalid @enderror" />
            @error('locality')<p class="invalid-feedback">{{ $message }}</p>@enderror
          </div>
        </div>
      </section>

      <!-- Paso 4: Revisar -->
      <section class="step-panel" data-step="3">
        <div class="space-y-4">
          <div class="review-box">
            <h3 class="font-semibold text-white mb-2">Revisa tus datos</h3>
            <dl class="grid gap-1 text-sm">
              <dt class="text-white/60">Correo</dt>
              <dd id="r-email" class="font-medium text-white/95">—</dd>
              <dt class="text-white/60">Nombre</dt>
              <dd id="r-name" class="font-medium text-white/95">—</dd>
              <dt class="text-white/60">Teléfono</dt>
              <dd id="r-phone" class="font-medium text-white/95">—</dd>
              <dt class="text-white/60">Género</dt>
              <dd id="r-gender" class="font-medium text-white/95">—</dd>
              <dt class="text-white/60">Fecha de nacimiento</dt>
              <dd id="r-birthdate" class="font-medium text-white/95">—</dd>
              <dt class="text-white/60">Ubicación</dt>
              <dd id="r-location" class="font-medium text-white/95">—</dd>
            </dl>
          </div>
          <label class="inline-flex items-start gap-3 text-sm cursor-pointer">
            <input id="consent" type="checkbox" class="mt-1" style="accent-color: #A67C52;" required />
            <span>Confirmo que los datos son correctos y acepto el tratamiento según la política de privacidad.</span>
          </label>
          <p class="hidden" data-error="consent">Debes confirmar para continuar.</p>
        </div>
      </section>

      <!-- Acciones -->
      <div class="msf-actions">
        <button type="button" id="msf-back" class="btn-back" disabled>Atrás</button>
        <div class="msf-actions-next">
          <button type="button" id="msf-next" class="btn-next">Siguiente</button>
          <button type="submit" id="msf-submit" class="btn-submit-msf hidden">Registrarse</button>
        </div>
      </div>
    </form>

    <!-- Estado éxito (oculto hasta envío real; si hay redirect no se ve) -->
    <div id="msf-success" class="msf-success">
      <div class="icon-wrap">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
      </div>
      <h3>¡Listo!</h3>
      <p>Tu cuenta se ha creado correctamente.</p>
    </div>
  </div>

  <div class="flex items-center gap-3 my-6">
    <div class="h-px flex-1 bg-white/20"></div>
    <span class="text-xs text-white/50">o</span>
    <div class="h-px flex-1 bg-white/20"></div>
  </div>

  <p class="text-sm text-center text-white/80">
    ¿Ya tienes cuenta?
    <a href="{{ route('login') }}" class="link">Iniciar sesión</a>
  </p>

  <script>
  (function() {
    var form = document.getElementById('msf');
    var panels = form ? form.querySelectorAll('.step-panel') : [];
    var backBtn = document.getElementById('msf-back');
    var nextBtn = document.getElementById('msf-next');
    var submitBtn = document.getElementById('msf-submit');
    var progressEl = document.getElementById('msf-progress');
    var dots = document.querySelectorAll('.step-dot');
    var successEl = document.getElementById('msf-success');
    var current = 0;
    var totalSteps = panels.length;

    function toggleError(key, show) {
      var el = document.querySelector('[data-error="' + key + '"]');
      if (!el) return;
      el.classList.toggle('hidden', !show);
    }

    var rules = {
      0: function() {
        var email = form.email.value.trim();
        var pass = form.password.value;
        var pass2 = form.password_confirmation.value;
        var tos = document.getElementById('tos1').checked;
        var validEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        var validPass = pass.length >= 8;
        var validPass2 = pass === pass2;
        toggleError('email', !validEmail);
        toggleError('password', !validPass);
        toggleError('password_confirmation', !validPass2);
        toggleError('tos1', !tos);
        return validEmail && validPass && validPass2 && tos;
      },
      1: function() {
        var name = form.name.value.trim().length >= 2;
        var phone = (form.phone.value || '').replace(/\D/g, '');
        var validPhone = phone.length >= 7;
        var gender = (form.gender && form.gender.value) ? true : false;
        toggleError('name', !name);
        toggleError('phone', !validPhone);
        return name && validPhone && gender;
      },
      2: function() { return true; },
      3: function() {
        var consent = document.getElementById('consent').checked;
        toggleError('consent', !consent);
        return consent;
      }
    };

    function showStep(i) {
      panels.forEach(function(p, idx) {
        p.classList.toggle('active', idx === i);
      });
      backBtn.disabled = i === 0;
      nextBtn.classList.toggle('hidden', i === totalSteps - 1);
      submitBtn.classList.toggle('hidden', i !== totalSteps - 1);
      var pct = totalSteps > 1 ? (i / (totalSteps - 1)) * 100 : 100;
      if (progressEl) progressEl.style.width = pct + '%';
      dots.forEach(function(d, idx) {
        d.classList.toggle('active', idx <= i);
        d.classList.toggle('pending', idx > i);
      });
      if (i === 3) {
        var rEmail = document.getElementById('r-email');
        var rName = document.getElementById('r-name');
        var rPhone = document.getElementById('r-phone');
        var rGender = document.getElementById('r-gender');
        var rBirthdate = document.getElementById('r-birthdate');
        var rLocation = document.getElementById('r-location');
        if (rEmail) rEmail.textContent = form.email.value || '—';
        if (rName) rName.textContent = form.name.value || '—';
        if (rPhone) rPhone.textContent = form.phone.value || '—';
        if (rGender) {
          var g = form.gender && form.gender.options[form.gender.selectedIndex];
          rGender.textContent = g ? g.text : '—';
        }
        if (rBirthdate) rBirthdate.textContent = form.birthdate && form.birthdate.value ? form.birthdate.value : '—';
        var locParts = [];
        if (form.postal_code && form.postal_code.value) locParts.push('CP ' + form.postal_code.value);
        if (form.state && form.state.value) locParts.push(form.state.value);
        if (form.city && form.city.value) locParts.push(form.city.value);
        if (form.municipality && form.municipality.value) locParts.push(form.municipality.value);
        if (form.locality && form.locality.value) locParts.push(form.locality.value);
        if (rLocation) rLocation.textContent = locParts.length ? locParts.join(', ') : '—';
      }
    }

    if (nextBtn) nextBtn.addEventListener('click', function() {
      var validate = rules[current];
      if (validate && !validate()) return;
      current = Math.min(current + 1, totalSteps - 1);
      showStep(current);
    });

    if (backBtn) backBtn.addEventListener('click', function() {
      current = Math.max(current - 1, 0);
      showStep(current);
    });

    if (form) form.addEventListener('submit', function(e) {
      var validate = rules[current];
      if (validate && !validate()) {
        e.preventDefault();
        return;
      }
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Enviando…';
      }
    });

    showStep(0);
  })();
  </script>
@endsection
