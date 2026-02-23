<x-app-layout>
    @push('styles')
    <style>
        .msg-page { background: #FAF6F0; min-height: calc(100vh - 4rem); }
        .msg-wrap { max-width: 72rem; margin: 0 auto; padding: 1rem; height: calc(100vh - 5rem); min-height: 420px; }
        @media (min-width: 1024px) { .msg-wrap { padding: 1.25rem 1.5rem; } }
        .msg-layout { display: grid; grid-template-columns: 1fr; grid-template-rows: auto 1fr; gap: 0; height: 100%; border-radius: 1rem; border: 1px solid #E8E2DA; background: #fff; box-shadow: 0 1px 3px rgba(44,24,16,.04); overflow: hidden; }
        @media (min-width: 768px) { .msg-layout { grid-template-columns: 320px 1fr; grid-template-rows: 1fr; } }
        .msg-sidebar { display: flex; flex-direction: column; min-height: 0; border-right: none; }
        @media (min-width: 768px) { .msg-sidebar { border-right: 1px solid #E8E2DA; } }
        .msg-sidebar-header { padding: 1.25rem 1.25rem; border-bottom: 1px solid #E8E2DA; display: flex; align-items: center; gap: 0.5rem; background: #FFFDFB; }
        .msg-sidebar-header .icon-wrap { width: 2.25rem; height: 2.25rem; border-radius: 0.625rem; background: rgba(111,78,55,.12); display: flex; align-items: center; justify-content: center; }
        .msg-sidebar-header .icon-wrap svg { color: #6F4E37; }
        .msg-sidebar-header h1 { font-size: 1.125rem; font-weight: 700; color: #2C1810; margin: 0; }
        .msg-search { padding: 0.75rem 1rem; border-bottom: 1px solid #E8E2DA; }
        .msg-search-input { width: 100%; padding: 0.5rem 0.75rem 0.5rem 2.25rem; border-radius: 0.5rem; border: 1px solid #E8E2DA; font-size: 0.875rem; color: #2C1810; background: #FAF6F0 url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='1.5' class='w-4 h-4'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z'/%3E%3C/svg%3E") no-repeat 0.65rem center; background-size: 1rem; transition: border-color .15s, box-shadow .15s; }
        .msg-search-input::placeholder { color: #94a3b8; }
        .msg-search-input:focus { outline: none; border-color: #6F4E37; box-shadow: 0 0 0 3px rgba(111,78,55,.12); }
        .msg-list { flex: 1; overflow-y: auto; padding: 0.5rem; }
        .msg-thread { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 0.875rem; border-radius: 0.75rem; text-decoration: none; color: inherit; transition: background .15s; margin-bottom: 0.25rem; }
        .msg-thread:hover { background: #FFFDFB; }
        .msg-thread.active { background: rgba(111,78,55,.08); }
        .msg-thread.active .msg-avatar { background: linear-gradient(135deg, #A67C52, #6F4E37); color: #fff; }
        .msg-thread.active .msg-thread-name { color: #6F4E37; font-weight: 600; }
        .msg-avatar { width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; background: #F0EBE3; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.9375rem; color: #6B5344; flex-shrink: 0; transition: background .15s, color .15s; }
        .msg-avatar-img { width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; object-fit: cover; display: block; flex-shrink: 0; }
        .msg-thread:hover .msg-avatar { background: #E8E2DA; color: #2C1810; }
        .msg-thread-main { flex: 1; min-width: 0; }
        .msg-thread-name { font-size: 0.9375rem; font-weight: 500; color: #2C1810; margin: 0 0 0.125rem; }
        .msg-thread-role { font-size: 0.75rem; color: #6B5344; margin: 0; }
        .msg-thread-last { font-size: 0.8125rem; color: #8B7355; margin-top: 0.125rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .msg-main { display: flex; flex-direction: column; min-height: 0; background: #fff; }
        .msg-main-header { padding: 1rem 1.25rem; border-bottom: 1px solid #E8E2DA; display: flex; align-items: center; gap: 1rem; background: #FFFDFB; }
        .msg-main-header .msg-avatar { width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; background: linear-gradient(135deg, #A67C52, #6F4E37); color: #fff; font-size: 0.9375rem; }
        .msg-main-header-info { flex: 1; min-width: 0; }
        .msg-main-header-name { font-size: 1rem; font-weight: 600; color: #2C1810; margin: 0; }
        .msg-main-header-status { font-size: 0.8125rem; color: #6B5344; margin: 0.25rem 0 0; }
        .msg-main-header-apt { font-size: 0.75rem; color: #8B7355; margin-top: 0.125rem; }
        .msg-main-body { flex: 1; overflow-y: auto; padding: 1.25rem; display: flex; flex-direction: column; gap: 1rem; background: #FAF6F0; }
        .msg-bubble-row { display: flex; }
        .msg-bubble-row.me { justify-content: flex-end; }
        .msg-bubble-wrap { max-width: 75%; }
        .msg-bubble { padding: 0.625rem 1rem; border-radius: 1rem; font-size: 0.9375rem; line-height: 1.45; box-shadow: 0 1px 2px rgba(0,0,0,.04); }
        .msg-bubble.me { background: #6F4E37; color: #fff; border-bottom-right-radius: 0.375rem; }
        .msg-bubble.other { background: #fff; color: #2C1810; border: 1px solid #E8E2DA; border-bottom-left-radius: 0.375rem; }
        .msg-bubble-meta { margin-top: 0.25rem; font-size: 0.6875rem; color: #8B7355; }
        .msg-bubble-row.me .msg-bubble-meta { text-align: right; }
        .msg-main-footer { padding: 1rem 1.25rem; border-top: 1px solid #E8E2DA; background: #fff; }
        .msg-input-wrap { display: flex; align-items: center; gap: 0.75rem; }
        .msg-input { flex: 1; border-radius: 0.75rem; border: 1px solid #E8E2DA; padding: 0.625rem 1rem; font-size: 0.9375rem; color: #2C1810; background: #FAF6F0; transition: border-color .15s, box-shadow .15s; }
        .msg-input::placeholder { color: #8B7355; }
        .msg-input:focus { outline: none; border-color: #6F4E37; box-shadow: 0 0 0 3px rgba(111,78,55,.12); background: #fff; }
        .msg-send-btn { width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; border: none; background: #6F4E37; color: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; transition: background .15s, transform .1s; }
        .msg-send-btn:hover { background: #4A3728; }
        .msg-send-btn:active { transform: scale(0.97); }
        .msg-empty { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2.5rem; text-align: center; }
        .msg-empty-icon { width: 4rem; height: 4rem; border-radius: 1rem; background: rgba(111,78,55,.08); display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; }
        .msg-empty-icon svg { color: #6B5344; }
        .msg-empty-title { font-size: 1rem; font-weight: 600; color: #2C1810; margin: 0 0 0.25rem; }
        .msg-empty-text { font-size: 0.875rem; color: #6B5344; margin: 0; line-height: 1.5; }
        .msg-list-empty { padding: 1.25rem 1rem; font-size: 0.8125rem; color: #6B5344; text-align: center; line-height: 1.5; }
        .msg-report-details { margin-left: auto; }
        .msg-report-summary {
            font-size: 0.8125rem;
            font-weight: 500;
            color: #8B7355;
            cursor: pointer;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            list-style: none;
            transition: background .15s, color .15s;
        }
        .msg-report-summary::-webkit-details-marker { display: none; }
        .msg-report-summary:hover { background: rgba(229,57,53,.08); color: #b91c1c; }
        .msg-report-form {
            margin-top: 0.75rem;
            padding: 1rem;
            background: #FFFDFB;
            border: 1px solid #E8E2DA;
            border-radius: 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .msg-report-label { font-size: 0.8125rem; font-weight: 500; color: #6B5344; }
        .msg-report-textarea {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid #E8E2DA;
            font-size: 0.875rem;
            color: #2C1810;
            resize: vertical;
            min-height: 4rem;
        }
        .msg-report-textarea:focus { outline: none; border-color: #6F4E37; box-shadow: 0 0 0 2px rgba(111,78,55,.12); }
        .msg-report-btn {
            align-self: flex-start;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.8125rem;
            font-weight: 600;
            background: #dc3545;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background .2s;
        }
        .msg-report-btn:hover { background: #c82333; }
        .msg-report-error { font-size: 0.8125rem; color: #b91c1c; margin: 0 0 0.25rem; }
        .msg-flash-ok { padding: 0.5rem 1rem; margin-bottom: 1rem; border-radius: 0.5rem; font-size: 0.875rem; background: rgba(82,121,111,.15); color: #2d5a4f; border: 1px solid rgba(82,121,111,.3); }
    </style>
    @endpush

    <div class="msg-page">
        <div class="msg-wrap">
            <div class="msg-layout">
                {{-- Sidebar: conversaciones --}}
                <aside class="msg-sidebar">
                    <div class="msg-sidebar-header">
                        <span class="icon-wrap" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/></svg>
                        </span>
                        <h1>Mensajes</h1>
                    </div>
                    <div class="msg-search">
                        <input type="text" id="msg-search" class="msg-search-input" placeholder="Buscar conversación">
                    </div>
                    <div class="msg-list" id="msg-thread-list">
                        @forelse($threads as $partnerId => $threadMessages)
                            @php
                                $last = $threadMessages->first();
                                $other = $last->sender_id === $user->id ? $last->receiver : $last->sender;
                                $isActive = $activePartner && $other && $other->id === $activePartner->id;
                            @endphp
                            @if($other)
                            <a href="{{ route('messages.index', ['user' => $other->id]) }}" class="msg-thread {{ $isActive ? 'active' : '' }}" data-name="{{ strtolower($other->name) }}">
                                @if($other->avatarUrl())
                                <img src="{{ $other->avatarUrl() }}" alt="" class="msg-avatar msg-avatar-img">
                            @else
                                <div class="msg-avatar">{{ strtoupper(substr($other->publicName(), 0, 1)) }}</div>
                            @endif
                                <div class="msg-thread-main">
                                    <div class="msg-thread-name">{{ $other->publicName() }}</div>
                                    <div class="msg-thread-role">
                                        @if($other->isOwner())
                                            Anfitrión
                                        @elseif($other->isClient())
                                            Huésped
                                        @else
                                            Usuario
                                        @endif
                                    </div>
                                    <div class="msg-thread-last">
                                        {{ Str::limit($last->body, 42) }}
                                    </div>
                                </div>
                            </a>
                            @endif
                        @empty
                            <div class="msg-list-empty">
                                Aún no tienes conversaciones. Envía un mensaje desde un alojamiento o desde tu reserva.
                            </div>
                        @endforelse
                    </div>
                </aside>

                {{-- Panel principal: chat --}}
                <section class="msg-main">
                    @if($activePartner)
                        <div class="msg-main-header">
                            @if($activePartner->avatarUrl())
                            <img src="{{ $activePartner->avatarUrl() }}" alt="" class="msg-avatar msg-avatar-img">
                        @else
                            <div class="msg-avatar">{{ strtoupper(substr($activePartner->publicName(), 0, 1)) }}</div>
                        @endif
                            <div class="msg-main-header-info">
                                <span class="msg-main-header-name">{{ $activePartner->publicName() }}</span>
                                <span class="msg-main-header-status">
                                    @if($activePartner->isOwner())
                                        Anfitrión
                                    @elseif($activePartner->isClient())
                                        Huésped
                                    @else
                                        Usuario
                                    @endif
                                </span>
                                @if($apartment)
                                    <span class="msg-main-header-apt">Sobre: {{ $apartment->title }}</span>
                                @endif
                            </div>
                            <details class="msg-report-details">
                                <summary class="msg-report-summary">Reportar conversación</summary>
                                <form action="{{ route('messages.report') }}" method="POST" class="msg-report-form">
                                    @csrf
                                    <input type="hidden" name="receiver_id" value="{{ $activePartner->id }}">
                                    @if($errors->any())
                                        <p class="msg-report-error">{{ $errors->first() }}</p>
                                    @endif
                                    <label for="msg-report-reason" class="msg-report-label">Motivo (opcional)</label>
                                    <textarea id="msg-report-reason" name="reason" class="msg-report-textarea" rows="2" placeholder="Ej. contenido inapropiado, acoso...">{{ old('reason') }}</textarea>
                                    <button type="submit" class="msg-report-btn">Enviar reporte</button>
                                </form>
                            </details>
                        </div>

                        <div class="msg-main-body" id="msg-body">
                            @if(session('ok'))
                                <div class="msg-flash-ok" role="alert">{{ session('ok') }}</div>
                            @endif
                            @forelse($conversation as $m)
                                <div class="msg-bubble-row {{ $m->sender_id === $user->id ? 'me' : 'other' }}">
                                    <div class="msg-bubble-wrap">
                                        <div class="msg-bubble {{ $m->sender_id === $user->id ? 'me' : 'other' }}">
                                            {{ $m->body }}
                                        </div>
                                        <div class="msg-bubble-meta">
                                            {{ $m->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="msg-empty">
                                    <div class="msg-empty-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/></svg>
                                    </div>
                                    <p class="msg-empty-title">Sin mensajes aún</p>
                                    <p class="msg-empty-text">Escribe el primer mensaje para iniciar la conversación.</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="msg-main-footer">
                            <form action="{{ route('messages.store') }}" method="POST" class="msg-input-wrap">
                                @csrf
                                <input type="hidden" name="receiver_id" value="{{ $activePartner->id }}">
                                @if($apartment)
                                    <input type="hidden" name="apartment_id" value="{{ $apartment->id }}">
                                @endif
                                <input type="text" name="body" class="msg-input" placeholder="Escribe tu mensaje..." maxlength="2000" required autocomplete="off">
                                <button type="submit" class="msg-send-btn" aria-label="Enviar mensaje">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="msg-empty">
                            <div class="msg-empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/></svg>
                            </div>
                            <p class="msg-empty-title">Selecciona una conversación</p>
                            <p class="msg-empty-text">Elige un contacto en la lista o envía un mensaje desde la ficha de un alojamiento.</p>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const list = document.getElementById('msg-thread-list');
            const search = document.getElementById('msg-search');
            if (search && list) {
                search.addEventListener('input', function() {
                    const q = this.value.toLowerCase().trim();
                    list.querySelectorAll('.msg-thread').forEach(function(item) {
                        const name = item.getAttribute('data-name') || '';
                        item.style.display = !q || name.indexOf(q) !== -1 ? '' : 'none';
                    });
                });
            }
            const body = document.getElementById('msg-body');
            if (body) {
                body.scrollTop = body.scrollHeight;
            }
        })();
    </script>
</x-app-layout>
