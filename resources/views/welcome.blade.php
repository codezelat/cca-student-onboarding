<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased overflow-x-hidden">
        <!-- Animated Background with Liquid Gradient Blobs -->
        <div class="fixed inset-0 bg-gradient-to-br from-violet-50 via-purple-50 to-indigo-50 overflow-hidden">
            <!-- Animated Blobs -->
            <div class="absolute top-0 -left-4 w-72 h-72 bg-violet-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-72 h-72 bg-purple-400 rounded-full mix-blend-multiply filter blur-xl opacity-60 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
            <div class="absolute bottom-20 right-20 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-60 animate-blob"></div>
        </div>

        <!-- Main Content -->
        <div class="relative min-h-screen">
            <!-- Navigation -->
            @if (Route::has('login'))
                <nav class="relative z-10 p-6 lg:p-8">
                    <div class="max-w-7xl mx-auto flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-xl">L</span>
                            </div>
                            <span class="text-2xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                                {{ config('app.name', 'Laravel') }}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" 
                                   class="px-6 py-2.5 rounded-xl bg-white/40 backdrop-blur-md border border-white/60 
                                          text-primary-700 font-medium hover:bg-white/60 transition-all duration-300 
                                          shadow-lg hover:shadow-xl hover:scale-105">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="px-6 py-2.5 rounded-xl text-primary-700 font-medium hover:bg-white/40 
                                          backdrop-blur-sm transition-all duration-300">
                                    Log in
                                </a>

                                <a href="{{ route('cca.register') }}" 
                                   class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-primary-500 to-secondary-500 
                                          text-white font-medium hover:from-primary-600 hover:to-secondary-600 
                                          transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                                    Apply Now
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" 
                                       class="px-6 py-2.5 rounded-xl bg-white/40 backdrop-blur-md border border-white/60 
                                              text-primary-700 font-medium hover:bg-white/60 transition-all duration-300">
                                        Staff Login
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </nav>
            @endif

            <!-- Hero Section -->
            <div class="relative z-10 px-6 lg:px-8 py-12 lg:py-20">
                <div class="max-w-7xl mx-auto">
                    <div class="grid lg:grid-cols-2 gap-12 items-center">
                        <!-- Left Column - Content -->
                        <div class="space-y-8">
                            <div class="space-y-4">
                                <h1 class="text-5xl lg:text-7xl font-bold leading-tight">
                                    <span class="bg-gradient-to-r from-primary-600 via-secondary-600 to-primary-700 bg-clip-text text-transparent">
                                        Build Amazing
                                    </span>
                                    <br>
                                    <span class="text-gray-800">
                                        Experiences
                                    </span>
                                </h1>
                                <p class="text-xl text-gray-600 leading-relaxed">
                                    Create stunning web applications with modern design and powerful features. 
                                    Get started in minutes with our beautiful, responsive templates.
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-4">
                                <a href="{{ route('cca.register') }}" 
                                   class="px-8 py-4 rounded-2xl bg-gradient-to-r from-primary-500 to-secondary-500 
                                          text-white font-semibold hover:from-primary-600 hover:to-secondary-600 
                                          transition-all duration-300 shadow-2xl hover:shadow-primary-500/50 
                                          hover:scale-105 inline-flex items-center gap-2">
                                    Apply for Programs
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </a>
                                
                                @auth
                                    <a href="{{ url('/dashboard') }}" 
                                       class="px-8 py-4 rounded-2xl bg-white/40 backdrop-blur-md border border-white/60 
                                              text-primary-700 font-semibold hover:bg-white/60 transition-all duration-300 
                                              shadow-lg hover:shadow-xl hover:scale-105 inline-flex items-center gap-2">
                                        Dashboard
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="px-8 py-4 rounded-2xl bg-white/40 backdrop-blur-md border border-white/60 
                                              text-primary-700 font-semibold hover:bg-white/60 transition-all duration-300 
                                              shadow-lg hover:shadow-xl hover:scale-105 inline-flex items-center gap-2">
                                        Staff Portal
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                        </svg>
                                    </a>
                                @endauth
                            </div>

                            <!-- Stats -->
                            <div class="grid grid-cols-3 gap-6 pt-8">
                                <div class="text-center">
                                    <div class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">10k+</div>
                                    <div class="text-sm text-gray-600 mt-1">Active Users</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">99%</div>
                                    <div class="text-sm text-gray-600 mt-1">Satisfaction</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">24/7</div>
                                    <div class="text-sm text-gray-600 mt-1">Support</div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Glassmorphic Cards -->
                        <div class="relative">
                            <div class="space-y-6">
                                <!-- Feature Card 1 -->
                                <div class="group relative p-8 rounded-3xl bg-white/30 backdrop-blur-lg border border-white/60 
                                            shadow-2xl hover:shadow-primary-500/20 transition-all duration-500 hover:scale-105 
                                            hover:bg-white/40">
                                    <div class="absolute inset-0 rounded-3xl bg-gradient-to-br from-primary-500/10 to-secondary-500/10 
                                                opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                    <div class="relative">
                                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-500 to-secondary-500 
                                                    flex items-center justify-center mb-4 shadow-lg group-hover:scale-110 
                                                    transition-transform duration-300">
                                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Lightning Fast</h3>
                                        <p class="text-gray-600">Built with performance in mind. Experience blazing-fast load times and smooth interactions.</p>
                                    </div>
                                </div>

                                <!-- Feature Card 2 -->
                                <div class="group relative p-8 rounded-3xl bg-white/30 backdrop-blur-lg border border-white/60 
                                            shadow-2xl hover:shadow-secondary-500/20 transition-all duration-500 hover:scale-105 
                                            hover:bg-white/40 ml-12">
                                    <div class="absolute inset-0 rounded-3xl bg-gradient-to-br from-secondary-500/10 to-primary-500/10 
                                                opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                    <div class="relative">
                                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-secondary-500 to-primary-600 
                                                    flex items-center justify-center mb-4 shadow-lg group-hover:scale-110 
                                                    transition-transform duration-300">
                                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Fully Customizable</h3>
                                        <p class="text-gray-600">Tailor every aspect to your needs with our flexible and intuitive design system.</p>
                                    </div>
                                </div>

                                <!-- Feature Card 3 -->
                                <div class="group relative p-8 rounded-3xl bg-white/30 backdrop-blur-lg border border-white/60 
                                            shadow-2xl hover:shadow-primary-500/20 transition-all duration-500 hover:scale-105 
                                            hover:bg-white/40">
                                    <div class="absolute inset-0 rounded-3xl bg-gradient-to-br from-primary-500/10 to-secondary-500/10 
                                                opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                    <div class="relative">
                                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-500 to-secondary-500 
                                                    flex items-center justify-center mb-4 shadow-lg group-hover:scale-110 
                                                    transition-transform duration-300">
                                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Secure & Reliable</h3>
                                        <p class="text-gray-600">Enterprise-grade security with regular updates and comprehensive testing.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="relative z-10 px-6 lg:px-8 py-12 lg:py-20">
                <div class="max-w-7xl mx-auto">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl lg:text-5xl font-bold mb-4">
                            <span class="bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                                Everything You Need
                            </span>
                        </h2>
                        <p class="text-xl text-gray-600">Powerful features to help you build faster</p>
                    </div>

                    <div class="grid md:grid-cols-3 gap-8">
                        @foreach([
                            ['icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01', 'title' => 'Modern Design', 'desc' => 'Beautiful, responsive designs that work perfectly on all devices'],
                            ['icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4', 'title' => 'Developer First', 'desc' => 'Clean code architecture with best practices and documentation'],
                            ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Super Fast', 'desc' => 'Optimized performance for the best user experience'],
                            ['icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4', 'title' => 'Customizable', 'desc' => 'Easily customize colors, layouts, and components'],
                            ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Secure', 'desc' => 'Built with security best practices and regular updates'],
                            ['icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z', 'title' => 'Team Ready', 'desc' => 'Collaboration tools and features for teams of all sizes'],
                        ] as $feature)
                            <div class="group p-6 rounded-2xl bg-white/30 backdrop-blur-lg border border-white/60 
                                        hover:bg-white/40 transition-all duration-300 hover:scale-105 hover:shadow-xl">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-secondary-500 
                                            flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $feature['title'] }}</h3>
                                <p class="text-gray-600 text-sm">{{ $feature['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="relative z-10 px-6 lg:px-8 py-20">
                <div class="max-w-4xl mx-auto text-center">
                    <div class="p-12 rounded-3xl bg-white/40 backdrop-blur-xl border border-white/60 shadow-2xl">
                        <h2 class="text-4xl lg:text-5xl font-bold mb-6">
                            <span class="bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                                Ready to Get Started?
                            </span>
                        </h2>
                        <p class="text-xl text-gray-600 mb-8">
                            Join our programs and take your career to the next level
                        </p>
                        <a href="{{ route('cca.register') }}" 
                           class="inline-flex items-center gap-2 px-10 py-5 rounded-2xl bg-gradient-to-r from-primary-500 to-secondary-500 
                                  text-white text-lg font-semibold hover:from-primary-600 hover:to-secondary-600 
                                  transition-all duration-300 shadow-2xl hover:shadow-primary-500/50 hover:scale-105">
                            Apply Now
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    </div>
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
