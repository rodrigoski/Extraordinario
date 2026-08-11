<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($appointment->id) ? 'Editar cita' : 'Nueva cita' }}
        </h2>
    </x-slot>

    @php
        $prefix = auth()->user()->role === 'admin' ? 'admin' : 'staff';
        $startTime = $appointment->start_time ? \Illuminate\Support\Carbon::parse($appointment->start_time)->format('H:i') : '';
    @endphp

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ isset($appointment->id) ? route($prefix.'.appointments.update', $appointment) : route($prefix.'.appointments.store') }}">
                    @csrf
                    @if(isset($appointment->id))
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cliente</label>
                            <select name="client_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id', $appointment->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }} {{ $client->last_name }}</option>
                                @endforeach
                            </select>
                            @error('client_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Empleado</label>
                            <select name="employee_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id', $appointment->employee_id) == $employee->id ? 'selected' : '' }}>{{ $employee->name }} {{ $employee->last_name }} ({{ $employee->position }})</option>
                                @endforeach
                            </select>
                            @error('employee_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Servicio</label>
                            <select name="service_id" id="service_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" data-duration="{{ $service->duration }}" data-price="{{ $service->price }}" {{ old('service_id', $appointment->service_id) == $service->id ? 'selected' : '' }}>{{ $service->name }} ({{ $service->duration }} min)</option>
                                @endforeach
                            </select>
                            @error('service_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha</label>
                            <input type="date" name="appointment_date" value="{{ old('appointment_date', optional($appointment->appointment_date)->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('appointment_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Hora inicio</label>
                            <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $startTime) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('start_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado</label>
                            <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="pending" {{ old('status', $appointment->status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>Confirmada</option>
                                <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Completada</option>
                                <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                            @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Notas</label>
                            <textarea name="notes" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('notes', $appointment->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4 bg-gray-50 rounded-md p-3 text-sm text-gray-700">
                        <span id="duration_info">Selecciona un servicio para ver su duración.</span>
                        <span id="end_time_info" class="ml-2 text-gray-500"></span>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route($prefix.'.appointments.index') }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-md">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const serviceSelect = document.getElementById('service_id');
            const startTimeInput = document.getElementById('start_time');
            const durationInfo = document.getElementById('duration_info');
            const endTimeInfo = document.getElementById('end_time_info');

            function updateInfo() {
                const option = serviceSelect.options[serviceSelect.selectedIndex];
                const duration = option ? parseInt(option.dataset.duration || '0', 10) : 0;
                durationInfo.textContent = duration ? 'Duración del servicio: ' + duration + ' minutos.' : 'Selecciona un servicio para ver su duración.';
                endTimeInfo.textContent = '';

                if (duration && startTimeInput.value) {
                    const [h, m] = startTimeInput.value.split(':').map(Number);
                    const total = h * 60 + m + duration;
                    const endH = String(Math.floor(total / 60) % 24).padStart(2, '0');
                    const endM = String(total % 60).padStart(2, '0');
                    endTimeInfo.textContent = 'Hora estimada de fin: ' + endH + ':' + endM;
                }
            }

            serviceSelect.addEventListener('change', updateInfo);
            startTimeInput.addEventListener('input', updateInfo);
            updateInfo();
        });
    </script>
</x-app-layout>
