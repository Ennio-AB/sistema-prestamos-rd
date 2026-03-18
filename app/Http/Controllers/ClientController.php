<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::withCount('loans');

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('cedula', 'like', "%{$buscar}%")
                  ->orWhere('telefono', 'like', "%{$buscar}%");
            });
        }

        $clients = $query->latest()->paginate(20)->withQueryString();

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:100',
            'cedula'      => 'required|string|size:11|unique:clients,cedula',
            'telefono'    => 'nullable|string|max:15',
            'direccion'   => 'nullable|string|max:255',
            'email'       => 'nullable|email|max:100',
            'trabajo'     => 'nullable|string|max:150',
            'referencias' => 'nullable|string',
        ]);

        $client = Client::create($data);

        return redirect()->route('clients.show', $client)
            ->with('success', "Cliente {$client->nombre} creado exitosamente.");
    }

    public function show(Client $client)
    {
        $client->load(['loans' => function ($q) {
            $q->with('installments')->latest();
        }]);

        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:100',
            'cedula'      => 'required|string|size:11|unique:clients,cedula,' . $client->id,
            'telefono'    => 'nullable|string|max:15',
            'direccion'   => 'nullable|string|max:255',
            'email'       => 'nullable|email|max:100',
            'trabajo'     => 'nullable|string|max:150',
            'referencias' => 'nullable|string',
            'activo'      => 'boolean',
        ]);

        $client->update($data);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Client $client)
    {
        if ($client->loans()->exists()) {
            return back()->with('error', 'No se puede eliminar un cliente con préstamos registrados.');
        }

        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Cliente eliminado exitosamente.');
    }
}
