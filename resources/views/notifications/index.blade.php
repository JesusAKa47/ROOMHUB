<x-app-layout>
    @push('styles')
    <style>
        .notif-page { background: #FAF6F0; min-height: calc(100vh - 8rem); padding: 1.5rem 1rem 3rem; }
        .notif-wrap { max-width: 42rem; margin: 0 auto; }
        .notif-header { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; }
        .notif-header h1 { font-size: 1.5rem; font-weight: 700; color: #2C1810; margin: 0; display: flex; align-items: center; gap: 0.5rem; letter-spacing: -0.02em; }
        .notif-header .notif-icon-wrap { width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: rgba(111,78,55,.1); display: flex; align-items: center; justify-content: center; }
        .notif-header .notif-icon-wrap svg { color: #6F4E37; }
        .notif-mark-all { font-size: 0.875rem; font-weight: 500; color: #6F4E37; background: none; border: none; cursor: pointer; padding: 0.375rem 0.75rem; border-radius: 0.5rem; transition: background .15s, color .15s; }
        .notif-mark-all:hover { background: rgba(111,78,55,.08); color: #4A3728; }
        .notif-list { list-style: none; padding: 0; margin: 0; }
        .notif-item { margin-bottom: 0.75rem; }
        .notif-item a { display: block; text-decoration: none; color: inherit; border-radius: 1rem; border: 1px solid #E8E2DA; background: #fff; padding: 1rem 1.25rem; transition: border-color .15s, box-shadow .15s, background .15s; box-shadow: 0 1px 3px rgba(44,24,16,.03); }
        .notif-item a:hover { border-color: #D4C4B0; box-shadow: 0 2px 8px rgba(44,24,16,.05); }
        .notif-item.unread a { background: rgba(111,78,55,.04); border-color: #D4C4B0; }
        .notif-item.unread a:hover { background: rgba(111,78,55,.06); }
        .notif-item-inner { display: flex; align-items: flex-start; gap: 1rem; }
        .notif-avatar { width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; background: linear-gradient(135deg, #A67C52 0%, #6F4E37 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .notif-avatar svg { width: 1.25rem; height: 1.25rem; color: #fff; }
        .notif-item.read .notif-avatar { background: #F0EBE3; }
        .notif-item.read .notif-avatar svg { color: #6B5344; }
        .notif-body { flex: 1; min-width: 0; }
        .notif-title { font-size: 0.9375rem; font-weight: 600; color: #2C1810; margin: 0; line-height: 1.35; }
        .notif-message { font-size: 0.875rem; color: #6B5344; margin: 0.35rem 0 0; line-height: 1.45; }
        .notif-time { font-size: 0.75rem; color: #8B7355; margin-top: 0.5rem; }
        .notif-dot { width: 0.5rem; height: 0.5rem; border-radius: 50%; background: #A67C52; flex-shrink: 0; margin-top: 0.5rem; }
        .notif-item.read .notif-dot { display: none; }
        .notif-empty { text-align: center; padding: 3rem 1.5rem; border-radius: 1rem; border: 1px solid #E8E2DA; background: #fff; box-shadow: 0 1px 3px rgba(44,24,16,.04); }
        .notif-empty-icon { width: 4rem; height: 4rem; margin: 0 auto 1rem; border-radius: 1rem; background: rgba(111,78,55,.08); display: flex; align-items: center; justify-content: center; }
        .notif-empty-icon svg { width: 2rem; height: 2rem; color: #6B5344; }
        .notif-empty h2 { font-size: 1.125rem; font-weight: 600; color: #2C1810; margin: 0 0 0.25rem; }
        .notif-empty p { font-size: 0.875rem; color: #6B5344; margin: 0; }
        .notif-alert { margin-bottom: 1rem; padding: 0.75rem 1rem; border-radius: 0.5rem; background: #ecfdf5; color: #065f46; font-size: 0.875rem; }
        .notif-pagination { margin-top: 1.5rem; display: flex; justify-content: center; }
        .notif-pagination .pagination { flex-wrap: wrap; justify-content: center; gap: 0.25rem; }
    </style>
    @endpush

    <div class="notif-page">
        <div class="notif-wrap">
            <header class="notif-header">
                <h1>
                    <span class="notif-icon-wrap" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                    </span>
                    Notificaciones
                </h1>
                @if($notifications->whereNull('read_at')->count() > 0)
                    <form method="POST" action="{{ route('notifications.read-all') }}" class="inline">
                        @csrf
                        <button type="submit" class="notif-mark-all">Marcar todas como leídas</button>
                    </form>
                @endif
            </header>

            @if(session('ok'))
                <div class="notif-alert" role="alert">{{ session('ok') }}</div>
            @endif

            @if($notifications->isEmpty())
                <div class="notif-empty">
                    <div class="notif-empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                    </div>
                    <h2>Nada por ahora</h2>
                    <p>Cuando tengas mensajes, reservas o actualizaciones, aparecerán aquí.</p>
                </div>
            @else
                <ul class="notif-list">
                    @foreach($notifications as $notification)
                        @php
                            $data = $notification->data;
                            $title = $data['title'] ?? 'Notificación';
                            $message = $data['message'] ?? '';
                            $isUnread = is_null($notification->read_at);
                        @endphp
                        <li class="notif-item {{ $isUnread ? 'unread' : 'read' }}">
                            <a href="{{ route('notifications.read', $notification->id) }}">
                                <div class="notif-item-inner">
                                    <div class="notif-avatar">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                                    </div>
                                    <div class="notif-body">
                                        <p class="notif-title">{{ $title }}</p>
                                        @if($message)
                                            <p class="notif-message">{{ $message }}</p>
                                        @endif
                                        <p class="notif-time">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                    <span class="notif-dot" aria-hidden="true"></span>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>

                @if($notifications->hasPages())
                    <div class="notif-pagination">
                        {{ $notifications->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
