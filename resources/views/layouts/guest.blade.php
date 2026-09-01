<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Telkom Property') }} — Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="min-h-screen flex">
            {{-- Left Panel — Branding --}}
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700 relative overflow-hidden">
                {{-- Decorative circles --}}
                <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary-600/20 rounded-full"></div>
                <div class="absolute -bottom-32 -right-32 w-[500px] h-[500px] bg-primary-600/10 rounded-full"></div>
                <div class="absolute top-1/4 right-10 w-48 h-48 bg-accent-500/10 rounded-full"></div>

                <div class="relative z-10 flex flex-col items-center justify-center w-full px-12">
                    <img src="{{ asset('images/logoTelkom.webp') }}" alt="Telkom Property" class="h-24 w-auto mb-8 drop-shadow-2xl">
                    <h1 class="text-3xl font-bold text-white text-center leading-tight">Project Progress<br>Automation</h1>
                    <p class="mt-4 text-primary-200 text-center text-sm max-w-sm">Sistem otomasi analisis kelayakan dan progres proyek untuk Telkom Property Indonesia.</p>
                    <div class="mt-8 flex items-center gap-2 text-primary-300 text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Secured & Reliable
                    </div>
                </div>
            </div>

            {{-- Right Panel — Form --}}
            <div class="flex-1 flex flex-col items-center justify-center px-6 py-12 bg-slate-50">
                {{-- Mobile logo --}}
                <div class="lg:hidden mb-8">
                    <img src="{{ asset('images/logoTelkom.webp') }}" alt="Telkom Property" class="h-16 w-auto mx-auto drop-shadow-md">
                </div>

                <div class="w-full sm:max-w-md">
                    <div class="bg-white shadow-xl rounded-2xl px-8 py-10 border border-slate-200">
                        {{ $slot }}
                    </div>
                    <p class="mt-6 text-center text-xs text-slate-400">&copy; {{ date('Y') }} Telkom Property Indonesia. All rights reserved.</p>
                </div>
            </div>
        </div>
    </body>
</html>
