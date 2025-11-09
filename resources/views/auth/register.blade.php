<x-guest-layout>
    <!-- Page Title -->
    <div class="mb-6 text-center">
        <h2 class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent mb-2">
            Create Account
        </h2>
        <p class="text-gray-600">Join us and start building amazing things</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" class="text-gray-700 font-semibold mb-2" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <x-text-input id="name" class="block mt-1 w-full pl-11 pr-4 py-3 bg-white/50 border-white/60 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" 
                              type="text" name="name" :value="old('name')" required autofocus autocomplete="name" 
                              placeholder="John Doe" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

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
                              type="email" name="email" :value="old('email')" required autocomplete="username" 
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
                                required autocomplete="new-password" 
                                placeholder="Create a strong password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-700 font-semibold mb-2" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <x-text-input id="password_confirmation" class="block mt-1 w-full pl-11 pr-4 py-3 bg-white/50 border-white/60 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" 
                                placeholder="Confirm your password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div class="space-y-4 pt-2">
            <x-primary-button class="w-full justify-center px-6 py-3.5 bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 rounded-xl font-semibold text-base shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                {{ __('Create Account') }}
            </x-primary-button>

            <p class="text-center text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="font-semibold text-primary-600 hover:text-secondary-600 transition-colors">
                    Sign in instead
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
