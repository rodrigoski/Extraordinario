<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalle del usuario
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">{{ $user->name }}</h3>
                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-700">{{ ucfirst($user->role) }}</span>
                </div>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Rol</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($user->role) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Cliente vinculado</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->client ? $user->client->name.' '.$user->client->last_name : 'Ninguno' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Empleado vinculado</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->employee ? $user->employee->name.' '.$user->employee->last_name : 'Ninguno' }}</dd>
                    </div>
                </dl>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700">Volver</a>
                    <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-gray-900 text-white rounded-md">Editar</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
