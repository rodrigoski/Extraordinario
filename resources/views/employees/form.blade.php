<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($employee->id) ? 'Editar empleado' : 'Nuevo empleado' }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ isset($employee->id) ? route('admin.employees.update', $employee) : route('admin.employees.store') }}">
                    @csrf
                    @if(isset($employee->id))
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="name" value="{{ old('name', $employee->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Apellido</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cargo</label>
                            <input type="text" name="position" value="{{ old('position', $employee->position) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Usuario asociado</label>
                            <select name="user_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $employee->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center justify-start mt-6">
                            <input type="checkbox" name="active" value="1" {{ old('active', $employee->active) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label class="ml-2 block text-sm text-gray-900">Empleado activo</label>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('admin.employees.index') }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-md">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
