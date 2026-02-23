<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index(Request $r)
    {
        $q = Client::query();

        if ($r->filled('gender')) {
            $q->where('gender', $r->gender);
        }

        // Usar has() para respetar el valor "0"
        if ($r->has('is_verified') && $r->is_verified !== '') {
            $q->where('is_verified', (bool) $r->is_verified);
        }

        if ($r->filled('s')) {
            $s = $r->s;
            $q->where(function ($x) use ($s) {
                $x->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $clients = $q->orderBy('id', 'desc')->get();
        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.form', ['client' => new Client()]);
    }

    public function store(ClientRequest $req)
    {
        $data = $req->validated();

        if ($req->hasFile('id_scan')) {
            $data['id_scan_path'] = $req->file('id_scan')->store('clients', 'public');
        }

        // Evitar intentar guardar 'id_scan' (no existe en la BD)
        unset($data['id_scan']);

        Client::create($data);

        return redirect()
            ->route('admin.clients.index')
            ->with('ok', 'Cliente creado');
    }

    public function edit(Client $client)
    {
        return view('admin.clients.form', compact('client'));
    }

    public function update(ClientRequest $req, Client $client)
    {
        $data = $req->validated();

        if ($req->hasFile('id_scan')) {
            if ($client->id_scan_path) {
                Storage::disk('public')->delete($client->id_scan_path);
            }
            $data['id_scan_path'] = $req->file('id_scan')->store('clients', 'public');
        }

        // Igual aquí: quitar la clave que no existe en la tabla
        unset($data['id_scan']);

        $client->update($data);

        return redirect()
            ->route('admin.clients.index')
            ->with('ok', 'Cliente actualizado');
    }

    public function destroy(Client $client)
    {
        if ($client->id_scan_path) {
            Storage::disk('public')->delete($client->id_scan_path);
        }
        $client->delete();
        return back()->with('ok', 'Cliente eliminado');
    }
}
