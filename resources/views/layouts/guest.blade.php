<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex, nofollow">

        <title>{{ config('app.name', 'CCA') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/icon.png') }}">

        @if(app()->environment('production'))
        <!-- Google tag (gtag.js) -->
        <link rel="preconnect" href="https://www.googletagmanager.com">
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-YDEF398QWX"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-YDEF398QWX', {
                'cookie_domain': 'auto',
                'cookie_flags': 'SameSite=None;Secure'
            });
        </script>
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            // Refresh CSRF token every 30 minutes to prevent expiration
            setInterval(function() {
                fetch('/csrf-token', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => response.json())
                .then(data => {
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.token);
                }).catch(err => {
                    console.log('CSRF token refresh failed');
                });
            }, 30 * 60 * 1000); // 30 minutes
        </script>
    </head>
    <body class="font-sans antialiased overflow-x-hidden">
        <!-- Animated Background with Liquid Gradient Blobs -->
        <div class="fixed inset-0 bg-gradient-to-br from-violet-50 via-purple-50 to-indigo-50 overflow-hidden">
            <!-- Animated Blobs -->
            <div class="absolute top-0 -left-4 w-96 h-96 bg-violet-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-xl opacity-60 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-60 animate-blob"></div>
        </div>

        <!-- Main Content -->
        <div class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-6">
            <!-- Logo -->
            <div class="mb-8">
                <a href="/" class="flex items-center space-x-3 group">
                    <img src="{{ asset('images/icon.png') }}" 
                         alt="CCA" 
                         class="w-14 h-14 transition-transform duration-300 group-hover:scale-110">
                    <span class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                        {{ config('app.name', 'CCA') }}
                    </span>
                </a>
            </div>

            <!-- Glassmorphic Card -->
            <div class="w-full sm:max-w-md">
                <div class="px-8 py-10 bg-white/40 backdrop-blur-xl border border-white/60 shadow-2xl rounded-3xl overflow-hidden">
                    {{ $slot }}
                </div>

                <!-- Back to Home Link -->
                <div class="mt-6 text-center">
                    <a href="/" class="text-primary-600 hover:text-secondary-600 font-medium transition-colors duration-300 inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>

        <style>
            .animation-delay-2000 {
                animation-delay: 2s;
            }
            .animation-delay-4000 {
                animation-delay: 4s;
            }
        </style>
    </body>
</html>
