@push('styles')
<style>
    /* Footer — paleta café */
    .footer-roomhub { background: #6F4E37; color: rgba(255,255,255,.85); }
    .footer-roomhub .footer-wrap { max-width: 80rem; margin: 0 auto; padding: 3rem 1rem 2rem; }
    @media (min-width: 1024px) { .footer-roomhub .footer-wrap { padding: 4rem 2rem 3rem; } }
    .footer-roomhub .footer-grid { display: grid; gap: 2rem; grid-template-columns: 1fr; }
    @media (min-width: 640px) { .footer-roomhub .footer-grid { grid-template-columns: 1.5fr 1fr 1fr; } }
    @media (min-width: 1024px) { .footer-roomhub .footer-grid { grid-template-columns: 2fr 1fr 1fr 1fr; gap: 3rem; } }
    .footer-roomhub .footer-brand { max-width: 20rem; }
    .footer-roomhub .footer-brand .logo-link { display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #F8F8F9; font-size: 1.25rem; font-weight: 700; margin-bottom: 0.75rem; }
    .footer-roomhub .footer-brand .logo-link svg { height: 2rem; width: auto; fill: currentColor; }
    .footer-roomhub .footer-brand .logo-link img { height: 2.25rem; width: auto; object-fit: contain; display: block; border-radius: 0.375rem; border: 1px solid rgba(255,255,255,.2); box-shadow: 0 1px 2px rgba(0,0,0,.2); }
    .footer-roomhub .footer-brand .logo-link:hover { color: #fff; }
    .footer-roomhub .footer-brand p { font-size: 0.875rem; line-height: 1.6; color: rgba(255,255,255,.75); margin: 0; }
    .footer-roomhub .footer-col h4 { color: #F8F8F9; font-size: 0.875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 1rem; }
    .footer-roomhub .footer-col ul { list-style: none; padding: 0; margin: 0; }
    .footer-roomhub .footer-col li { margin-bottom: 0.5rem; }
    .footer-roomhub .footer-col a { color: rgba(255,255,255,.75); text-decoration: none; font-size: 0.875rem; }
    .footer-roomhub .footer-col a:hover { color: #fff; }
    .footer-roomhub .footer-bottom { border-top: 1px solid rgba(255,255,255,.15); margin-top: 2.5rem; padding-top: 1.5rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; }
    .footer-roomhub .footer-bottom .copy { font-size: 0.8125rem; color: rgba(255,255,255,.6); margin: 0; }
    .footer-roomhub .footer-bottom .legal { display: flex; flex-wrap: wrap; gap: 1rem; }
    .footer-roomhub .footer-bottom .legal a { color: rgba(255,255,255,.6); font-size: 0.8125rem; text-decoration: none; }
    .footer-roomhub .footer-bottom .legal a:hover { color: #fff; }
</style>
@endpush

<footer class="footer-roomhub" role="contentinfo">
    <div class="footer-wrap">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="{{ Auth::check() ? route('dashboard') : url('/') }}" class="logo-link">
                    <x-roomhub-logo class="block h-8 w-auto" />
                    <span>RoomHub</span>
                </a>
                <p>Encuentra tu espacio. Alquila por día o por mes con total seguridad y pagos con Stripe.</p>
            </div>
            <div class="footer-col">
                <h4>Navegación</h4>
                <ul>
                    <li><a href="{{ Auth::check() ? route('dashboard') : url('/') }}">Inicio</a></li>
                    @if(Auth::check())
                    <li><a href="{{ route('cuartos.index') }}">Explorar alojamientos</a></li>
                    <li><a href="{{ route('quienes') }}">Quiénes somos</a></li>
                    @else
                    <li><a href="{{ url('/') }}#alojamientos">Alojamientos</a></li>
                    <li><a href="{{ url('/') }}#como-funciona">Cómo funciona</a></li>
                    @endif
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
