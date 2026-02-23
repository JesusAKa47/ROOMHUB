<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Stripe\StripeClient;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information (incl. privacy, locale, timezone).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->only([
            'name', 'email',
            'postal_code', 'state', 'city', 'municipality', 'locality',
            'privacy_show_name_public', 'privacy_show_location_public', 'privacy_show_last_login',
            'locale', 'timezone',
        ]));
        $user->privacy_show_name_public = $request->boolean('privacy_show_name_public');
        $user->privacy_show_location_public = $request->boolean('privacy_show_location_public');
        $user->privacy_show_last_login = $request->boolean('privacy_show_last_login');

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->client_id) {
            $user->client->update([
                'phone' => $request->input('phone', ''),
                'gender' => $request->input('gender', 'otro'),
            ]);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Subir o actualizar foto de perfil.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        $user = $request->user();
        $oldPath = $user->avatar;

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        return Redirect::route('profile.edit')->with('status', 'avatar-updated');
    }

    /**
     * Redirigir al portal de Stripe para gestionar métodos de pago.
     */
    public function stripePortal(Request $request): RedirectResponse
    {
        $secret = config('services.stripe.secret');
        if (! $secret) {
            return Redirect::route('profile.edit')->withErrors(['payment' => 'Stripe no está configurado.']);
        }

        $user = $request->user();
        $stripe = new StripeClient($secret);

        if (! $user->stripe_customer_id) {
            $customer = $stripe->customers->create([
                'email' => $user->email,
                'name' => $user->name,
                'metadata' => ['user_id' => (string) $user->id],
            ]);
            $user->update(['stripe_customer_id' => $customer->id]);
        }

        $session = $stripe->billingPortal->sessions->create([
            'customer' => $user->stripe_customer_id,
            'return_url' => route('profile.edit'),
        ]);

        return Redirect::away($session->url);
    }

    /**
     * Activar modo cliente: crear Client si no existe (para que dueños también puedan rentar).
     */
    public function activateClientMode(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('profile.edit');
        }

        if ($user->client_id) {
            return redirect()->route('profile.edit')->with('status', 'Ya tienes modo cliente activo.');
        }

        $client = \App\Models\Client::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '',
            'gender' => 'otro',
            'bio' => '',
        ]);

        $user->update(['client_id' => $client->id]);

        return redirect()->route('profile.edit')->with('status', 'Modo cliente activado. Ahora puedes explorar y rentar alojamientos.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
