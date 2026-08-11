<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Servicios disponibles
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($services as $service)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-semibold text-gray-900">{{ $service->name }}</h3>
                            <span class="text-sm font-bold text-gray-900">${{ number_format($service->price, 2) }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">{{ $service->description ?? 'Sin descripción' }}</p>
                        <p class="text-xs text-gray-500">Duración: {{ $service->duration }} minutos</p>
                    </div>
                @empty
                    <div class="md:col-span-2 bg-white rounded-lg shadow-sm p-6 text-sm text-gray-500">
                        No hay servicios disponibles.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
