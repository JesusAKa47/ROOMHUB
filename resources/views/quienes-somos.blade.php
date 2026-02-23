<x-app-layout>
    @push('styles')
    <style>
        .qs-page { background: #F8F8F9; min-height: 100vh; padding-bottom: 3rem; }
        .qs-wrap { max-width: 56rem; margin: 0 auto; padding: 1.5rem 1rem 2.5rem; }
        @media (min-width: 1024px) { .qs-wrap { padding: 2rem 1.5rem 3rem; } }
        .qs-back { display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 500; color: #5c5e7a; text-decoration: none; margin-bottom: 1.5rem; transition: color .15s; }
        .qs-back:hover { color: #6F4E37; }
        .qs-header { display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 2rem; }
        .qs-header-icon { width: 3rem; height: 3rem; border-radius: 0.75rem; background: rgba(111,78,55,.1); color: #6F4E37; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .qs-header-icon svg { width: 1.5rem; height: 1.5rem; }
        .qs-header h1 { font-size: 1.5rem; font-weight: 700; color: #2C1810; margin: 0; letter-spacing: -0.02em; }
        .qs-header p { font-size: 0.9375rem; color: #5c5e7a; margin: 0.25rem 0 0; line-height: 1.5; }
        .qs-intro { background: #fff; border-radius: 1rem; border: 1px solid #E8E2DA; box-shadow: 0 1px 3px rgba(44,24,16,.04); padding: 1.5rem 1.75rem; margin-bottom: 2rem; }
        .qs-intro p { font-size: 0.9375rem; color: #5c5e7a; line-height: 1.6; margin: 0; }
        .qs-intro strong { color: #2C1810; }
        .qs-grid { display: grid; gap: 1.5rem; grid-template-columns: 1fr; }
        @media (min-width: 640px) { .qs-grid { grid-template-columns: repeat(2, 1fr); } }
        .qs-card { background: #fff; border-radius: 1rem; border: 1px solid #E8E2DA; box-shadow: 0 1px 3px rgba(44,24,16,.04); padding: 1.75rem; text-align: center; transition: box-shadow .2s, border-color .2s; }
        .qs-card:hover { box-shadow: 0 4px 12px rgba(44,24,16,.06); border-color: #D4C4B0; }
        .qs-card-img { width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 3px solid #E8E2DA; margin: 0 auto 1rem; display: block; }
        .qs-card h3 { font-size: 1.0625rem; font-weight: 700; color: #2C1810; margin: 0 0 0.25rem; }
        .qs-card .role { font-size: 0.875rem; font-weight: 600; color: #6F4E37; margin: 0; }
        .qs-card .desc { font-size: 0.8125rem; color: #5c5e7a; margin: 0.5rem 0 1rem; line-height: 1.45; }
        .qs-card .btn-section { display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; background: #6F4E37; color: #fff; text-decoration: none; transition: background .2s; }
        .qs-card .btn-section:hover { background: #4A3728; color: #fff; }
        .qs-footer-link { display: inline-flex; align-items: center; gap: 0.5rem; margin-top: 2rem; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; color: #6F4E37; text-decoration: none; transition: background .15s, color .15s; }
        .qs-footer-link:hover { background: rgba(111,78,55,.08); color: #4A3728; }
    </style>
    @endpush

    <div class="qs-page">
        <div class="qs-wrap">
            <a href="{{ url('/') }}" class="qs-back">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Volver al inicio
            </a>

            <header class="qs-header">
                <span class="qs-header-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </span>
                <div>
                    <h1>Quiénes somos</h1>
                    <p>El equipo detrás de RoomHub. Conoce a quienes hacemos posible esta plataforma.</p>
                </div>
            </header>

            <div class="qs-intro">
                <p>Somos el equipo detrás de <strong>RoomHub</strong>, una plataforma creada para simplificar la gestión de inmuebles, dueños y clientes mediante tecnología moderna y diseño elegante.</p>
            </div>

            <div class="qs-grid">
                <div class="qs-card">
                    <img src="https://scontent-gua1-1.xx.fbcdn.net/v/t39.30808-6/485352658_1056616539838682_3111478714917387969_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=a5f93a&_nc_eui2=AeEZ22fU10RHO9uhWfGkFHTx5pZeLV3V5i3mll4tXdXmLYOC0LZZFL1IB_rYAsrJfgl3yQMEcR4Pa5nMkBolC7N7&_nc_ohc=lKswQXEXTqMQ7kNvwEmG1C7&_nc_oc=AdnQTWjGOpVEkQSOwDLQaXmZeoCG_mkyrK7Zfm7xT4s2-WDtc6hMUcyqnppxa97-tz-M-XUvp2Y52EeFLA1e6vHW&_nc_zt=23&_nc_ht=scontent-gua1-1.xx&_nc_gid=-SXS5BSUl_B_FDD7CoT9gQ&oh=00_AfmkpLJUJwVJnYseDwMiJnE8EBIMh6xtVsdpW8Nis7g52g&oe=693AF4A8" alt="Jesús Acacio" class="qs-card-img">
                    <h3>Jesús Acacio Pérez Jiménez</h3>
                    <p class="role">Desarrollador Backend</p>
                    <p class="desc">Encargado de la lógica en Laravel y base de datos.</p>
                    <a href="{{ url('/admin/apartments') }}" class="btn-section">Ir a Inmuebles</a>
                </div>
                <div class="qs-card">
                    <img src="https://scontent-gua1-1.xx.fbcdn.net/v/t39.30808-6/474199251_623428483544231_5550543627694183911_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=6ee11a&_nc_eui2=AeESvTHq7549ByzZpET6_o9xrymJVSQ31t6vKYlVJDfW3iw_DhVfoQSQcLi-h4iyjGjZ99ZovpUn5NVWyHYEfp69&_nc_ohc=4LjVOEIPRawQ7kNvwHVFBSE&_nc_oc=Adn6sh9EVL154FpglSJYU7tS56XwAp4KN0wdoOxg94edswwtH8qmWbCwe3A1l0wjtKrv5YqiNACFJ1_cVVzfg5Ny&_nc_zt=23&_nc_ht=scontent-gua1-1.xx&_nc_gid=UTB8CVDzmamdyv3HPj75Iw&oh=00_Afm5yT_13v-Z4p_A4r6836_m2DQbvgd4IHu7uezfmcc0Rg&oe=693B017E" alt="Kelvin Alexis" class="qs-card-img">
                    <h3>Kelvin Alexis Cerrato Mazariegos</h3>
                    <p class="role">Diseñador UI/UX</p>
                    <p class="desc">Responsable del diseño y experiencia de usuario en RoomHub.</p>
                    <a href="{{ url('/admin/owners') }}" class="btn-section">Ir a Dueños</a>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('dashboard') }}" class="qs-footer-link">← Volver al Dashboard</a>
            </div>
        </div>
    </div>
</x-app-layout>
