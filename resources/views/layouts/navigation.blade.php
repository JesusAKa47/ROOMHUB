<nav x-data="{ open: false }" class="nav-roomhub" :class="{ 'nav-mobile-open': open }">
    <div class="nav-wrap">
        <div class="nav-inner">
            <div class="nav-left">
                <a href="{{ route('dashboard') }}" class="nav-logo">
                    <x-roomhub-logo class="nav-logo-img" :white="false" />
                    <span class="nav-logo-text">RoomHub</span>
                </a>
                <div class="nav-links">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        {{ __('Inicio') }}
                    </a>
                    @if(Auth::user()->canRent() || Auth::user()->client_id)
                        <a href="{{ route('cuartos.index') }}" class="nav-link nav-link-cta {{ request()->routeIs('cuartos.index') ? 'active' : '' }}">
                            <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            {{ __('Explorar') }}
                        </a>
                        <a href="{{ route('cuartos.mapa') }}" class="nav-link {{ request()->routeIs('cuartos.mapa') ? 'active' : '' }}">
                            <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                            {{ __('Mapa') }}
                        </a>
                    @endif
                    @if(Auth::user()->canBecomeHost())
                        <a href="{{ route('become-host.show') }}" class="nav-link {{ request()->routeIs('become-host.*') ? 'active' : '' }}">{{ __('Ser anfitrión') }}</a>
                    @endif
                    @if(Auth::user()->isOwner())
                        <a href="{{ route('owner.dashboard') }}" class="nav-link {{ request()->routeIs('owner.*') ? 'active' : '' }}">{{ __('Mis alojamientos') }}</a>
                    @endif
                    @if(Auth::user()->isOwner() || Auth::user()->isClient())
                        <a href="{{ route('messages.index') }}" class="nav-link {{ request()->routeIs('messages.*') ? 'active' : '' }}">{{ __('Mensajes') }}</a>
                    @endif
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.index') }}" class="nav-link nav-link-admin {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                            <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ __('Admin') }}
                        </a>
                    @endif
                </div>
            </div>

            <div class="nav-right">
                @php $unreadNav = (int) ($unreadNotificationsCount ?? 0); @endphp
                <a href="{{ route('notifications.index') }}" class="nav-icon-btn nav-icon-btn-bell" aria-label="{{ $unreadNav > 0 ? __('Notificaciones') . ' (' . $unreadNav . ' ' . __('pendientes') . ')' : __('Notificaciones') }}" title="{{ $unreadNav > 0 ? $unreadNav . ' ' . __('notificaciones pendientes') : __('Notificaciones') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @if($unreadNav > 0)
                        <span class="nav-notif-badge" aria-hidden="true">{{ $unreadNav > 99 ? '99+' : $unreadNav }}</span>
                    @endif
                </a>
                @if(!Auth::user()->hasVerifiedEmail() && Auth::user()->isOwner())
                    <a href="{{ route('profile.edit') }}" class="nav-verify">{{ __('Verificar cuenta') }}</a>
                @endif
                <x-dropdown align="right" width="48" contentClasses="py-1 bg-white nav-dropdown-content">
                    <x-slot name="trigger">
                        <button type="button" class="nav-user-btn">
                            @if(Auth::user()->avatarUrl())
                                <img src="{{ Auth::user()->avatarUrl() }}" alt="" class="nav-user-avatar nav-user-avatar-img">
                            @else
                                <span class="nav-user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            @endif
                            <span class="nav-user-name">{{ Auth::user()->name }}</span>
                            <svg class="nav-user-chevron" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Perfil') }}</x-dropdown-link>
                        @if(!Auth::user()->hasVerifiedEmail() && Auth::user()->isOwner())
                            <x-dropdown-link :href="route('profile.edit') . '#verificar-correo'" class="text-amber-600 font-medium">
                                <span class="nav-dropdown-item">
                                    <svg class="nav-dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    {{ __('Verificar correo electrónico') }}
                                </span>
                            </x-dropdown-link>
                        @endif
                        <x-dropdown-link :href="route('notifications.index')">
                            <span class="nav-dropdown-item">
                                <svg class="nav-dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                {{ __('Notificaciones') }} @if($unreadNav > 0)<span class="nav-dropdown-badge">({{ $unreadNav }})</span>@endif
                            </span>
                        </x-dropdown-link>
                        @if(Auth::user()->canRent() || Auth::user()->client_id)
                            <x-dropdown-link :href="route('favorites.index')">
                                <span class="nav-dropdown-item">
                                    <svg class="nav-dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    {{ __('Mis favoritos') }}
                                </span>
                            </x-dropdown-link>
                        @endif
                        @if(Auth::user()->client_id)
                            <x-dropdown-link :href="route('reservations.history')">{{ __('Historial de rentas') }}</x-dropdown-link>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Cerrar sesión') }}</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                <button type="button" @click="open = ! open" class="nav-hamburger" aria-label="Menú">
                    <svg width="24" height="24" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="nav-mobile" :class="{ 'open': open }" x-show="open" x-transition style="display: none;">
        <div class="nav-mobile-links">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">{{ __('Inicio') }}</a>
            @if(Auth::user()->canRent() || Auth::user()->client_id)
                <a href="{{ route('cuartos.index') }}" class="{{ request()->routeIs('cuartos.index') ? 'active' : '' }}">{{ __('Explorar alojamientos') }}</a>
                <a href="{{ route('cuartos.mapa') }}" class="{{ request()->routeIs('cuartos.mapa') ? 'active' : '' }}">{{ __('Ver en mapa') }}</a>
            @endif
            @if(Auth::user()->canBecomeHost())
                <a href="{{ route('become-host.show') }}" class="{{ request()->routeIs('become-host.*') ? 'active' : '' }}">{{ __('Ser anfitrión') }}</a>
            @endif
            @if(Auth::user()->isOwner())
                <a href="{{ route('owner.dashboard') }}" class="{{ request()->routeIs('owner.*') ? 'active' : '' }}">{{ __('Mis alojamientos') }}</a>
            @endif
            @if(Auth::user()->isOwner() || Auth::user()->isClient())
                <a href="{{ route('messages.index') }}" class="{{ request()->routeIs('messages.*') ? 'active' : '' }}">{{ __('Mensajes') }}</a>
            @endif
            <a href="{{ route('notifications.index') }}" class="{{ request()->routeIs('notifications.*') ? 'active' : '' }}">{{ __('Notificaciones') }} @if($unreadNav > 0)<span>({{ $unreadNav }})</span>@endif</a>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.index') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">{{ __('Admin') }}</a>
            @endif
        </div>
        <div class="nav-mobile-user">
            <div class="nav-mobile-user-name">{{ Auth::user()->name }}</div>
            <div class="nav-mobile-user-email">{{ Auth::user()->email }}</div>
            @if(!Auth::user()->hasVerifiedEmail() && Auth::user()->isOwner())
                <a href="{{ route('profile.edit') }}" class="nav-mobile-verify">{{ __('Verificar cuenta') }}</a>
            @endif
            <div class="nav-mobile-actions">
                <a href="{{ route('profile.edit') }}">{{ __('Perfil') }}</a>
                @if(Auth::user()->client_id)
                    <a href="{{ route('reservations.history') }}">{{ __('Historial de rentas') }}</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-mobile-logout">{{ __('Cerrar sesión') }}</button>
                </form>
            </div>
        </div>
    </div>
</nav>
