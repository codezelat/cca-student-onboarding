@extends('admin.layouts.app')

@section('title', 'Admin Dashboard | ' . config('app.name', 'CCA'))
@section('meta_description', 'Review and manage Codezela Career Accelerator registrations, statuses, and onboarding workflows.')
@section('og_title', 'Admin Dashboard | ' . config('app.name', 'CCA'))
@section('og_description', 'Internal dashboard for the Codezela Career Accelerator admissions team.')
@section('twitter_title', 'Admin Dashboard | ' . config('app.name', 'CCA'))
@section('twitter_description', 'Internal dashboard for the Codezela Career Accelerator admissions team.')

@section('body')
    <!-- Animated Background -->
    <div class="fixed inset-0 bg-gradient-to-br from-violet-50 via-purple-50 to-indigo-50 overflow-hidden -z-10">
        <div class="absolute top-0 -left-4 w-96 h-96 bg-violet-200 rounded-full mix-blend-multiply filter blur-xl opacity-60 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-50 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-xl opacity-60 animate-blob animation-delay-4000"></div>
    </div>

    <div class="min-h-screen">
        <!-- Navigation -->
        @include('admin.partials.navigation')

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent mb-2">
                        Student Registrations
                    </h1>
                    <p class="text-gray-600">Manage and review all CCA program registrations</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a
                        href="{{ route('admin.programs.index') }}"
                        class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-300 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center space-x-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13"/>
                        </svg>
                        <span>Programs</span>
                    </a>
                    <a
                        href="{{ route('admin.activity.index') }}"
                        class="px-4 py-2.5 bg-slate-700 hover:bg-slate-800 text-white font-semibold rounded-xl focus:outline-none focus:ring-4 focus:ring-slate-300 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center space-x-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Activity</span>
                    </a>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-green-600 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Scope Switch -->
            <div class="mb-6 flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.dashboard', array_merge(request()->except('scope'), ['scope' => 'active'])) }}"
                   class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request('scope', 'active') === 'active' ? 'bg-primary-600 text-white shadow-lg' : 'bg-white/60 text-gray-700 border border-white/60 hover:bg-white/80' }}">
                    Active ({{ $activeRegistrationsCount }})
                </a>
                <a href="{{ route('admin.dashboard', array_merge(request()->except('scope'), ['scope' => 'trashed'])) }}"
                   class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request('scope') === 'trashed' ? 'bg-red-600 text-white shadow-lg' : 'bg-white/60 text-gray-700 border border-white/60 hover:bg-white/80' }}">
                    Trash ({{ $trashedRegistrationsCount }})
                </a>
                <a href="{{ route('admin.dashboard', array_merge(request()->except('scope'), ['scope' => 'all'])) }}"
                   class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request('scope') === 'all' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-white/60 text-gray-700 border border-white/60 hover:bg-white/80' }}">
                    All ({{ $totalRegistrations }})
                </a>
            </div>

            <!-- Filters & Actions -->
            <div class="mb-6 p-6 bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="space-y-4">
                    <input type="hidden" name="scope" value="{{ request('scope', 'active') }}">

                    <!-- Search and Program Filter Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Search Box -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                Search by Full Name, Email, NIC, or WhatsApp
                            </label>
                            <input 
                                type="text" 
                                name="search" 
                                id="search"
                                value="{{ request('search') }}"
                                placeholder="Enter Register ID, Full Name, Email, NIC, or WhatsApp..."
                                class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                            >
                        </div>

                        <!-- Program Filter -->
                        <div>
                            <label for="program_filter" class="block text-sm font-medium text-gray-700 mb-2">
                                Filter by Program
                            </label>
                            <select 
                                name="program_filter" 
                                id="program_filter"
                                class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                            >
                                <option value="">All Programs</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->program_id }}" {{ request('program_filter') == $program->program_id ? 'selected' : '' }}>
                                        {{ $program->program_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons Row -->
                    <div class="flex flex-wrap items-center gap-3">
                        <button 
                            type="submit"
                            class="px-5 py-2.5 bg-gradient-to-r from-primary-600 to-secondary-600 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-secondary-700 focus:outline-none focus:ring-4 focus:ring-primary-300 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02] flex items-center justify-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Apply Filters
                        </button>
                        
                        <a 
                            href="{{ route('admin.export', request()->query()) }}"
                            class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl focus:outline-none focus:ring-4 focus:ring-green-300 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02] flex items-center space-x-2"
                            title="Export to Excel"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Excel</span>
                        </a>

                        @if(request('search') || request('program_filter') || request('tag_filter') || request('scope', 'active') !== 'active')
                            <a 
                                href="{{ route('admin.dashboard', ['scope' => 'active']) }}"
                                class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl focus:outline-none focus:ring-4 focus:ring-red-300 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02] flex items-center space-x-2"
                                title="Clear Filters"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Clear</span>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total Registrations -->
                <div class="p-6 bg-white/60 backdrop-blur-xl border border-white/60 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Active Registrations</p>
                            <p class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">{{ $activeRegistrationsCount }}</p>
                            <p class="text-xs text-gray-500 mt-1">Trash: {{ $trashedRegistrationsCount }} | Total: {{ $totalRegistrations }}</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- General Rate Registrations -->
                <a href="{{ route('admin.dashboard', array_merge(request()->except('tag_filter'), request('tag_filter') === 'General Rate' ? [] : ['tag_filter' => 'General Rate'])) }}" 
                   class="block p-6 backdrop-blur-xl border rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 {{ request('tag_filter') === 'General Rate' ? 'bg-green-100/80 border-green-400 ring-2 ring-green-500' : 'bg-white/60 border-white/60' }} cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">General Rate {{ request('tag_filter') === 'General Rate' ? '(Active Filter)' : '' }}</p>
                            <p class="text-3xl font-bold bg-gradient-to-r from-green-600 to-green-800 bg-clip-text text-transparent">{{ $generalRateCount }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ request('tag_filter') === 'General Rate' ? '✓ Click to clear filter' : 'Click to filter' }}</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-green-100 to-green-200 rounded-xl">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Special Offer Registrations -->
                <a href="{{ route('admin.dashboard', array_merge(request()->except('tag_filter'), request('tag_filter') === 'Special 50% Offer' ? [] : ['tag_filter' => 'Special 50% Offer'])) }}" 
                   class="block p-6 backdrop-blur-xl border rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 {{ request('tag_filter') === 'Special 50% Offer' ? 'bg-purple-100/80 border-purple-400 ring-2 ring-purple-500' : 'bg-white/60 border-white/60' }} cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Special Offer {{ request('tag_filter') === 'Special 50% Offer' ? '(Active Filter)' : '' }}</p>
                            <p class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-purple-800 bg-clip-text text-transparent">{{ $specialOfferCount }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ request('tag_filter') === 'Special 50% Offer' ? '✓ Click to clear filter' : 'Click to filter' }}</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Most Registered Program -->
                <div class="p-6 bg-white/60 backdrop-blur-xl border border-white/60 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-600 mb-1">Top Program</p>
                            @if($mostRegisteredProgram)
                                <p class="text-lg font-bold text-orange-600 truncate" title="{{ $mostRegisteredProgram->program_id }}">{{ $mostRegisteredProgram->program_id }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $mostRegisteredProgram->total }} registrations</p>
                            @else
                                <p class="text-lg font-semibold text-gray-400">N/A</p>
                            @endif
                        </div>
                        <div class="p-3 bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl flex-shrink-0">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white/40">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Register ID
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Program
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Full Name
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    NIC/Passport
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    WhatsApp
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Payment Slip
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/20 divide-y divide-gray-200">
                            @forelse($registrations as $registration)
                                <tr class="hover:bg-white/40 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $registration->register_id ?? 'cca-A' . str_pad($registration->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <div class="font-medium">{{ $registration->program_id }}</div>
                                        <div class="text-xs text-gray-500">{{ Str::limit($registration->program_name, 30) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $registration->full_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $registration->nic_number ?? $registration->passport_number ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ Str::limit($registration->email_address, 25) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $registration->whatsapp_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @php
                                            $paymentSlipUrl = null;
                                            if (is_array($registration->payment_slip)) {
                                                // Check if it's an array of files (indexed array)
                                                if (isset($registration->payment_slip[0]['url'])) {
                                                    $paymentSlipUrl = $registration->payment_slip[0]['url'];
                                                }
                                                // Check if it's a single file object (associative array)
                                                elseif (isset($registration->payment_slip['url'])) {
                                                    $paymentSlipUrl = $registration->payment_slip['url'];
                                                }
                                            } elseif (is_string($registration->payment_slip) && !empty($registration->payment_slip)) {
                                                $paymentSlipUrl = $registration->payment_slip;
                                            }
                                        @endphp
                                        
                                        @if($paymentSlipUrl)
                                            <a href="{{ $paymentSlipUrl }}" 
                                               target="_blank" 
                                               rel="noopener noreferrer"
                                               class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors font-medium">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                                View Slip
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-sm">No slip</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2">
                                            @if($registration->trashed())
                                                <form method="POST" action="{{ route('admin.registrations.restore', array_merge(['id' => $registration->id], request()->query())) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="px-3 py-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors"
                                                            title="Restore">
                                                        Restore
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.registrations.force-delete', array_merge(['id' => $registration->id], request()->query())) }}" 
                                                      onsubmit="return confirm('Permanently delete this registration and files? This cannot be undone.');"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors"
                                                            title="Permanent Delete">
                                                        Purge
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('admin.registrations.show', $registration->id) }}" 
                                                   class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors"
                                                   title="View Details">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.registrations.edit', $registration->id) }}" 
                                                   class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors"
                                                   title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>
                                                <form method="POST" action="{{ route('admin.registrations.destroy', array_merge(['id' => $registration->id], request()->query())) }}" 
                                                      onsubmit="return confirm('Move this registration to trash? You can restore it later.');"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors"
                                                            title="Move to Trash">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <p class="text-gray-500 text-lg font-medium">No registrations found</p>
                                            <p class="text-gray-400 text-sm mt-1">Try adjusting your search or filters</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($registrations->hasPages())
                    <div class="bg-white/40 px-6 py-4 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Showing {{ $registrations->firstItem() }} to {{ $registrations->lastItem() }} of {{ $registrations->total() }} results
                            </div>
                            <div>
                                {{ $registrations->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>

@endsection
