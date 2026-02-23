@props(['class' => 'h-9 w-auto', 'white' => false, 'full' => false])
@php
    $imgFile = $full ? 'roomhub-logo-full.png' : 'roomhub-logo.png';
    $imgPath = public_path('images/' . $imgFile);
    $useImg = file_exists($imgPath);
@endphp
@if($useImg)
    <img src="{{ asset('images/' . $imgFile) }}" alt="RoomHub" class="{{ $class }}" {{ $attributes }}>
@else
    <x-application-logo class="{{ $class }} {{ $white ? 'fill-current text-white' : 'fill-current text-roomhub-primary' }}" {{ $attributes }} />
@endif
