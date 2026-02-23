<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class AuthApiController extends Controller
{
    /**
     * Registro: name + email + password + password_confirmation → token + user.
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($request) {
            $client = Client::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => '',
                'gender' => 'otro',
                'bio' => '',
            ]);

            return User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => User::ROLE_CLIENT,
                'client_id' => $client->id,
            ]);
        });

        $user->logActivity('register_api', ['client' => 'flutter'], $request->ip());

        $plainToken = ApiToken::generate();
        ApiToken::create([
            'user_id' => $user->id,
            'token' => ApiToken::hash($plainToken),
            'name' => $request->input('device_name', 'flutter-app'),
            'expires_at' => now()->addDays(30),
        ]);

        return response()->json([
            'token' => $plainToken,
            'token_type' => 'Bearer',
            'expires_in' => 30 * 24 * 60 * 60,
            'user' => $this->userResponse($user),
        ], 201);
    }

    /**
     * Login: email + password → token + user.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $key = Str::transliterate(Str::lower($request->email) . '|' . $request->ip());
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'message' => __('Demasiados intentos. Intenta de nuevo en :seconds segundos.', ['seconds' => $seconds]),
            ], 429);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            RateLimiter::hit($key);
            return response()->json([
                'message' => __('Credenciales incorrectas.'),
            ], 401);
        }

        if ($user->isSuspended()) {
            return response()->json([
                'message' => __('Tu cuenta está suspendida. Contacta al administrador.'),
            ], 403);
        }

        RateLimiter::clear($key);

        $user->update(['last_login_at' => now()]);
        $user->logActivity('login_api', ['client' => 'flutter'], $request->ip());

        $plainToken = ApiToken::generate();
        $token = ApiToken::create([
            'user_id' => $user->id,
            'token' => ApiToken::hash($plainToken),
            'name' => $request->input('device_name', 'flutter-app'),
            'expires_at' => now()->addDays(30),
        ]);

        return response()->json([
            'token' => $plainToken,
            'token_type' => 'Bearer',
            'expires_in' => 30 * 24 * 60 * 60,
            'user' => $this->userResponse($user),
        ]);
    }

    /**
     * Logout: revoke current token.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user) {
            $user->logActivity('logout_api', ['client' => 'flutter'], $request->ip());
        }

        $auth = $request->header('Authorization');
        if ($auth && str_starts_with($auth, 'Bearer ')) {
            $plainToken = substr($auth, 7);
            $hashed = ApiToken::hash($plainToken);
            ApiToken::where('token', $hashed)->delete();
        }

        return response()->json(['message' => __('Sesión cerrada correctamente.')]);
    }

    /**
     * Get authenticated user.
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $this->userResponse($request->user()),
        ]);
    }

    protected function userResponse(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status,
            'owner_id' => $user->owner_id,
            'client_id' => $user->client_id,
            'is_admin' => $user->isAdmin(),
            'is_owner' => $user->isOwner(),
            'is_client' => $user->isClient(),
        ];
    }
}
