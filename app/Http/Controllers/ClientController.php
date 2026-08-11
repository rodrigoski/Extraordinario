<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Client::class);

        $search = $request->query('search');

        $clients = Client::with('user')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('clients.index', compact('clients', 'search'));
    }

    public function create(): View
    {
        $this->authorize('create', Client::class);

        return view('clients.form', [
            'client' => new Client,
            'users' => User::where('role', 'cliente')->get(),
        ]);
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        $this->authorize('create', Client::class);

        Client::create($request->validated());

        return redirect()->route('admin.clients.index')->with('success', 'Cliente registrado correctamente.');
    }

    public function show(Client $client): View
    {
        $this->authorize('view', $client);

        $client->load(['user', 'appointments.service', 'appointments.employee']);

        return view('clients.show', compact('client'));
    }

    public function edit(Client $client): View
    {
        $this->authorize('update', $client);

        return view('clients.form', [
            'client' => $client,
            'users' => User::where('role', 'cliente')->get(),
        ]);
    }

    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $this->authorize('update', $client);

        $client->update($request->validated());

        return redirect()->route('admin.clients.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $this->authorize('delete', $client);

        // Soft delete: el registro no se elimina físicamente.
        $client->delete();

        return redirect()->route('admin.clients.index')->with('success', 'Cliente eliminado correctamente.');
    }
}
