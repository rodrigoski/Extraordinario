<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalle del servicio
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">{{ $service->name }}</h3>
                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $service->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $service->active ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Duración</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $service->duration }} minutos</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Precio</dt>
                        <dd class="mt-1 text-sm text-gray-900">${{ number_format($service->price, 2) }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-xs font-medium text-gray-500 uppercase">Descripción</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $service->description ?? 'Sin descripción' }}</dd>
                    </div>
                </dl>

                <div class="mt-6 flex justify-end gap-3">
                    @php($prefix = auth()->user()->role === 'admin' ? 'admin' : 'staff')
                    <a href="{{ route($prefix.'.services.index') }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700">Volver</a>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.services.edit', $service) }}" class="px-4 py-2 bg-gray-900 text-white rounded-md">Editar</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
