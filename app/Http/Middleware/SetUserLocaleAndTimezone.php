<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetUserLocaleAndTimezone
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user) {
            if (! empty($user->locale)) {
                App::setLocale($user->locale);
            }
            if (! empty($user->timezone)) {
                Carbon::setTimezone($user->timezone);
            }
        }

        return $next($request);
    }
}
