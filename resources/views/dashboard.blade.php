<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between bg-gray-900 text-white rounded-lg shadow p-5">
                <div>
                    <p class="text-lg font-semibold">Bienvenido, {{ Auth::user()->name }}</p>
                    <p class="text-sm text-gray-300">{{ now()->format('l, d/m/Y') }}</p>
                </div>
                @if(Auth::user()->role === 'cliente')
                    <a href="{{ route('cliente.appointments.create') }}" class="px-4 py-2 bg-white text-gray-900 rounded-md text-sm font-medium hover:bg-gray-200">
                        + Reservar cita
                    </a>
                @endif
            </div>

            @if(Auth::user()->role === 'admin')
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-lg shadow p-5 border-t-4 border-indigo-500">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">Clientes</div>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 text-sm font-bold">C</span>
                        </div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $clients ?? 0 }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-5 border-t-4 border-emerald-500">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">Empleados activos</div>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 text-sm font-bold">E</span>
                        </div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $employees ?? 0 }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-5 border-t-4 border-sky-500">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">Citas hoy</div>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-sky-100 text-sky-600 text-sm font-bold">H</span>
                        </div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $appointmentsToday ?? 0 }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-5 border-t-4 border-amber-500">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">Pendientes</div>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-100 text-amber-600 text-sm font-bold">P</span>
                        </div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $pendingAppointments ?? 0 }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div class="bg-white rounded-lg shadow p-5 border-t-4 border-fuchsia-500">
                        <div class="text-sm text-gray-500">Servicios activos</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $services ?? 0 }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-5 border-t-4 border-rose-500">
                        <div class="text-sm text-gray-500">Ventas de hoy</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">${{ number_format($salesToday ?? 0, 2) }}</div>
                    </div>
                </div>
            @elseif(Auth::user()->role === 'staff')
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white rounded-lg shadow p-5 border-t-4 border-sky-500">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">Mis citas hoy</div>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-sky-100 text-sky-600 text-sm font-bold">H</span>
                        </div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $myAppointmentsToday ?? 0 }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-5 border-t-4 border-amber-500">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">Pendientes</div>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-100 text-amber-600 text-sm font-bold">P</span>
                        </div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $pendingAppointments ?? 0 }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-5 border-t-4 border-emerald-500">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">Completadas</div>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 text-sm font-bold">C</span>
                        </div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $completedAppointments ?? 0 }}</div>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white rounded-lg shadow p-5 border-t-4 border-indigo-500">
                        <div class="text-sm text-gray-500">Próxima cita</div>
                        <div class="mt-2 text-lg font-bold text-gray-900">
                            @if($nextAppointment)
                                {{ $nextAppointment->appointment_date->format('d/m/Y') }} a las {{ $nextAppointment->start_time }}
                            @else
                                Sin cita
                            @endif
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-5 border-t-4 border-sky-500">
                        <div class="text-sm text-gray-500">Total de citas</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalAppointments ?? 0 }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-5 border-t-4 border-emerald-500">
                        <div class="text-sm text-gray-500">Último servicio</div>
                        <div class="mt-2 text-lg font-bold text-gray-900">{{ $lastService?->service?->name ?? 'Sin historial' }}</div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ Auth::user()->role === 'staff' ? 'Mis próximas citas' : 'Próximas citas' }}
                        </h3>
                        @if($upcomingAppointments->count() > 0)
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.appointments.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Ver todas</a>
                            @elseif(Auth::user()->role === 'staff')
                                <a href="{{ route('staff.appointments.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Ver todas</a>
                            @else
                                <a href="{{ route('cliente.appointments') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Ver todas</a>
                            @endif
                        @endif
                    </div>

                    @if($upcomingAppointments->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                                    @if(Auth::user()->role !== 'cliente')
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                                    @endif
                                    @if(Auth::user()->role === 'admin')
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Empleado</th>
                                    @endif
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hora</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($upcomingAppointments as $appointment)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $appointment->service?->name }}</td>
                                        @if(Auth::user()->role !== 'cliente')
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $appointment->client?->name }} {{ $appointment->client?->last_name }}</td>
                                        @endif
                                        @if(Auth::user()->role === 'admin')
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $appointment->employee?->name }} {{ $appointment->employee?->last_name }}</td>
                                        @endif
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $appointment->appointment_date->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $appointment->start_time }} - {{ $appointment->end_time }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $appointment->status === 'completed' ? 'bg-green-100 text-green-700' : ($appointment->status === 'cancelled' ? 'bg-red-100 text-red-700' : ($appointment->status === 'confirmed' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700')) }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-sm text-gray-500">No hay próximas citas.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
