<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RoomHub | Restablecer contraseña</title>

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Instrument Sans','ui-sans-serif','system-ui']
          }
        }
      }
    }
  </script>

  <style>
    .bg-aurora{
      background: radial-gradient(1200px 600px at 10% 10%, rgba(99,102,241,.25), transparent 60%),
                  radial-gradient(900px 500px at 80% 20%, rgba(16,185,129,.25), transparent 55%),
                  radial-gradient(1000px 500px at 30% 80%, rgba(236,72,153,.25), transparent 60%),
                  #0b0b0f;
      animation: float 14s ease-in-out infinite alternate;
    }
    @keyframes float {
      0% { filter:hue-rotate(0) blur(0) }
      100% { filter:hue-rotate(25deg) blur(.4px) }
    }
    .glass {
      background: linear-gradient(180deg,rgba(255,255,255,.08),rgba(255,255,255,.03));
      backdrop-filter: blur(12px);
      border: 1px solid rgba(255,255,255,.12);
    }
    .ring-soft {
      box-shadow: 0 0 0 1px rgba(255,255,255,.08),0 10px 30px rgba(0,0,0,.35);
    }
  </style>
</head>

<body class="min-h-screen bg-aurora text-white antialiased flex flex-col">

  <!-- Navbar -->
  <header class="container mx-auto px-6 py-5 flex items-center justify-between">
    <a href="{{ url('/') }}" class="flex items-center gap-3 group">
      <div class="h-9 w-9 rounded-xl bg-white/10 ring-soft grid place-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-300 group-hover:text-indigo-200 transition" fill="currentColor">
          <path d="M12 2l9 6-9 6-9-6 9-6Zm0 8l9 6-9 6-9-6 9-6Z"/>
        </svg>
      </div>
      <span class="text-lg font-semibold tracking-wide">RoomHub</span>
    </a>
  </header>

  <!-- Contenido -->
  <main class="container mx-auto px-6 py-8 flex-1">

    <div class="mx-auto max-w-md glass ring-soft rounded-3xl p-6 sm:p-8">

      <h1 class="text-2xl font-semibold mb-1">Restablecer contraseña</h1>
      <p class="text-white/70 text-sm mb-6">
        Ingresa tu nueva contraseña para recuperar el acceso a tu cuenta.
      </p>

      <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <!-- Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <div>
          <label for="email" class="text-sm text-white/80">Email</label>

          <input
            id="email"
            class="w-full rounded-xl bg-white/10 border border-white/10 focus:border-indigo-400 
                   focus:ring-2 focus:ring-indigo-400/30 text-white placeholder-white/60 px-4 py-2 outline-none mt-1"
            type="email"
            name="email"
            value="{{ old('email', $request->email) }}"
            required autofocus autocomplete="username"
          >

          @error('email')
            <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
          @enderror
        </div>

        <!-- New Password -->
        <div>
          <label for="password" class="text-sm text-white/80">Nueva contraseña</label>

          <input
            id="password"
            class="w-full rounded-xl bg-white/10 border border-white/10 focus:border-indigo-400 
                   focus:ring-2 focus:ring-indigo-400/30 text-white placeholder-white/60 px-4 py-2 outline-none mt-1"
            type="password"
            name="password"
            required autocomplete="new-password"
          >

          @error('password')
            <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
          @enderror
        </div>

        <!-- Confirm Password -->
        <div>
          <label for="password_confirmation" class="text-sm text-white/80">Confirmar contraseña</label>

          <input
            id="password_confirmation"
            class="w-full rounded-xl bg-white/10 border border-white/10 focus:border-indigo-400 
                   focus:ring-2 focus:ring-indigo-400/30 text-white placeholder-white/60 px-4 py-2 outline-none mt-1"
            type="password"
            name="password_confirmation"
            required autocomplete="new-password"
          >

          @error('password_confirmation')
            <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex justify-end mt-6">
          <button
            type="submit"
            class="inline-flex justify-center items-center px-5 py-2.5 rounded-xl bg-indigo-500 hover:bg-indigo-600 
                   font-medium transition"
          >
            Restablecer contraseña
          </button>
        </div>

      </form>
    </div>

  </main>

  <footer class="container mx-auto px-6 py-8">
    <div class="text-xs text-white/50">© {{ date('Y') }} RoomHub</div>
  </footer>

</body>
</html>
