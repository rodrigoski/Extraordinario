<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalle del empleado
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="h-14 w-14 rounded-full bg-gray-900 text-white flex items-center justify-center font-bold text-lg">
                            {{ strtoupper(substr($employee->name, 0, 1)) }}{{ strtoupper(substr($employee->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $employee->name }} {{ $employee->last_name }}</h3>
                            <p class="text-sm text-gray-600">{{ $employee->position }} &middot; {{ $employee->phone }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $employee->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $employee->active ? 'Activo' : 'Inactivo' }}
                        </span>
                        <a href="{{ route('admin.employees.edit', $employee) }}" class="px-4 py-2 bg-gray-900 text-white rounded-md text-sm">Editar</a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h4 class="font-semibold text-gray-900">Citas asignadas</h4>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($employee->appointments as $appointment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $appointment->appointment_date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $appointment->client->name }} {{ $appointment->client->last_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $appointment->service->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $appointment->start_time }} - {{ $appointment->end_time }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ ucfirst($appointment->status) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-sm text-gray-500">Este empleado no tiene citas asignadas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
