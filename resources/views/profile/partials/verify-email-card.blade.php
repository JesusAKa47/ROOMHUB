@if(Auth::user()->isOwner() && !Auth::user()->hasVerifiedEmail())
<div class="profile-card profile-card-compact" id="verificar-correo">
    <div class="profile-card-inner">
        <h2>Verificar correo electrónico</h2>
        <p class="card-desc">Como anfitrión, necesitas tener tu correo verificado. Revisa tu bandeja y haz clic en el enlace que te enviamos.</p>
        @if(session('status') === 'verification-link-sent')
            <p class="profile-alert success mt-2">Se ha enviado un nuevo enlace de verificación a tu correo.</p>
        @else
            <form method="POST" action="{{ route('verification.send') }}" class="inline">
                @csrf
                <button type="submit" class="profile-mode-btn">Reenviar correo de verificación</button>
            </form>
        @endif
    </div>
</div>
@endif
