<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Panel del dueño: solo sus departamentos.
     */
    public function index(Request $request)
    {
        $ownerId = $request->user()->owner_id;
        if (! $ownerId) {
            abort(403, 'No tienes un perfil de dueño asociado.');
        }

        $apartments = Apartment::where('owner_id', $ownerId)
            ->withCount(['reservations' => fn ($q) => $q->where('status', 'paid')])
            ->with(['reservations' => fn ($q) => $q->where('status', 'paid')->latest()->limit(5)])
            ->orderBy('id', 'desc')
            ->get();

        $rentedApartments = $apartments->filter(fn ($a) => $a->reservations_count > 0);

        return view('owner.dashboard', compact('apartments', 'rentedApartments'));
    }
}
