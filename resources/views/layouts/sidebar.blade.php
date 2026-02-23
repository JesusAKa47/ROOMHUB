{{-- Estructura tipo imagen: barra de iconos (izq) + panel expandible + contenido --}}
@php
    $unread = (int) ($unreadNotificationsCount ?? 0);
@endphp
<div class="app-sidebar" x-data="{ openPanel: null, mobileOpen: false }" @click.outside="if (window.innerWidth >= 1024) openPanel = null; mobileOpen = false">
    {{-- Barra estrecha de iconos --}}
    <aside class="sidebar-icons">
        <a href="{{ route('dashboard') }}" class="sidebar-icon sidebar-logo" aria-label="RoomHub">
            <x-roomhub-logo class="sidebar-logo-svg" :white="true" />
        </a>
        <nav class="sidebar-nav" aria-label="Principal">
            <a href="{{ route('dashboard') }}" class="sidebar-icon {{ request()->routeIs('dashboard') ? 'active' : '' }}" title="{{ __('Inicio') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </a>
            @if(Auth::user()->canRent() || Auth::user()->client_id)
                <button type="button" class="sidebar-icon {{ request()->routeIs('cuartos.*') && !request()->routeIs('cuartos.mapa') ? 'active' : '' }}" @click="openPanel = openPanel === 'explore' ? null : 'explore'; mobileOpen = true" title="{{ __('Explorar') }}" :aria-expanded="openPanel === 'explore'">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
                <a href="{{ route('cuartos.mapa') }}" class="sidebar-icon {{ request()->routeIs('cuartos.mapa') ? 'active' : '' }}" title="{{ __('Mapa') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                </a>
            @endif
            @if(Auth::user()->isOwner() || Auth::user()->isClient())
                <a href="{{ route('messages.index') }}" class="sidebar-icon {{ request()->routeIs('messages.*') ? 'active' : '' }}" title="{{ __('Mensajes') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </a>
            @endif
            @if(Auth::user()->isOwner())
                <a href="{{ route('owner.dashboard') }}" class="sidebar-icon {{ request()->routeIs('owner.*') ? 'active' : '' }}" title="{{ __('Mis alojamientos') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </a>
            @endif
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.index') }}" class="sidebar-icon {{ request()->routeIs('admin.*') ? 'active' : '' }}" title="{{ __('Admin') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </a>
            @endif
            @if(Auth::user()->client_id)
                <a href="{{ route('reservations.history') }}" class="sidebar-icon {{ request()->routeIs('reservations.*') ? 'active' : '' }}" title="{{ __('Historial') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                </a>
            @endif
            <button type="button" class="sidebar-icon {{ request()->routeIs('profile.*') || request()->routeIs('notifications.*') ? 'active' : '' }}" title="{{ __('Cuenta') }}" @click="openPanel = openPanel === 'account' ? null : 'account'; mobileOpen = true" :aria-expanded="openPanel === 'account'">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </button>
        </nav>
        <div class="sidebar-bottom">
            <a href="{{ route('notifications.index') }}" class="sidebar-icon sidebar-avatar relative" title="{{ __('Notificaciones') }}">
                <span class="sidebar-avatar-initial">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                @if($unread > 0)
                    <span class="sidebar-badge">{{ $unread > 99 ? '99+' : $unread }}</span>
                @endif
            </a>
        </div>
    </aside>

    {{-- Panel expandible (submenús) --}}
    <aside class="sidebar-panel" x-show="openPanel !== null" x-transition style="display: none;">
        <div class="sidebar-panel-inner" x-show="openPanel === 'account'" style="display: none;">
                <div class="sidebar-panel-header">
                    <button type="button" @click="openPanel = null" class="sidebar-panel-back" aria-label="Cerrar">←</button>
                    <h2 class="sidebar-panel-title">{{ __('Cuenta') }}</h2>
                </div>
                <nav class="sidebar-panel-nav">
                    <span class="sidebar-panel-section">CUENTA</span>
                    <a href="{{ route('profile.edit') }}" class="sidebar-panel-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        {{ __('Perfil') }}
                    </a>
                    @if(Auth::user()->client_id)
                        <a href="{{ route('reservations.history') }}" class="sidebar-panel-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                            {{ __('Historial de rentas') }}
                        </a>
                    @endif
                    <a href="{{ route('notifications.index') }}" class="sidebar-panel-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        {{ __('Notificaciones') }} @if($unread > 0)<span class="sidebar-panel-badge">{{ $unread }}</span>@endif
                    </a>
                    <span class="sidebar-panel-section">SESIÓN</span>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="sidebar-panel-link w-full text-left border-0 bg-transparent cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            {{ __('Cerrar sesión') }}
                        </button>
                    </form>
                </nav>
            </div>
        <div class="sidebar-panel-inner" x-show="openPanel === 'explore'" style="display: none;">
                <div class="sidebar-panel-header">
                    <button type="button" @click="openPanel = null" class="sidebar-panel-back" aria-label="Cerrar">←</button>
                    <h2 class="sidebar-panel-title">{{ __('Explorar') }}</h2>
                </div>
                <nav class="sidebar-panel-nav">
                    <span class="sidebar-panel-section">ALOJAMIENTOS</span>
                    <a href="{{ route('cuartos.index') }}" class="sidebar-panel-link {{ request()->routeIs('cuartos.index') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        {{ __('Explorar alojamientos') }}
                    </a>
                    <a href="{{ route('cuartos.mapa') }}" class="sidebar-panel-link {{ request()->routeIs('cuartos.mapa') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        {{ __('Ver en mapa') }}
                    </a>
                    @if(Auth::user()->canBecomeHost())
                        <a href="{{ route('become-host.show') }}" class="sidebar-panel-link {{ request()->routeIs('become-host.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                            {{ __('Ser anfitrión') }}
                        </a>
                    @endif
                </nav>
            </div>
    </aside>

    {{-- Overlay móvil --}}
    <div class="sidebar-overlay" x-show="mobileOpen && openPanel !== null" x-transition @click="mobileOpen = false; openPanel = null" style="display: none;"></div>
</div>
