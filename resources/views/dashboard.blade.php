<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="mb-6 p-8 bg-white/40 backdrop-blur-lg border border-white/60 rounded-3xl shadow-xl overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent mb-2">
                            Welcome back, {{ Auth::user()->name }}! 👋
                        </h3>
                        <p class="text-gray-600">
                            You're logged in and ready to go!
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-primary-500 to-secondary-600 flex items-center justify-center shadow-xl">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid md:grid-cols-3 gap-6 mb-6">
                <!-- Stat Card 1 -->
                <div class="group p-6 bg-white/40 backdrop-blur-lg border border-white/60 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-green-600">+12.5%</span>
                    </div>
                    <h4 class="text-gray-600 text-sm font-medium mb-1">Total Projects</h4>
                    <p class="text-3xl font-bold text-gray-800">24</p>
                </div>

                <!-- Stat Card 2 -->
                <div class="group p-6 bg-white/40 backdrop-blur-lg border border-white/60 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-secondary-500 to-secondary-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-orange-600">+8.2%</span>
                    </div>
                    <h4 class="text-gray-600 text-sm font-medium mb-1">Active Tasks</h4>
                    <p class="text-3xl font-bold text-gray-800">18</p>
                </div>

                <!-- Stat Card 3 -->
                <div class="group p-6 bg-white/40 backdrop-blur-lg border border-white/60 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-green-600">+15.3%</span>
                    </div>
                    <h4 class="text-gray-600 text-sm font-medium mb-1">Completed</h4>
                    <p class="text-3xl font-bold text-gray-800">142</p>
                </div>
            </div>

            <!-- Quick Actions Grid -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Quick Actions Card -->
                <div class="p-6 bg-white/40 backdrop-blur-lg border border-white/60 rounded-2xl shadow-lg">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        @foreach([
                            ['icon' => 'M12 4v16m8-8H4', 'title' => 'Create New Project', 'desc' => 'Start a new project from scratch'],
                            ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'title' => 'Invite Team Members', 'desc' => 'Collaborate with your team'],
                            ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => 'View Analytics', 'desc' => 'Check your project insights'],
                        ] as $action)
                            <button class="w-full p-4 rounded-xl bg-white/30 border border-white/40 hover:bg-white/50 hover:scale-102 transition-all duration-300 text-left group">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary-500 to-secondary-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $action['icon'] }}"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-800">{{ $action['title'] }}</h4>
                                        <p class="text-sm text-gray-600">{{ $action['desc'] }}</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Activity Card -->
                <div class="p-6 bg-white/40 backdrop-blur-lg border border-white/60 rounded-2xl shadow-lg">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Recent Activity</h3>
                    <div class="space-y-4">
                        @foreach([
                            ['color' => 'primary', 'title' => 'New project created', 'time' => '2 hours ago'],
                            ['color' => 'accent', 'title' => 'Task completed', 'time' => '4 hours ago'],
                            ['color' => 'purple', 'title' => 'Team member joined', 'time' => '1 day ago'],
                            ['color' => 'primary', 'title' => 'Report generated', 'time' => '2 days ago'],
                        ] as $activity)
                            <div class="flex items-center space-x-4 p-3 rounded-lg hover:bg-white/30 transition-colors">
                                <div class="w-2 h-2 rounded-full bg-gradient-to-r from-{{ $activity['color'] }}-500 to-{{ $activity['color'] }}-600"></div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800">{{ $activity['title'] }}</p>
                                    <p class="text-xs text-gray-600">{{ $activity['time'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
