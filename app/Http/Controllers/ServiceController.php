<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Service::class);

        $services = Service::latest()->paginate(15);

        return view('services.index', compact('services'));
    }

    public function create(): View
    {
        $this->authorize('create', Service::class);

        return view('services.form', ['service' => new Service]);
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $this->authorize('create', Service::class);

        Service::create($request->validated());

        return redirect()->route('admin.services.index')->with('success', 'Servicio registrado correctamente.');
    }

    public function show(Service $service): View
    {
        $this->authorize('view', $service);

        return view('services.show', compact('service'));
    }

    public function edit(Service $service): View
    {
        $this->authorize('update', $service);

        return view('services.form', ['service' => $service]);
    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $this->authorize('update', $service);

        $service->update($request->validated());

        return redirect()->route('admin.services.index')->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $this->authorize('delete', $service);

        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Servicio eliminado correctamente.');
    }

    public function listForClient(): View
    {
        $services = Service::where('active', true)->get();

        return view('services.client', compact('services'));
    }
}
