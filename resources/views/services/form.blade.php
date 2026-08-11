<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($service->id) ? 'Editar servicio' : 'Nuevo servicio' }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ isset($service->id) ? route('admin.services.update', $service) : route('admin.services.store') }}">
                    @csrf
                    @if(isset($service->id))
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="name" value="{{ old('name', $service->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $service->description) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Duración (min)</label>
                            <input type="number" name="duration" value="{{ old('duration', $service->duration) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" min="15" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Precio</label>
                            <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $service->price) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div class="md:col-span-2 flex items-center">
                            <input type="checkbox" name="active" value="1" {{ old('active', $service->active) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label class="ml-2 block text-sm text-gray-900">Servicio activo</label>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('admin.services.index') }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-md">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
