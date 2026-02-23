{{-- Estilos: barra de iconos (oscura) + panel expandible --}}
<style>
    .app-layout-with-sidebar { display: flex; min-height: 100vh; }
    .app-layout-with-sidebar .app-main { flex: 1; min-width: 0; display: flex; flex-direction: column; margin-left: 4.5rem; }
    @media (min-width: 1024px) { .app-layout-with-sidebar .app-main { margin-left: 0; } }
    .app-layout-with-sidebar .app-main main { flex: 1; }

    .app-sidebar { display: flex; position: fixed; left: 0; top: 0; bottom: 0; z-index: 40; }
    @media (min-width: 1024px) { .app-sidebar { position: sticky; top: 0; height: 100vh; flex-shrink: 0; align-self: flex-start; } }

    /* Barra estrecha de iconos (fondo oscuro) */
    .sidebar-icons {
        width: 4.5rem;
        flex-shrink: 0;
        background: #1a1d24;
        color: rgba(255,255,255,.9);
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0.75rem 0;
        border-right: 1px solid rgba(255,255,255,.06);
    }
    .sidebar-logo {
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
        border-radius: 0.5rem;
        color: #fff;
        transition: opacity .15s;
    }
    .sidebar-logo:hover { opacity: .9; }
    .sidebar-logo-svg { height: 1.75rem; width: auto; }
    .sidebar-logo img { height: 1.75rem; width: auto; display: block; }
    .sidebar-nav { flex: 1; display: flex; flex-direction: column; gap: 0.25rem; width: 100%; align-items: center; }
    .sidebar-icon {
        width: 2.75rem;
        height: 2.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        color: rgba(255,255,255,.75);
        transition: background .15s, color .15s;
        border: none;
        background: transparent;
        cursor: pointer;
        text-decoration: none;
    }
    .sidebar-icon svg { width: 1.25rem; height: 1.25rem; }
    .sidebar-icon:hover { color: #fff; background: rgba(255,255,255,.08); }
    .sidebar-icon.active {
        color: #fff;
        background: rgba(111,78,55,.5);
        border-left: 3px solid #A67C52;
        border-radius: 0 0.5rem 0.5rem 0;
    }
    .sidebar-bottom { padding-top: 1rem; border-top: 1px solid rgba(255,255,255,.08); margin-top: auto; }
    .sidebar-avatar-initial {
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 50%;
        background: #6F4E37;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
    }
    .sidebar-badge {
        position: absolute;
        bottom: -2px;
        right: -2px;
        min-width: 1.125rem;
        height: 1.125rem;
        padding: 0 4px;
        border-radius: 9999px;
        background: #A67C52;
        color: #fff;
        font-size: 0.65rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Panel expandible (submenú) */
    .sidebar-panel {
        width: 16rem;
        flex-shrink: 0;
        background: #22262e;
        color: rgba(255,255,255,.9);
        overflow-y: auto;
        border-right: 1px solid rgba(255,255,255,.06);
        box-shadow: 4px 0 24px rgba(0,0,0,.2);
    }
    .sidebar-panel-inner { padding: 1rem 0; }
    .sidebar-panel-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0 1rem 1rem;
        border-bottom: 1px solid rgba(255,255,255,.08);
    }
    .sidebar-panel-back {
        width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: transparent;
        color: rgba(255,255,255,.8);
        cursor: pointer;
        border-radius: 0.5rem;
        font-size: 1.25rem;
        transition: background .15s, color .15s;
    }
    .sidebar-panel-back:hover { background: rgba(255,255,255,.08); color: #fff; }
    .sidebar-panel-title { margin: 0; font-size: 1rem; font-weight: 600; color: #fff; }
    .sidebar-panel-nav { padding: 0.75rem 0; }
    .sidebar-panel-section {
        display: block;
        padding: 0.5rem 1rem 0.25rem;
        font-size: 0.6875rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        color: rgba(255,255,255,.45);
    }
    .sidebar-panel-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 1rem;
        margin: 0 0.5rem;
        border-radius: 0.5rem;
        color: rgba(255,255,255,.85);
        text-decoration: none;
        font-size: 0.9375rem;
        transition: background .15s, color .15s;
    }
    .sidebar-panel-link:hover { background: rgba(255,255,255,.08); color: #fff; }
    .sidebar-panel-link.active {
        background: rgba(111,78,55,.35);
        color: #fff;
        border-left: 3px solid #A67C52;
    }
    .sidebar-panel-link svg { flex-shrink: 0; }
    .sidebar-panel-badge {
        margin-left: auto;
        padding: 0.15rem 0.4rem;
        border-radius: 9999px;
        background: #6F4E37;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Móvil: sidebar como overlay */
    @media (max-width: 1023px) {
        .app-sidebar { position: fixed; top: 0; left: 0; right: auto; bottom: 0; }
        .sidebar-panel { position: fixed; left: 4.5rem; top: 0; bottom: 0; z-index: 45; }
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 44;
        }
    }
    @media (min-width: 1024px) {
        .sidebar-overlay { display: none !important; }
    }
</style>
