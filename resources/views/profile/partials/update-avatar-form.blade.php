<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Foto de perfil</h2>
        <p class="mt-1 text-sm text-gray-600">Tu foto se mostrará en la navegación y en mensajes. JPG, PNG o WebP, máx. 2 MB.</p>
    </header>
    <div class="mt-4 flex flex-wrap items-center gap-4 profile-avatar-row">
        <div class="profile-avatar-wrap">
            @if($user->avatarUrl())
                <img src="{{ $user->avatarUrl() }}" alt="" class="profile-avatar-img" width="96" height="96">
            @else
                <span class="profile-avatar-initial">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
            @endif
        </div>
        <form method="post" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="profile-avatar-form flex flex-col gap-2">
            @csrf
            <div class="profile-avatar-file-wrap">
                <input type="file" name="avatar" accept="image/jpeg,image/png,image/gif,image/webp" id="avatar-upload" class="profile-avatar-file-input" aria-label="Elegir imagen">
                <button type="button" class="profile-avatar-file-btn" id="avatar-upload-trigger">Elegir archivo</button>
                <span class="profile-avatar-file-label" id="avatar-file-label">Ningún archivo elegido</span>
            </div>
            @error('avatar')
                <p class="profile-form form-error">{{ $message }}</p>
            @enderror
            <button type="submit" class="profile-btn-primary">{{ __('Subir foto') }}</button>
        </form>
    </div>
    @if(session('status') === 'avatar-updated')
        <p class="mt-2 text-sm text-green-600" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)">{{ __('Foto actualizada.') }}</p>
    @endif
</section>
