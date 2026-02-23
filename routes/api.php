<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CodigoPostalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

// --- Código postal (México): autocompletar estado, ciudad, municipio, colonias (uso en formulario inmuebles) ---
Route::get('/codigo-postal/{cp}', [CodigoPostalController::class, 'show']);

// --- Autenticación API (Flutter / móvil) ---
Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);
Route::middleware('auth.api')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/user', [AuthApiController::class, 'user']);
});

// --- Reset password (puede usarse desde Flutter) ---
Route::post('/forgot-password', function (Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? response()->json(['status' => __($status)], 200)
        : response()->json(['message' => __($status)], 422);
});

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token'    => ['required'],
        'email'    => ['required', 'email'],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => bcrypt($password),
            ])->save();

            $user->setRememberToken(Str::random(60));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? response()->json(['status' => __($status)], 200)
        : response()->json(['message' => __($status)], 422);
});
