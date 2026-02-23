<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\ApartmentComment;
use Illuminate\Http\Request;

class CuartosController extends Controller
{
    /**
     * Listado de todos los cuartos (vista principal para clientes).
     * Filtros: búsqueda (q), estado, ciudad, municipio, cercanía, precio, servicios.
     */
    public function index(Request $request)
    {
        $query = Apartment::with('owner')
            ->withAvg('comments as rating_avg', 'rating')
            ->where('status', 'activo');

        if ($request->filled('q')) {
            $s = $request->q;
            $query->where(fn ($q) => $q->where('title', 'like', "%{$s}%")
                ->orWhere('address', 'like', "%{$s}%")
                ->orWhere('description', 'like', "%{$s}%")
                ->orWhere('city', 'like', "%{$s}%")
                ->orWhere('state', 'like', "%{$s}%")
                ->orWhere('municipality', 'like', "%{$s}%")
                ->orWhere('locality', 'like', "%{$s}%"));
        }
        if ($request->filled('state')) {
            $query->where('state', 'like', '%' . trim($request->state) . '%');
        }
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . trim($request->city) . '%');
        }
        if ($request->filled('municipality')) {
            $query->where('municipality', 'like', '%' . trim($request->municipality) . '%');
        }
        if ($request->filled('cercania')) {
            $term = trim($request->cercania);
            $query->where('nearby', 'like', '%' . $term . '%');
        }
        if ($request->filled('user_lat') && $request->filled('user_lng')) {
            $userLat = (float) $request->user_lat;
            $userLng = (float) $request->user_lng;
            $radiusKm = (float) ($request->input('radius_km', 10));
            if ($radiusKm <= 0) {
                $radiusKm = 10;
            }
            $query->whereNotNull('apartments.lat')->whereNotNull('apartments.lng')
                ->selectRaw('( 6371 * acos( LEAST(1, GREATEST(-1, cos(radians(?)) * cos(radians(apartments.lat)) * cos(radians(apartments.lng) - radians(?)) + sin(radians(?)) * sin(radians(apartments.lat)) ) ) ) ) as distance_km', [$userLat, $userLng, $userLat])
                ->having('distance_km', '<=', $radiusKm)
                ->orderBy('distance_km');
        }
        $minRentFilter = (float) ($request->input('min_rent', 1000));
        if ($minRentFilter < 1000) {
            $minRentFilter = 1000;
        }
        $query->where('monthly_rent', '>=', $minRentFilter);
        $maxRentFilter = (float) ($request->input('max_rent', 100000));
        $query->where('monthly_rent', '<=', $maxRentFilter);
        foreach (['has_wifi', 'has_ac', 'has_tv', 'has_kitchen', 'has_laundry', 'has_parking', 'has_heating', 'has_balcony'] as $attr) {
            if ($request->filled($attr) && $request->boolean($attr)) {
                $query->where($attr, true);
            }
        }

        if (! $request->filled('user_lat')) {
            $query->orderBy('available_from', 'desc');
        }
        $apartments = $query->paginate(12)->withQueryString();

        $minRent = (float) ($request->min_rent ?? 1000);
        if ($minRent < 1000) {
            $minRent = 1000;
        }
        $maxRent = (float) ($request->max_rent ?? 100000);

        $base = Apartment::where('status', 'activo');
        $states = $base->whereNotNull('state')->where('state', '!=', '')->distinct()->pluck('state')->filter()->sort()->values();
        $cities = $base->whereNotNull('city')->where('city', '!=', '')->distinct()->pluck('city')->filter()->sort()->values();
        $municipalities = $base->whereNotNull('municipality')->where('municipality', '!=', '')->distinct()->pluck('municipality')->filter()->sort()->values();

        $userLocation = null;
        if ($request->user() && ($request->user()->city || $request->user()->state)) {
            $userLocation = [
                'city' => $request->user()->city,
                'state' => $request->user()->state,
                'municipality' => $request->user()->municipality,
            ];
        }

        $favoritedIds = $request->user()
            ? $request->user()->favoritedApartments()->pluck('apartments.id')->all()
            : [];

        return view('cuartos.index', compact('apartments', 'minRent', 'maxRent', 'states', 'cities', 'municipalities', 'userLocation', 'favoritedIds'));
    }

    /**
     * Mapa con todos los cuartos; al hacer clic en un marcador se abre un modal con detalles.
     */
    public function mapa()
    {
        $apartments = Apartment::with('owner')
            ->withAvg('comments as rating_avg', 'rating')
            ->where('status', 'activo')
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->orderBy('available_from', 'desc')
            ->get();

        return view('cuartos.mapa', compact('apartments'));
    }

    /**
     * Detalle de un cuarto y formulario de reserva.
     */
    public function show(Apartment $apartment)
    {
        if ($apartment->status !== 'activo') {
            abort(404);
        }
        $apartment->load(['owner', 'comments.user'])->loadAvg('comments as rating_avg', 'rating');
        return view('cuartos.show', compact('apartment'));
    }

    /**
     * Guardar un comentario en un departamento.
     */
    public function storeComment(Request $request, Apartment $apartment)
    {
        if ($apartment->status !== 'activo') {
            abort(404);
        }
        $request->validate([
            'body' => ['required', 'string', 'min:3', 'max:1000'],
            'rating' => ['required', 'integer', 'between:1,5'],
        ], [
            'body.required' => 'Escribe tu comentario.',
            'body.min' => 'El comentario debe tener al menos 3 caracteres.',
            'body.max' => 'El comentario no puede superar 1000 caracteres.',
            'rating.required' => 'Elige una puntuación.',
            'rating.between' => 'La puntuación debe estar entre 1 y 5 estrellas.',
        ]);

        ApartmentComment::create([
            'apartment_id' => $apartment->id,
            'user_id' => $request->user()->id,
            'body' => $request->body,
            'rating' => $request->rating,
        ]);

        return redirect()->route('cuartos.show', $apartment)->with('comment_ok', 'Tu comentario se publicó correctamente.');
    }
}
