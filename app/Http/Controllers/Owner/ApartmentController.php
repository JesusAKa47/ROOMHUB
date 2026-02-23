<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApartmentRequest;
use App\Models\Apartment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ApartmentController extends Controller
{
    private function getOwnerId(Request $request): int
    {
        $id = $request->user()->owner_id;
        if (! $id) {
            abort(403, 'No tienes perfil de anfitrión.');
        }
        return $id;
    }

    public function index(Request $request): View
    {
        $ownerId = $this->getOwnerId($request);
        $apartments = Apartment::where('owner_id', $ownerId)->orderBy('id', 'desc')->get();
        return view('owner.apartments.index', compact('apartments'));
    }

    public function create(Request $request): View
    {
        $this->getOwnerId($request);
        return view('admin.apartments.form', [
            'apartment' => new Apartment(),
            'owners' => [],
            'forOwner' => true,
        ]);
    }

    public function store(ApartmentRequest $request): RedirectResponse
    {
        $ownerId = $this->getOwnerId($request);
        $data = $this->normalizeData($request);
        $data['owner_id'] = $ownerId;
        $data['status'] = 'activo';

        $paths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $f) {
                $paths[] = $f->store('apartments', 'public');
            }
        }
        $data['photos'] = $paths;

        Apartment::create($data);
        return redirect()->route('owner.apartments.index')->with('ok', 'Inmueble creado. Aparecerá en el listado cuando esté activo.');
    }

    public function edit(Request $request, Apartment $apartment): View|RedirectResponse
    {
        $ownerId = $this->getOwnerId($request);
        if ((int) $apartment->owner_id !== (int) $ownerId) {
            abort(404);
        }
        return view('admin.apartments.form', [
            'apartment' => $apartment,
            'owners' => [],
            'forOwner' => true,
        ]);
    }

    public function update(ApartmentRequest $request, Apartment $apartment): RedirectResponse
    {
        $ownerId = $this->getOwnerId($request);
        if ((int) $apartment->owner_id !== (int) $ownerId) {
            abort(404);
        }
        $data = $this->normalizeData($request);
        $data['owner_id'] = $ownerId;

        $current = $apartment->photos ?? [];
        if ($request->hasFile('photos')) {
            $current = [];
            foreach ($request->file('photos') as $f) {
                $current[] = $f->store('apartments', 'public');
            }
        }
        $data['photos'] = $current;

        $apartment->update($data);
        return redirect()->route('owner.apartments.index')->with('ok', 'Inmueble actualizado.');
    }

    public function destroy(Request $request, Apartment $apartment): RedirectResponse
    {
        $ownerId = $this->getOwnerId($request);
        if ((int) $apartment->owner_id !== (int) $ownerId) {
            abort(404);
        }
        $apartment->delete();
        return back()->with('ok', 'Inmueble eliminado.');
    }

    private function normalizeData(ApartmentRequest $req): array
    {
        $data = $req->safe()->except(['photos', 'nearby_tipo', 'nearby_nombre', 'nearby_metros']);
        $booleans = [
            'has_ac', 'has_tv', 'has_wifi', 'has_kitchen', 'has_parking',
            'has_laundry', 'has_heating', 'has_balcony', 'pets_allowed', 'smoking_allowed',
        ];
        foreach ($booleans as $key) {
            if (array_key_exists($key, $data)) {
                $data[$key] = filter_var($data[$key], FILTER_VALIDATE_BOOLEAN);
            }
        }
        $tipos = $req->input('nearby_tipo', []);
        $nombres = $req->input('nearby_nombre', []);
        $metros = $req->input('nearby_metros', []);
        $nearby = [];
        foreach ($tipos as $i => $tipo) {
            $tipo = is_string($tipo) ? trim($tipo) : '';
            $nombre = isset($nombres[$i]) ? trim((string) $nombres[$i]) : '';
            if ($tipo !== '' || $nombre !== '') {
                $item = ['tipo' => $tipo, 'nombre' => $nombre];
                if (isset($metros[$i]) && is_numeric($metros[$i]) && (int) $metros[$i] > 0) {
                    $item['metros'] = (int) $metros[$i];
                }
                $nearby[] = $item;
            }
        }
        $data['nearby'] = $nearby;
        $rulesInput = $req->input('rules', []);
        $data['rules'] = array_values(array_filter(array_map(function ($s) {
            return is_string($s) ? trim($s) : '';
        }, is_array($rulesInput) ? $rulesInput : []), fn ($s) => $s !== ''));
        return $data;
    }
}
