<x-app-layout>
    @push('styles')
    <style>
        main:has(.success-page) { padding-top: 2rem; padding-bottom: 3.5rem; min-height: 60vh; }
        @media (min-width: 640px) { main:has(.success-page) { padding-top: 3rem; padding-bottom: 4.5rem; } }
        .success-page { background: #FAF6F0; min-height: calc(100vh - 8rem); padding: 1.5rem 1rem 3rem; }
        .success-wrap { max-width: 28rem; margin: 0 auto; }
        .success-header { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; }
        .success-header h1 { font-size: 1.5rem; font-weight: 700; color: #2C1810; margin: 0; display: flex; align-items: center; gap: 0.5rem; letter-spacing: -0.02em; }
        .success-header .header-icon { width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; }
        .success-header .header-icon.paid { background: rgba(16,185,129,.15); }
        .success-header .header-icon.paid svg { color: #52796F; }
        .success-header .header-icon.pending { background: rgba(245,158,11,.15); }
        .success-header .header-icon.pending svg { color: #d97706; }
        .success-header a.explore-link { font-size: 0.875rem; font-weight: 500; color: #A67C52; text-decoration: none; }
        .success-header a.explore-link:hover { color: #4A3728; text-decoration: underline; }
        .success-card { background: #fff; border-radius: 1rem; border: 1px solid #E8E2DA; box-shadow: 0 1px 3px rgba(44,24,16,.04); overflow: hidden; }
        .success-card-section { padding: 1.5rem 1.25rem; }
        @media (min-width: 640px) { .success-card-section { padding: 1.75rem 1.5rem; } }
        .success-card .icon-wrap { width: 4rem; height: 4rem; border-radius: 1rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
        .success-card .icon-wrap.paid { background: rgba(16,185,129,.12); }
        .success-card .icon-wrap.paid svg { color: #52796F; }
        .success-card .icon-wrap.pending { background: rgba(245,158,11,.12); }
        .success-card .icon-wrap.pending svg { color: #d97706; }
        .success-card .section-title { font-size: 1.25rem; font-weight: 700; color: #2C1810; margin: 0 0 0.25rem; text-align: center; }
        .success-card .section-subtitle { font-size: 0.875rem; color: #6B5344; margin: 0; text-align: center; }
        .success-details { background: #FFFDFB; border-radius: 0.75rem; border: 1px solid #E8E2DA; padding: 1rem 1.25rem; margin-top: 1rem; }
        .success-details .row { display: flex; justify-content: space-between; align-items: flex-start; gap: 0.75rem; padding: 0.4rem 0; font-size: 0.875rem; }
        .success-details .row:not(:last-child) { border-bottom: 1px solid #E8E2DA; }
        .success-details .row .label { color: #6B5344; }
        .success-details .row .value { font-weight: 500; color: #2C1810; text-align: right; }
        .success-details .row.total { border-bottom: none; padding-top: 0.75rem; margin-top: 0.25rem; border-top: 1px solid #D4C4B0; font-weight: 600; }
        .success-details .row.total .value { font-size: 1.125rem; font-weight: 700; color: #6F4E37; }
        .success-chat-block { background: rgba(111,78,55,.06); border-top: 1px solid rgba(111,78,55,.12); padding: 1.25rem 1.25rem; }
        @media (min-width: 640px) { .success-chat-block { padding: 1.25rem 1.5rem; } }
        .success-chat-inner { display: flex; flex-wrap: wrap; align-items: center; gap: 1rem; }
        .success-chat-icon { width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: rgba(111,78,55,.12); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .success-chat-icon svg { color: #6F4E37; }
        .success-chat-text { flex: 1; min-width: 0; }
        .success-chat-text strong { font-size: 0.9375rem; color: #2C1810; }
        .success-chat-text p { font-size: 0.8125rem; color: #6B5344; margin: 0.25rem 0 0; }
        .success-chat-block .btn-chat { display: inline-flex; align-items: center; justify-content: center; padding: 0.625rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; color: #fff; background: #6F4E37; text-decoration: none; transition: background .15s, opacity .15s; flex-shrink: 0; }
        .success-chat-block .btn-chat:hover { background: #4A3728; opacity: .95; }
        .success-actions { padding: 1.25rem 1.25rem 1.5rem; background: #FFFDFB; border-top: 1px solid #E8E2DA; display: flex; flex-wrap: wrap; gap: 0.75rem; }
        .success-actions .btn { flex: 1; min-width: 0; display: inline-flex; align-items: center; justify-content: center; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; text-decoration: none; transition: box-shadow .15s, background .15s; }
        .success-actions .btn-secondary { background: #fff; color: #2C1810; border: 1px solid #E8E2DA; }
        .success-actions .btn-secondary:hover { background: #FAF6F0; box-shadow: 0 1px 3px rgba(44,24,16,.04); }
        .success-actions .btn-primary { background: #6F4E37; color: #fff; border: none; }
        .success-actions .btn-primary:hover { background: #4A3728; box-shadow: 0 2px 6px rgba(111,78,55,.3); }
        .success-help { text-align: center; margin-top: 1.5rem; padding: 0 0.5rem; font-size: 0.875rem; color: #6B5344; }
        .success-help a { font-weight: 500; color: #A67C52; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem; }
        .success-help a:hover { text-decoration: underline; }
    </style>
    @endpush

    <div class="success-page">
        <div class="success-wrap">
            <header class="success-header">
                <h1>
                    @if($reservation->isPaid())
                        <span class="header-icon paid" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        Reserva confirmada
                    @else
                        <span class="header-icon pending" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        Reserva en proceso
                    @endif
                </h1>
                <a href="{{ route('cuartos.index') }}" class="explore-link">Explorar más alojamientos</a>
            </header>

            <div class="success-card">
                <div class="success-card-section">
                    @if($reservation->isPaid())
                        <div class="icon-wrap paid">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h2 class="section-title">¡Pago realizado correctamente!</h2>
                        <p class="section-subtitle">Tu reserva está confirmada. Los detalles aparecen abajo.</p>
                    @else
                        <div class="icon-wrap pending">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h2 class="section-title">Reserva pendiente de pago</h2>
                        <p class="section-subtitle">Completa el pago para confirmar tu reserva.</p>
                    @endif

                    <div class="success-details">
                        <div class="row">
                            <span class="label">Folio de transacción</span>
                            <span class="value">{{ $reservation->transaction_folio }}</span>
                        </div>
                        <div class="row">
                            <span class="label">Alojamiento</span>
                            <span class="value">{{ $reservation->apartment->title }}</span>
                        </div>
                        <div class="row">
                            <span class="label">Entrada</span>
                            <span class="value">{{ $reservation->check_in->format('d/m/Y') }}</span>
                        </div>
                        <div class="row">
                            <span class="label">Salida</span>
                            <span class="value">{{ $reservation->check_out->format('d/m/Y') }}</span>
                        </div>
                        <div class="row">
                            <span class="label">Tipo</span>
                            <span class="value">{{ $reservation->rent_type === 'month' ? 'Por mes' : 'Por día' }}</span>
                        </div>
                        <div class="row total">
                            <span class="label">Total</span>
                            <span class="value">${{ number_format($reservation->total_amount, 0) }} MXN</span>
                        </div>
                    </div>
                </div>

                @if($reservation->isPaid() && $reservation->rent_type === 'month')
                    @php $ownerUser = $reservation->apartment->owner->user ?? null; @endphp
                    <div class="success-chat-block">
                        <div class="success-chat-inner">
                            <div class="success-chat-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            </div>
                            <div class="success-chat-text">
                                <strong>Acordar día de entrada</strong>
                                <p>Ponte en contacto con el anfitrión para acordar día y hora de entrega.</p>
                            </div>
                            @if($ownerUser && auth()->id() !== $ownerUser->id)
                                <a href="{{ route('messages.index', ['user' => $ownerUser->id, 'apartment' => $reservation->apartment->id]) }}" class="btn-chat">Ir al chat</a>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="success-actions">
                    <a href="{{ route('reservations.ticket-pdf', $reservation) }}" class="btn btn-secondary" target="_blank" rel="noopener">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" style="width:1.1rem;height:1.1rem;margin-right:0.35rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Descargar ticket (PDF)
                    </a>
                    <a href="{{ route('cuartos.show', $reservation->apartment) }}" class="btn btn-secondary">Ver alojamiento</a>
                    <a href="{{ route('cuartos.index') }}" class="btn btn-primary">Explorar más</a>
                </div>
            </div>

            @php $supportUser = \App\Models\User::where('role', 'admin')->first(); @endphp
            <p class="success-help">
                ¿Necesitas ayuda?
                @if($supportUser && $supportUser->id !== auth()->id())
                    <a href="{{ route('messages.index', ['user' => $supportUser->id]) }}">
                        Chatear con soporte
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}">Ir a mi cuenta</a>
                @endif
            </p>
        </div>
    </div>
</x-app-layout>
