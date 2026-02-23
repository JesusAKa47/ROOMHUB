<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    /**
     * Lista los alojamientos que el usuario tiene en favoritos.
     */
    public function index(Request $request): View
    {
        $apartments = $request->user()
            ->favoritedApartments()
            ->with('owner')
            ->withAvg('comments as rating_avg', 'rating')
            ->orderByPivot('created_at', 'desc')
            ->get();

        $favoritedIds = $apartments->pluck('id')->all();

        return view('favorites.index', compact('apartments', 'favoritedIds'));
    }

    /**
     * Añade o quita el alojamiento de favoritos del usuario.
     */
    public function toggle(Request $request, Apartment $apartment): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json(['ok' => false, 'message' => 'Debes iniciar sesión'], 401);
        }

        $user = Auth::user();
        $attached = $user->favoritedApartments()->where('apartment_id', $apartment->id)->exists();

        if ($attached) {
            $user->favoritedApartments()->detach($apartment->id);
            $favorited = false;
        } else {
            $user->favoritedApartments()->attach($apartment->id);
            $favorited = true;
        }

        return response()->json(['ok' => true, 'favorited' => $favorited]);
    }
}
