@php
  $dark = $dark ?? false;
@endphp
<style>
  body.global-loading .global-loader { opacity: 1; visibility: visible; pointer-events: auto; }
  .global-loader {
    position: fixed; inset: 0;
    background: {{ $dark ? 'rgba(15,23,42,.9)' : 'rgba(248,248,249,.96)' }};
    display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 1.25rem;
    z-index: 9999; opacity: 0; visibility: hidden; pointer-events: none;
    transition: opacity .3s ease, visibility .3s ease;
  }
  .global-loader-box { position: relative; width: 5rem; height: 5rem; }
  .global-loader-ring {
    position: absolute; inset: 0; border-radius: 50%;
    animation: global-loader-spin 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  }
  .global-loader-ring circle {
    fill: none; stroke: {{ $dark ? '#A67C52' : '#6F4E37' }};
    stroke-width: 3; stroke-linecap: round;
    stroke-dasharray: 90 240; stroke-dashoffset: 0;
    animation: global-loader-dash 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  }
  .global-loader-house {
    position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
    color: {{ $dark ? '#A67C52' : '#6F4E37' }};
    animation: global-loader-pulse 1.5s ease-in-out infinite;
  }
  .global-loader-house svg { width: 2.25rem; height: 2.25rem; }
  .global-loader-text {
    font-size: 0.9375rem; font-weight: 500;
    color: {{ $dark ? 'rgba(255,255,255,.9)' : '#5c5e7a' }};
  }
  @keyframes global-loader-spin { to { transform: rotate(360deg); } }
  @keyframes global-loader-dash {
    0% { stroke-dasharray: 90 240; stroke-dashoffset: 0; }
    50% { stroke-dasharray: 180 150; stroke-dashoffset: -70; }
    100% { stroke-dasharray: 90 240; stroke-dashoffset: -250; }
  }
  @keyframes global-loader-pulse {
    0%, 100% { opacity: 0.85; transform: scale(1); }
    50% { opacity: 1; transform: scale(1.05); }
  }
</style>
<div class="global-loader" id="global-loader" aria-hidden="true">
  <div class="global-loader-box">
    <svg class="global-loader-ring" viewBox="0 0 100 100">
      <circle cx="50" cy="50" r="42"/>
    </svg>
    <div class="global-loader-house">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
        <polyline points="9 22 9 12 15 12 15 22"/>
      </svg>
    </div>
  </div>
  <span class="global-loader-text">Cargando…</span>
</div>
