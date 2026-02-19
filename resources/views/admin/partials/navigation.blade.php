@php
    $adminUser = Auth::guard('admin')->user();
    $adminName = $adminUser?->name ?? 'Administrator';
    $adminEmail = $adminUser?->email ?? 'N/A';
    $adminInitial = strtoupper(substr($adminName, 0, 1));
@endphp

<nav class="bg-white/60 backdrop-blur-xl border-b border-white/60 shadow-sm relative z-50" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 group">
                        <img src="{{ asset('images/icon.png') }}" 
                             alt="CCA Logo" 
                             class="w-10 h-10 transition-transform duration-300 group-hover:scale-110">
                        <span class="text-xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                            CCA Admin Area
                        </span>
                    </a>
                </div>
            </div>
            
            <!-- Profile Dropdown -->
            <div class="flex items-center space-x-4">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" 
                        class="flex items-center space-x-3 px-4 py-2 rounded-xl bg-white/40 backdrop-blur-md border border-white/60 hover:bg-white/60 transition-all duration-300 shadow-lg hover:shadow-xl group">
                        <!-- Avatar -->
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                            <span class="text-white font-semibold text-sm">{{ $adminInitial }}</span>
                        </div>
                        <!-- Name -->
                        <div class="hidden md:block text-left">
                            <div class="text-sm font-semibold text-gray-800">{{ $adminName }}</div>
                            <div class="text-xs text-gray-600">Administrator</div>
                        </div>
                        <!-- Dropdown Icon -->
                        <svg class="w-4 h-4 text-gray-600 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-64 rounded-xl shadow-2xl bg-white/90 backdrop-blur-xl border border-white/60 overflow-hidden z-[9999]"
                        style="display: none;">
                        
                        <!-- User Info -->
                        <div class="px-4 py-3 bg-gradient-to-r from-primary-500 to-secondary-500">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">{{ $adminInitial }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-white">{{ $adminName }}</div>
                                    <div class="text-xs text-white/80">{{ $adminEmail }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-2">
                            <a href="{{ route('admin.dashboard') }}" 
                                class="flex items-center space-x-3 px-4 py-3 hover:bg-primary-50 transition-colors group">
                                <svg class="w-5 h-5 text-gray-600 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-primary-600 transition-colors">Dashboard</span>
                            </a>

                            <a href="{{ route('admin.profile.edit') }}" 
                                class="flex items-center space-x-3 px-4 py-3 hover:bg-primary-50 transition-colors group">
                                <svg class="w-5 h-5 text-gray-600 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-primary-600 transition-colors">Profile Settings</span>
                            </a>

                            <a href="{{ route('admin.programs.index') }}" 
                                class="flex items-center space-x-3 px-4 py-3 hover:bg-primary-50 transition-colors group">
                                <svg class="w-5 h-5 text-gray-600 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-primary-600 transition-colors">Program Management</span>
                            </a>

                            <a href="{{ route('admin.activity.index') }}" 
                                class="flex items-center space-x-3 px-4 py-3 hover:bg-primary-50 transition-colors group">
                                <svg class="w-5 h-5 text-gray-600 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-primary-600 transition-colors">Activity Timeline</span>
                            </a>

                            <a href="{{ route('admin.accounts.index') }}" 
                                class="flex items-center space-x-3 px-4 py-3 hover:bg-primary-50 transition-colors group">
                                <svg class="w-5 h-5 text-gray-600 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2m10 2v-2M12 7a4 4 0 100 8 4 4 0 000-8z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-primary-600 transition-colors">Admin Accounts</span>
                            </a>

                            <div class="border-t border-gray-100 my-2"></div>

                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" 
                                    class="w-full flex items-center space-x-3 px-4 py-3 hover:bg-red-50 transition-colors group text-left">
                                    <svg class="w-5 h-5 text-gray-600 group-hover:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 group-hover:text-red-600 transition-colors">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
