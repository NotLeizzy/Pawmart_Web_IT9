<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Creative Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;700&family=Fredoka:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Fredoka', sans-serif;
            }
            h1, h2, h3, h4, h5, h6 {
                font-family: 'Comfortaa', sans-serif;
                font-weight: 700;
            }
            .btn-primary, .btn-secondary {
                font-family: 'Poppins', sans-serif;
                font-weight: 600;
            }
            .brand-text {
                font-family: 'Comfortaa', sans-serif;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-amber-50 via-orange-50 to-pink-50 min-h-screen">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <!-- Decorative Background Elements -->
            <div class="absolute top-0 left-0 w-96 h-96 bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-pink-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>

            <!-- Logo & Brand -->
            <div class="relative z-10 mb-8">
                <a href="/" class="flex flex-col items-center gap-3">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-paw text-white text-2xl"></i>
                    </div>
                    <h1 class="brand-text text-3xl font-bold bg-gradient-to-r from-amber-600 via-orange-500 to-pink-500 bg-clip-text text-transparent">Pawmart</h1>
                </a>
                <p class="text-center text-gray-600 text-sm mt-2">Your Trusted Pet Shop</p>
            </div>

            <!-- Main Content Card -->
            <div class="relative z-10 w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-2xl rounded-3xl overflow-hidden">
                <!-- Card Decoration -->
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 via-orange-400 to-pink-400"></div>
                <div class="absolute -top-2 right-8 text-5xl opacity-10">🐾</div>
                <div class="absolute -bottom-2 left-8 text-5xl opacity-10">🐱</div>

                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="relative z-10 mt-8 text-center text-sm text-gray-600">
                <p>© 2026 Pawmart. All rights reserved. 🐾</p>
            </div>
        </div>
    </body>
</html>
