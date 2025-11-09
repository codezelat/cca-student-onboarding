<x-guest-layout>
    <!-- Page Title -->
    <div class="mb-6 text-center">
        <h2 class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent mb-2">
            Welcome Back
        </h2>
        <p class="text-gray-600">Sign in to your account to continue</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-semibold mb-2" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                </div>
                <x-text-input id="email" class="block mt-1 w-full pl-11 pr-4 py-3 bg-white/50 border-white/60 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" 
                              type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                              placeholder="you@example.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold mb-2" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <x-text-input id="password" class="block mt-1 w-full pl-11 pr-4 py-3 bg-white/50 border-white/60 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                type="password"
                                name="password"
                                required autocomplete="current-password" 
                                placeholder="Enter your password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" 
                       class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500 focus:ring-2" 
                       name="remember">
                <span class="ms-2 text-sm text-gray-600 group-hover:text-gray-800 transition-colors">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-primary-600 hover:text-secondary-600 font-medium transition-colors" 
                   href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="space-y-4">
            <x-primary-button class="w-full justify-center px-6 py-3.5 bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 rounded-xl font-semibold text-base shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                {{ __('Sign in') }}
            </x-primary-button>

            @if (Route::has('register'))
                <p class="text-center text-sm text-gray-600">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-semibold text-primary-600 hover:text-secondary-600 transition-colors">
                        Create one now
                    </a>
                </p>
            @endif
        </div>
    </form>
</x-guest-layout>
