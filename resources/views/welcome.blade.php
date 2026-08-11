<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'BarberManager') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-900 text-white">
        <div class="min-h-screen flex flex-col items-center justify-center px-6">
            <div class="text-center max-w-2xl">
                <h1 class="text-4xl sm:text-6xl font-bold tracking-tight">BarberManager</h1>
                <p class="mt-4 text-lg text-gray-300">
                    Sistema de gestión de citas y servicios para tu barbería.
                    Administra clientes, empleados, servicios y citas desde un solo lugar.
                </p>
                <div class="mt-8 flex items-center justify-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-white text-gray-900 font-semibold rounded-md">Ir al panel</a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-3 bg-white text-gray-900 font-semibold rounded-md">Iniciar sesión</a>
                        <a href="{{ route('register') }}" class="px-6 py-3 border border-gray-600 font-semibold rounded-md hover:bg-gray-800">Crear cuenta</a>
                    @endauth
                </div>
            </div>
        </div>
    </body>
</html>
