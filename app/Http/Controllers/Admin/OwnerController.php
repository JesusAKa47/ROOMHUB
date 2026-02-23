<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OwnerRequest;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OwnerController extends Controller
{
    public function index(Request $r)
    {
        $q = Owner::query();

        if ($r->filled('type')) {
            $q->where('type', $r->type);
        }

        // Usar has() para que el valor "0" no se pierda (filled puede ignorarlo en algunos casos).
        if ($r->has('is_active') && $r->is_active !== '') {
            $q->where('is_active', (bool) $r->is_active);
        }

        if ($r->filled('s')) {
            $s = $r->s;
            $q->where(function ($x) use ($s) {
                $x->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $owners = $q->orderBy('id', 'desc')->get();
        return view('admin.owners.index', compact('owners'));
    }

    public function create()
    {
        return view('admin.owners.form', ['owner' => new Owner()]);
    }

    public function store(OwnerRequest $req)
    {
        $data = $req->validated();

        // Subir archivo y mapear a avatar_path
        if ($req->hasFile('avatar')) {
            $data['avatar_path'] = $req->file('avatar')->store('owners', 'public');
        }

        // Evitar intentar guardar 'avatar' (no existe en la BD)
        unset($data['avatar']);

        Owner::create($data);

        return redirect()
            ->route('admin.owners.index')
            ->with('ok', 'Dueño creado');
    }

    public function edit(Owner $owner)
    {
        return view('admin.owners.form', compact('owner'));
    }

    public function update(OwnerRequest $req, Owner $owner)
    {
        $data = $req->validated();

        if ($req->hasFile('avatar')) {
            if ($owner->avatar_path) {
                Storage::disk('public')->delete($owner->avatar_path);
            }
            $data['avatar_path'] = $req->file('avatar')->store('owners', 'public');
        }

        // Evitar intentar guardar 'avatar' (no existe en la BD)
        unset($data['avatar']);

        $owner->update($data);

        return redirect()
            ->route('admin.owners.index')
            ->with('ok', 'Dueño actualizado');
    }

    public function destroy(Owner $owner)
    {
        if ($owner->avatar_path) {
            Storage::disk('public')->delete($owner->avatar_path);
        }

        $owner->delete();

        return back()->with('ok', 'Dueño eliminado');
    }
}
