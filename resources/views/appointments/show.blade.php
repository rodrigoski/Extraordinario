<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalle de la cita
        </h2>
    </x-slot>

    @php($prefix = auth()->user()->role === 'admin' ? 'admin' : 'staff')

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Cita #{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}</h3>
                        <p class="text-sm text-gray-600">{{ $appointment->appointment_date->format('d/m/Y') }} &middot; {{ $appointment->start_time }} - {{ $appointment->end_time }}</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $appointment->status === 'completed' ? 'bg-green-100 text-green-700' : ($appointment->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </div>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Cliente</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $appointment->client->name }} {{ $appointment->client->last_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Teléfono</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $appointment->client->phone }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Servicio</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $appointment->service->name }} ({{ $appointment->service->duration }} min)</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Precio</dt>
                        <dd class="mt-1 text-sm text-gray-900">${{ number_format($appointment->service->price, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Empleado</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $appointment->employee->name }} {{ $appointment->employee->last_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Estado</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($appointment->status) }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-xs font-medium text-gray-500 uppercase">Notas</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $appointment->notes ?? 'Sin notas' }}</dd>
                    </div>
                </dl>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route($prefix.'.appointments.index') }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700">Volver</a>
                    <a href="{{ route($prefix.'.appointments.pdf', $appointment) }}" target="_blank" class="px-4 py-2 bg-emerald-600 text-white rounded-md">Descargar PDF</a>
                    @if(in_array(auth()->user()->role, ['admin', 'staff'], true))
                        <a href="{{ route($prefix.'.appointments.edit', $appointment) }}" class="px-4 py-2 bg-gray-900 text-white rounded-md">Editar</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
