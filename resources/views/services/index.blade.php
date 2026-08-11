<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Servicios
        </h2>
    </x-slot>

    @php($prefix = auth()->user()->role === 'admin' ? 'admin' : 'staff')

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(auth()->user()->role === 'admin')
                <div class="mb-4 flex justify-end">
                    <a href="{{ route('admin.services.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        Nuevo servicio
                    </a>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duración</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($services as $service)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $service->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $service->duration }} min</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${{ number_format($service->price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $service->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $service->active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route($prefix.'.services.show', $service) }}" class="text-blue-600 hover:text-blue-900 mr-3">Ver</a>
                                    @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('admin.services.edit', $service) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Eliminar servicio?')">Eliminar</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-4 text-sm text-gray-500">No hay servicios registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
