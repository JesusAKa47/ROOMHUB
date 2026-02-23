<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApartmentRequest;
use App\Models\{Apartment, Owner};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ApartmentController extends Controller
{
    public function index(Request $r) {
        $q = Apartment::with('owner');
        if ($r->filled('status'))       $q->where('status', $r->status);
        if ($r->filled('owner_id'))     $q->where('owner_id', $r->owner_id);
        if ($r->filled('postal_code') && Schema::hasColumn('apartments', 'postal_code')) {
            $q->where('postal_code', 'like', trim($r->postal_code) . '%');
        }
        if ($r->filled('state'))        $q->where('state', 'like', '%' . trim($r->state) . '%');
        if ($r->filled('city'))         $q->where('city', 'like', '%' . trim($r->city) . '%');
        if ($r->filled('municipality')) $q->where('municipality', 'like', '%' . trim($r->municipality) . '%');
        if ($r->filled('q')) {
            $s = $r->q;
            $q->where(fn($x)=>$x->where('title','like',"%$s%")->orWhere('address','like',"%$s%"));
        }
        $apartments = $q->orderBy('id','desc')->paginate(15)->withQueryString();
        $owners = Owner::orderBy('name')->get();
        $states = Apartment::whereNotNull('state')->where('state','!=','')->distinct()->pluck('state')->sort()->values()->all();
        $cities = Apartment::whereNotNull('city')->where('city','!=','')->distinct()->pluck('city')->sort()->values()->all();
        $municipalities = Apartment::whereNotNull('municipality')->where('municipality','!=','')->distinct()->pluck('municipality')->sort()->values()->all();
        return view('admin.apartments.index', compact('apartments','owners','states','cities','municipalities'));
    }

    public function create() {
        return view('admin.apartments.form', [
            'apartment' => new Apartment(),
            'owners'    => Owner::orderBy('name')->get(),
        ]);
    }

    public function store(ApartmentRequest $req) {
        $data = $this->normalizeApartmentData($req);
        $paths = [];
        if ($req->hasFile('photos')) {
            foreach ($req->file('photos') as $f) {
                $paths[] = $f->store('apartments', 'public');
            }
        }
        $data['photos'] = $paths;
        Apartment::create($data);
        return redirect()->route('admin.apartments.index')->with('ok', 'Inmueble creado');
    }

    public function edit(Apartment $apartment) {
        return view('admin.apartments.form', [
            'apartment' => $apartment->loadMissing('owner'),
            'owners'    => Owner::orderBy('name')->get(),
        ]);
    }

    public function update(ApartmentRequest $req, Apartment $apartment) {
        $data = $this->normalizeApartmentData($req);
        $current = $apartment->photos ?? [];
        if ($req->hasFile('photos')) {
            $current = [];
            foreach ($req->file('photos') as $f) {
                $current[] = $f->store('apartments', 'public');
            }
        }
        $data['photos'] = $current;
        $apartment->update($data);
        return redirect()->route('admin.apartments.index')->with('ok', 'Inmueble actualizado');
    }

    private function normalizeApartmentData(ApartmentRequest $req): array
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

    public function destroy(Apartment $apartment) {
        // Para borrar archivos: foreach (($apartment->photos ?? []) as $p) Storage::disk('public')->delete($p);
        $apartment->delete();
        return back()->with('ok', 'Inmueble eliminado');
    }
}
