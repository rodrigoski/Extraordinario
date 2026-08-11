<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(Auth::user()->role === 'admin')
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-lg shadow p-5">
                        <div class="text-sm text-gray-500">Clientes</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $clients ?? 0 }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-5">
                        <div class="text-sm text-gray-500">Empleados</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $employees ?? 0 }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-5">
                        <div class="text-sm text-gray-500">Citas hoy</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $appointmentsToday ?? 0 }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-5">
                        <div class="text-sm text-gray-500">Pendientes</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $pendingAppointments ?? 0 }}</div>
                    </div>
                </div>
            @elseif(Auth::user()->role === 'staff')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white rounded-lg shadow p-5">
                        <div class="text-sm text-gray-500">Mis citas hoy</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $myAppointmentsToday ?? 0 }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-5">
                        <div class="text-sm text-gray-500">Pendientes</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $pendingAppointments ?? 0 }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-5">
                        <div class="text-sm text-gray-500">Completadas</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $completedAppointments ?? 0 }}</div>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white rounded-lg shadow p-5">
                        <div class="text-sm text-gray-500">Próxima cita</div>
                        <div class="mt-2 text-lg font-bold text-gray-900">
                            @if($nextAppointment)
                                {{ $nextAppointment->appointment_date->format('d/m/Y') }}
                            @else
                                Sin cita
                            @endif
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-5">
                        <div class="text-sm text-gray-500">Total de citas</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalAppointments ?? 0 }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-5">
                        <div class="text-sm text-gray-500">Último servicio</div>
                        <div class="mt-2 text-lg font-bold text-gray-900">
                            {{ $lastService?->service?->name ?? 'Sin historial' }}
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-lg font-semibold mb-2">Bienvenido a BarberManager</p>
                    <p class="text-gray-600">Gestiona clientes, empleados, servicios y citas desde un único panel centralizado.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
