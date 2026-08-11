<nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
    @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Dashboard
        </a>
        <a href="{{ route('admin.clients.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.clients.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Clientes
        </a>
        <a href="{{ route('admin.employees.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.employees.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Empleados
        </a>
        <a href="{{ route('admin.services.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.services.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Servicios
        </a>
        <a href="{{ route('admin.appointments.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.appointments.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Citas
        </a>
        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Usuarios
        </a>
    @elseif(auth()->user()->role === 'staff')
        <a href="{{ route('staff.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('dashboard') || request()->routeIs('staff.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Dashboard
        </a>
        <a href="{{ route('staff.clients.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('staff.clients.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Clientes
        </a>
        <a href="{{ route('staff.appointments.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('staff.appointments.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Mis citas
        </a>
        <a href="{{ route('staff.services.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('staff.services.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Servicios
        </a>
    @else
        <a href="{{ route('cliente.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('dashboard') || request()->routeIs('cliente.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Dashboard
        </a>
        <a href="{{ route('cliente.profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('cliente.profile.*') || request()->routeIs('profile.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Mi perfil
        </a>
        <a href="{{ route('cliente.appointments') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('cliente.appointments') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Mis citas
        </a>
        <a href="{{ route('cliente.appointments.create') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('cliente.appointments.create') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Reservar cita
        </a>
        <a href="{{ route('cliente.historial') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('cliente.historial') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Mi historial
        </a>
        <a href="{{ route('cliente.services') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('cliente.services') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Servicios
        </a>
    @endif
</nav>
