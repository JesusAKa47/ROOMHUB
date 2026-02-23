{{-- Nav alineado con paleta café: barra clara, CTA destacado, acentos cálidos --}}
<style>
    .nav-roomhub {
        position: relative;
        z-index: 1100;
        background: #fff;
        border-bottom: 1px solid #E8E2DA;
        box-shadow: 0 1px 3px rgba(111,78,55,.06);
        color: #1C1917;
    }
    .nav-roomhub a { color: inherit; text-decoration: none; transition: color .15s ease, background .15s ease, border-color .15s ease; }
    /* Especificidad alta para que Bootstrap/Tailwind en admin no sobrescriban la barra */
    .nav-roomhub .nav-wrap { max-width: 80rem; margin: 0 auto; padding: 0 1.25rem; }
    @media (min-width: 1024px) { .nav-roomhub .nav-wrap { padding: 0 2rem; } }
    .nav-roomhub .nav-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 4rem;
        gap: 1rem;
    }
    @media (min-width: 768px) { .nav-roomhub .nav-inner { min-height: 4.5rem; } }
    .nav-roomhub .nav-left { display: flex; align-items: center; flex: 1; min-width: 0; gap: 1.5rem; }
    .nav-roomhub .nav-logo {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.35rem 0;
        border-radius: 0.5rem;
        flex-shrink: 0;
    }
    .nav-roomhub .nav-logo:hover { opacity: .88; }
    .nav-roomhub .nav-logo-img { height: 2rem; width: auto; }
    .nav-roomhub .nav-logo-text { font-size: 1.25rem; font-weight: 700; color: #6F4E37; letter-spacing: -0.02em; }
    .nav-roomhub .nav-links {
        display: none;
        align-items: center;
        gap: 0.5rem;
    }
    @media (min-width: 640px) { .nav-roomhub .nav-links { display: flex; } }
    .nav-roomhub .nav-link-admin { white-space: nowrap; }
    .nav-roomhub .nav-link {
        display: inline-flex !important;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 0.875rem !important;
        border-radius: 9999px !important;
        font-size: 0.9375rem !important;
        font-weight: 500 !important;
        color: #6B5344 !important;
        border: none !important;
        background: transparent !important;
    }
    .nav-roomhub .nav-link:hover { color: #6F4E37 !important; background: #FAF6F0 !important; }
    .nav-roomhub .nav-link.active { color: #6F4E37 !important; background: rgba(111,78,55,.1) !important; }
    .nav-roomhub .nav-link-icon { width: 1.125rem; height: 1.125rem; flex-shrink: 0; }
    .nav-roomhub .nav-link-cta {
        background: #6F4E37 !important;
        color: #fff !important;
    }
    .nav-roomhub .nav-link-cta:hover { background: #5a3d2c !important; color: #fff !important; }
    .nav-roomhub .nav-link-cta.active { background: #4A3728 !important; color: #fff !important; }
    .nav-roomhub .nav-right {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0;
    }
    @media (min-width: 640px) { .nav-roomhub .nav-right { gap: 0.75rem; } }
    .nav-roomhub .nav-icon-btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 9999px;
        color: #6B5344;
        transition: background .15s ease, color .15s ease;
    }
    .nav-roomhub .nav-icon-btn:hover { background: #FAF6F0; color: #6F4E37; }
    .nav-roomhub .nav-icon-btn svg { width: 1.25rem; height: 1.25rem; }
    .nav-roomhub .nav-verify {
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.8125rem;
        font-weight: 600;
        background: #DC2626;
        color: #fff !important;
    }
    .nav-roomhub .nav-verify:hover { background: #B91C1C; }
    .nav-roomhub .nav-user-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.5rem 0.25rem 0.75rem;
        height: 2.5rem;
        border: 1px solid #E8E2DA;
        border-radius: 9999px;
        background: #fff;
        color: #1C1917;
        font-size: 0.9375rem;
        font-weight: 500;
        cursor: pointer;
        transition: box-shadow .2s ease, border-color .2s ease;
    }
    .nav-roomhub .nav-user-btn:hover { border-color: #D4C4B0; box-shadow: 0 2px 8px rgba(111,78,55,.08); }
    .nav-roomhub .nav-user-avatar {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        background: #6F4E37;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
    }
    .nav-roomhub .nav-user-avatar-img {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        object-fit: cover;
        display: block;
    }
    .nav-roomhub .nav-user-name { display: none; }
    @media (min-width: 640px) { .nav-roomhub .nav-user-name { display: inline; } }
    .nav-roomhub .nav-user-chevron { display: none; width: 1rem; height: 1rem; color: #6B5344; }
    @media (min-width: 640px) { .nav-roomhub .nav-user-chevron { display: block; } }
    .nav-roomhub .nav-hamburger {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        padding: 0;
        border: none;
        border-radius: 9999px;
        background: transparent;
        color: #6B5344;
        cursor: pointer;
        transition: background .15s ease;
    }
    .nav-roomhub .nav-hamburger:hover { background: #FAF6F0; color: #6F4E37; }
    @media (min-width: 640px) { .nav-roomhub .nav-hamburger { display: none; } }
    .nav-roomhub .nav-icon-btn-bell { position: relative; overflow: visible; }
    .nav-roomhub .nav-notif-badge {
        position: absolute;
        top: -2px;
        right: -2px;
        min-width: 1.25rem;
        height: 1.25rem;
        padding: 0 5px;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        background: #6F4E37;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 1px 3px rgba(0,0,0,.2);
    }

    /* Dropdown: paleta café (también en página mapa) */
    .nav-roomhub .relative > div.absolute {
        background: #fff !important;
        color: #1C1917;
        border-radius: 0.75rem;
        box-shadow: 0 10px 40px rgba(111,78,55,.12), 0 0 0 1px #E8E2DA;
        padding: 0;
        min-width: 14rem;
        z-index: 1101;
    }
    .nav-roomhub .relative > div.absolute > div,
    .nav-roomhub .nav-dropdown-content {
        border: none !important;
        border-radius: 0.75rem;
        box-shadow: none;
        padding: 0.5rem 0;
        background: transparent !important;
    }
    .nav-roomhub .relative > div.absolute a,
    .nav-roomhub .nav-dropdown-content a {
        color: #1C1917 !important;
        padding: 0.5rem 1rem !important;
        display: flex !important;
        align-items: center !important;
        font-size: 0.9375rem !important;
        transition: background .1s ease, color .1s ease;
    }
    .nav-roomhub .relative > div.absolute a:hover,
    .nav-roomhub .nav-dropdown-content a:hover { background: #FAF6F0 !important; color: #6F4E37 !important; }
    .nav-dropdown-item { display: inline-flex; align-items: center; gap: 0.5rem; }
    .nav-dropdown-icon { width: 1.125rem; height: 1.125rem; flex-shrink: 0; }
    .nav-dropdown-badge { font-size: 0.75rem; font-weight: 600; color: #6B5344; margin-left: 0.25rem; }

    /* Menú móvil */
    .nav-roomhub .nav-mobile {
        display: none;
        padding: 1rem 0;
        border-top: 1px solid #E8E2DA;
        background: #FAF6F0;
    }
    .nav-roomhub .nav-mobile.open { display: block; }
    @media (min-width: 640px) { .nav-roomhub .nav-mobile { display: none !important; } }
    .nav-mobile-links { padding: 0.5rem 0; }
    .nav-mobile-links a {
        display: block;
        padding: 0.75rem 1.25rem;
        border-radius: 0.5rem;
        font-size: 0.9375rem;
        font-weight: 500;
        color: #1C1917;
        margin: 0 0.5rem;
        transition: background .15s ease, color .15s ease;
    }
    .nav-mobile-links a:hover { background: rgba(111,78,55,.1); color: #6F4E37; }
    .nav-mobile-links a.active { background: rgba(111,78,55,.15); color: #6F4E37; }
    .nav-mobile-links a span { font-weight: 600; }
    .nav-mobile-user {
        padding: 1.25rem 1.25rem;
        border-top: 1px solid #E8E2DA;
        background: #fff;
    }
    .nav-mobile-user-name { font-weight: 600; font-size: 1rem; color: #1C1917; }
    .nav-mobile-user-email { font-size: 0.875rem; color: #6B5344; margin-top: 0.25rem; }
    .nav-mobile-verify {
        display: block;
        margin-top: 0.75rem;
        padding: 0.75rem;
        text-align: center;
        border-radius: 0.5rem;
        background: #DC2626;
        color: #fff !important;
        font-weight: 600;
        font-size: 0.875rem;
    }
    .nav-mobile-actions { margin-top: 0.75rem; }
    .nav-mobile-actions a {
        display: block;
        padding: 0.5rem 0;
        font-size: 0.9375rem;
        font-weight: 500;
        color: #6F4E37;
    }
    .nav-mobile-actions a:hover { text-decoration: underline; }
    .nav-mobile-logout {
        display: block;
        width: 100%;
        padding: 0.5rem 0;
        margin-top: 0.25rem;
        border: none;
        background: transparent;
        font-size: 0.9375rem;
        font-weight: 500;
        color: #6B5344;
        cursor: pointer;
        text-align: left;
    }
    .nav-mobile-logout:hover { color: #6F4E37; text-decoration: underline; }
</style>
