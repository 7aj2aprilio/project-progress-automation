<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Telkom Property') }}</title>
        <link rel="icon" href="{{ asset('images/logoTelkom.webp') }}" type="image/webp">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" x-data="{ sidebarOpen: false }" @toggle-sidebar.window="sidebarOpen = !sidebarOpen">
        <div class="min-h-screen bg-slate-50 flex">
            {{-- Sidebar --}}
            <div x-data="{ mobileOpen: sidebarOpen }" x-effect="mobileOpen = sidebarOpen">
                @include('layouts.sidebar')
            </div>

            {{-- Main Content --}}
            <div class="flex-1 md:ml-64 flex flex-col min-h-screen min-w-0">
                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow-sm border-b border-slate-200">
                        <div class="py-4 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1 min-w-0">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
