<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalle del cliente
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

            {{-- Encabezado del cliente --}}
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-full bg-gray-900 text-white flex items-center justify-center font-bold text-lg">
                        {{ strtoupper(substr($client->name, 0, 1)) }}{{ strtoupper(substr($client->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $client->name }} {{ $client->last_name }}</h3>
                        <p class="text-sm text-gray-600">{{ $client->phone }} &middot; {{ $client->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Activo</span>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.clients.edit', $client) }}" class="px-4 py-2 bg-gray-900 text-white rounded-md text-sm">Editar</a>
                    @endif
                </div>
            </div>

            {{-- Tabs --}}
            <div x-data="{ tab: 'info' }" class="bg-white rounded-lg shadow-sm">
                <div class="border-b border-gray-200 flex">
                    <button @click="tab = 'info'" :class="tab === 'info' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-3 text-sm font-medium border-b-2">
                        Información personal
                    </button>
                    <button @click="tab = 'history'" :class="tab === 'history' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-3 text-sm font-medium border-b-2">
                        Historial de servicios
                    </button>
                </div>

                {{-- Tab 1: Información personal --}}
                <div x-show="tab === 'info'" class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase">Nombre</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $client->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase">Apellido</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $client->last_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $client->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase">Teléfono</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $client->phone }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase">Dirección</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $client->address ?? 'Sin especificar' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase">Fecha de nacimiento</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $client->birth_date?->format('d/m/Y') ?? 'Sin especificar' }}</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-xs font-medium text-gray-500 uppercase">Notas</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $client->notes ?? 'Sin notas' }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Tab 2: Historial de servicios --}}
                <div x-show="tab === 'history'" class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Empleado</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($client->appointments as $appointment)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $appointment->appointment_date->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $appointment->service?->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $appointment->employee?->name }} {{ $appointment->employee?->last_name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">${{ number_format($appointment->service?->price ?? 0, 2) }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $appointment->status === 'completed' ? 'bg-green-100 text-green-700' : ($appointment->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm font-medium whitespace-nowrap">
                                        <a href="{{ route($prefix.'.appointments.show', $appointment) }}" class="text-blue-600 hover:text-blue-900 mr-3">Ver cita</a>
                                        @if($appointment->pdf_path)
                                            <a href="{{ route($prefix.'.appointments.pdf', $appointment) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">PDF</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-4 text-sm text-gray-500">Este cliente no tiene citas registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
