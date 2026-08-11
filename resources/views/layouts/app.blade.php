<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen lg:flex">

            <!-- Overlay para móvil -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-gray-900/50 lg:hidden" style="display: none;"></div>

            <!-- Sidebar -->
            <aside
                class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 flex flex-col transform transition-transform duration-200 lg:static lg:translate-x-0 lg:w-64"
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

                <div class="flex items-center justify-between px-5 h-16 border-b border-gray-800">
                    <a href="{{ route('dashboard') }}" class="text-lg font-bold text-white">BarberManager</a>
                </div>

                @include('layouts.sidebar-menu')

                <div class="px-4 py-4 border-t border-gray-800">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0">
                            <div class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-400">{{ ucfirst(auth()->user()->role) }}</div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-xs text-gray-400 hover:text-white">Salir</button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Contenido principal -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Barra superior -->
                <header class="bg-white shadow-sm">
                    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                        <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        <div class="hidden sm:flex items-center gap-2">
                            <span class="text-sm text-gray-500">Bienvenido,</span>
                            <span class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('profile.edit') }}" class="text-sm text-gray-600 hover:text-gray-900">Mi perfil</a>
                        </div>
                    </div>
                </header>

                @isset($header)
                    <div class="bg-white shadow-sm">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </div>
                @endisset

                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
