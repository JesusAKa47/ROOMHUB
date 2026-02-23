<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Registro simple: solo usuario (estilo Airbnb). Luego puede convertirse en anfitrión desde el dashboard.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'min:7', 'max:20', 'regex:/^[0-9\s\+\-]+$/'],
            'gender' => ['required', 'string', 'in:hombre,mujer,otro'],
            'birthdate' => ['nullable', 'date', 'before:today'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'state' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'municipality' => ['nullable', 'string', 'max:100'],
            'locality' => ['nullable', 'string', 'max:150'],
        ]);

        $user = DB::transaction(function () use ($request) {
            $client = Client::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => preg_replace('/\s+/', '', $request->phone),
                'gender' => $request->gender,
                'bio' => '',
                'birthdate' => $request->filled('birthdate') ? $request->birthdate : null,
            ]);

            return User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => User::ROLE_CLIENT,
                'client_id' => $client->id,
                'postal_code' => $request->filled('postal_code') ? $request->postal_code : null,
                'state' => $request->filled('state') ? $request->state : null,
                'city' => $request->filled('city') ? $request->city : null,
                'municipality' => $request->filled('municipality') ? $request->municipality : null,
                'locality' => $request->filled('locality') ? $request->locality : null,
            ]);
        });

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
