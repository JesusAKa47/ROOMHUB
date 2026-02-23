<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $auth = $request->header('Authorization');
        if (! $auth || ! str_starts_with($auth, 'Bearer ')) {
            return response()->json(['message' => 'Token de autenticación no proporcionado.'], 401);
        }

        $plainToken = substr($auth, 7);
        $hashed = ApiToken::hash($plainToken);
        $apiToken = ApiToken::where('token', $hashed)->first();

        if (! $apiToken || $apiToken->isExpired()) {
            return response()->json(['message' => 'Token inválido o expirado.'], 401);
        }

        $apiToken->update(['last_used_at' => now()]);
        $request->setUserResolver(fn () => $apiToken->user);

        return $next($request);
    }
}
