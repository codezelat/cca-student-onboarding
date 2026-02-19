@extends('admin.layouts.app')

@section('title', 'Registration Details | ' . config('app.name', 'CCA'))
@section('meta_description', 'Review complete applicant details, payment status, and actions inside the Codezela Career Accelerator admin portal.')
@section('og_title', 'Registration Details | ' . config('app.name', 'CCA'))
@section('og_description', 'Detailed view of a Codezela Career Accelerator applicant for the internal team.')
@section('twitter_title', 'Registration Details | ' . config('app.name', 'CCA'))
@section('twitter_description', 'Detailed view of a Codezela Career Accelerator applicant for the internal team.')

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
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-primary-600 mb-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Dashboard
                    </a>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                        Registration Details
                    </h1>
                    <p class="text-gray-600 mt-1">Registration ID: {{ $registration->register_id ?? 'cca-A' . str_pad($registration->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.registrations.edit', $registration->id) }}" 
                       class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-xl transition-all duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Payment & Tags Information -->
                    <div class="bg-gradient-to-br from-indigo-50/80 to-purple-50/80 backdrop-blur-xl border border-indigo-200 rounded-2xl shadow-lg p-6">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Payment Information
                            </h2>
                            <a href="{{ route('admin.registrations.payments.index', $registration->id) }}"
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-md">
                                Manage Ledger
                            </a>
                        </div>
                        <p class="text-xs text-indigo-700 mb-4">Ledger entries: {{ $registration->payments->count() }}</p>
                        <div class="space-y-4">
                            <!-- Tags Display -->
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-3">Payment Tags</p>
                                @if($registration->tags && count($registration->tags) > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($registration->tags as $tag)
                                            @php
                                                $tagColors = [
                                                    'General Rate' => 'bg-gradient-to-r from-indigo-500 to-indigo-600 text-white',
                                                    'Special 50% Offer' => 'bg-gradient-to-r from-purple-500 to-purple-600 text-white',
                                                    'Full Payment' => 'bg-gradient-to-r from-green-500 to-green-600 text-white',
                                                    'Registration Fee' => 'bg-gradient-to-r from-blue-500 to-blue-600 text-white',
                                                    'Partial Registration Fee' => 'bg-gradient-to-r from-yellow-500 to-yellow-600 text-white',
                                                ];
                                                $colorClass = $tagColors[$tag] ?? 'bg-gradient-to-r from-gray-500 to-gray-600 text-white';
                                            @endphp
                                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200 {{ $colorClass }}">
                                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $tag }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="flex items-center p-4 bg-gray-50 border border-gray-200 rounded-xl">
                                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        <span class="text-sm text-gray-500 italic">No tags assigned</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Full Amount -->
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-2">Full Amount</p>
                                <div class="flex items-center p-4 bg-white/70 border-2 border-green-200 rounded-xl">
                                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500">Total Course Fee</p>
                                        @if($registration->full_amount)
                                            <p class="text-2xl font-bold text-green-600">LKR {{ number_format($registration->full_amount, 2) }}</p>
                                        @else
                                            <p class="text-lg font-semibold text-gray-400">Not specified</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Current Paid Amount -->
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-2">Current Paid Amount</p>
                                <div class="flex items-center p-4 bg-white/70 border-2 border-indigo-200 rounded-xl">
                                    <svg class="w-6 h-6 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500">Amount Paid</p>
                                        @if($registration->current_paid_amount)
                                            <p class="text-2xl font-bold text-indigo-600">LKR {{ number_format($registration->current_paid_amount, 2) }}</p>
                                        @else
                                            <p class="text-lg font-semibold text-gray-400">Not specified</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Remaining Amount -->
                            @if($registration->full_amount && $registration->current_paid_amount)
                                @php
                                    $remainingAmount = $registration->full_amount - $registration->current_paid_amount;
                                @endphp
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-2">Remaining Amount</p>
                                    <div class="flex items-center p-4 bg-white/70 border-2 border-{{ $remainingAmount > 0 ? 'orange' : 'green' }}-200 rounded-xl">
                                        <svg class="w-6 h-6 text-{{ $remainingAmount > 0 ? 'orange' : 'green' }}-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($remainingAmount > 0)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            @endif
                                        </svg>
                                        <div>
                                            <p class="text-xs text-gray-500">Balance</p>
                                            <p class="text-2xl font-bold text-{{ $remainingAmount > 0 ? 'orange' : 'green' }}-600">
                                                LKR {{ number_format(abs($remainingAmount), 2) }}
                                            </p>
                                            @if($remainingAmount <= 0)
                                                <p class="text-xs text-green-600 font-medium mt-1">✓ Fully Paid</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Program Information -->
                    <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Program Information
                        </h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Program ID</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $registration->program_id }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Program Year</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $registration->program_year }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-sm text-gray-600">Program Name</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $registration->program_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Duration</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $registration->program_duration }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Personal Information
                        </h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <p class="text-sm text-gray-600">Full Name</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $registration->full_name }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-sm text-gray-600">Name with Initials</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $registration->name_with_initials }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Gender</p>
                                <p class="text-lg font-semibold text-gray-900 capitalize">{{ $registration->gender }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Date of Birth</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $registration->date_of_birth->format('F d, Y') }}</p>
                            </div>
                            @if($registration->nic_number)
                            <div>
                                <p class="text-sm text-gray-600">NIC Number</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $registration->nic_number }}</p>
                            </div>
                            @endif
                            @if($registration->passport_number)
                            <div>
                                <p class="text-sm text-gray-600">Passport Number</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $registration->passport_number }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-sm text-gray-600">Nationality</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $registration->nationality }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Country of Birth</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $registration->country_of_birth }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Contact Information
                        </h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <p class="text-sm text-gray-600">Email Address</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $registration->email_address }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">WhatsApp Number</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $registration->whatsapp_number }}</p>
                            </div>
                            @if($registration->home_contact_number)
                            <div>
                                <p class="text-sm text-gray-600">Home Contact</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $registration->home_contact_number }}</p>
                            </div>
                            @endif
                            <div class="col-span-2">
                                <p class="text-sm text-gray-600">Address</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $registration->permanent_address }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Country</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $registration->country }}</p>
                            </div>
                            @if($registration->district)
                            <div>
                                <p class="text-sm text-gray-600">District</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $registration->district }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-sm text-gray-600">Postal Code</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $registration->postal_code }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Guardian Information -->
                    <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            Guardian Information
                        </h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Guardian Name</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $registration->guardian_contact_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Guardian Contact</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $registration->guardian_contact_number }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Qualification Information -->
                    <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                            Qualification
                        </h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Highest Qualification</p>
                                <p class="text-lg font-semibold text-gray-900 capitalize">{{ str_replace('_', ' ', $registration->highest_qualification) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status</p>
                                <p class="text-lg font-semibold text-gray-900 capitalize">{{ $registration->qualification_status }}</p>
                            </div>
                            @if($registration->qualification_completed_date)
                            <div>
                                <p class="text-sm text-gray-600">Completion Date</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $registration->qualification_completed_date->format('F d, Y') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Quick Info -->
                    <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg p-6">
                        <h3 class="font-bold text-gray-800 mb-4">Registration Info</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between py-2 border-b border-gray-200">
                                <span class="text-sm text-gray-600">Registered on</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $registration->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <span class="text-sm text-gray-600">Last updated</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $registration->updated_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Documents
                            </h3>
                            <button type="button" onclick="openAllDocumentsViewer()" 
                                    class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all transform hover:scale-105">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <span>View All</span>
                            </button>
                        </div>
                        <div class="space-y-3">
                            <!-- Passport Photo -->
                            @php
                                $passportPhotoUrl = null;
                                if (is_array($registration->passport_photo)) {
                                    if (isset($registration->passport_photo[0]['url'])) {
                                        $passportPhotoUrl = $registration->passport_photo[0]['url'];
                                    } elseif (isset($registration->passport_photo['url'])) {
                                        $passportPhotoUrl = $registration->passport_photo['url'];
                                    }
                                } elseif (is_string($registration->passport_photo) && !empty($registration->passport_photo)) {
                                    $passportPhotoUrl = $registration->passport_photo;
                                }
                            @endphp
                            
                            @if($passportPhotoUrl)
                            <div class="flex items-center justify-between p-2 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Passport Photo</span>
                                </div>
                                <a href="{{ $passportPhotoUrl }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            </div>
                            @endif

                            @php
                                $paymentSlipUrl = null;
                                if (is_array($registration->payment_slip)) {
                                    if (isset($registration->payment_slip[0]['url'])) {
                                        $paymentSlipUrl = $registration->payment_slip[0]['url'];
                                    } elseif (isset($registration->payment_slip['url'])) {
                                        $paymentSlipUrl = $registration->payment_slip['url'];
                                    }
                                } elseif (is_string($registration->payment_slip) && !empty($registration->payment_slip)) {
                                    $paymentSlipUrl = $registration->payment_slip;
                                }
                            @endphp
                            
                            @if($paymentSlipUrl)
                            <div class="flex items-center justify-between p-2 bg-green-50 rounded-lg border border-green-200">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Payment Slip</span>
                                </div>
                                <a href="{{ $paymentSlipUrl }}" target="_blank" rel="noopener noreferrer" class="text-green-600 hover:text-green-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            </div>
                            @endif

                            @if(is_array($registration->academic_qualification_documents) && count($registration->academic_qualification_documents) > 0)
                            <div>
                                <p class="text-xs text-gray-500 mb-1.5 font-medium">Academic Documents</p>
                                @foreach($registration->academic_qualification_documents as $index => $doc)
                                <div class="flex items-center justify-between p-2 bg-purple-50 rounded-lg border border-purple-200 mb-1.5">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span class="text-xs font-medium text-gray-700">Document {{ $index + 1 }}</span>
                                    </div>
                                    <a href="{{ $doc['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="text-purple-600 hover:text-purple-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            @endif
                            
                            @if(is_array($registration->nic_documents) && count($registration->nic_documents) > 0)
                            <div>
                                <p class="text-xs text-gray-500 mb-1.5 font-medium">NIC Documents</p>
                                @foreach($registration->nic_documents as $index => $doc)
                                <div class="flex items-center justify-between p-2 bg-indigo-50 rounded-lg border border-indigo-200 mb-1.5">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                        </svg>
                                        <span class="text-xs font-medium text-gray-700">NIC {{ $index + 1 }}</span>
                                    </div>
                                    <a href="{{ $doc['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="text-indigo-600 hover:text-indigo-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            @endif
                            
                            @if(is_array($registration->passport_documents) && count($registration->passport_documents) > 0)
                            <div>
                                <p class="text-xs text-gray-500 mb-1.5 font-medium">Passport Documents</p>
                                @foreach($registration->passport_documents as $index => $doc)
                                <div class="flex items-center justify-between p-2 bg-teal-50 rounded-lg border border-teal-200 mb-1.5">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                                        </svg>
                                        <span class="text-xs font-medium text-gray-700">Passport {{ $index + 1 }}</span>
                                    </div>
                                    <a href="{{ $doc['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="text-teal-600 hover:text-teal-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Document Viewer Modal -->
    <div id="docViewerModal" class="hidden fixed inset-0 z-50 overflow-hidden" role="dialog" aria-modal="true" aria-labelledby="docViewerTitle">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm transition-opacity duration-300" onclick="closeDocViewer()" aria-hidden="true"></div>
        
        <!-- Modal Container -->
        <div class="relative h-full flex items-center justify-center p-4 sm:p-6">
            <!-- Modal Content -->
            <div class="relative w-full max-w-7xl h-[95vh] bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden transform transition-all duration-300 scale-95">
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-4 sm:px-6 py-4 bg-gradient-to-r from-primary-600 to-secondary-600 text-white shadow-lg">
                    <div class="flex items-center space-x-3 min-w-0 flex-1">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 id="docViewerTitle" class="text-base sm:text-lg font-semibold truncate">Document Preview</h3>
                    </div>
                    <div class="flex items-center space-x-2 flex-shrink-0 ml-4">
                        <!-- Zoom Controls for Images -->
                        <div id="zoomControls" class="hidden items-center space-x-1 mr-2 bg-white/20 rounded-lg p-1">
                            <button onclick="zoomOut()" class="p-2 hover:bg-white/30 rounded transition-all" title="Zoom Out" aria-label="Zoom out">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"/>
                                </svg>
                            </button>
                            <span id="zoomLevel" class="px-2 text-sm font-medium">100%</span>
                            <button onclick="zoomIn()" class="p-2 hover:bg-white/30 rounded transition-all" title="Zoom In" aria-label="Zoom in">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                </svg>
                            </button>
                            <button onclick="resetZoom()" class="p-2 hover:bg-white/30 rounded transition-all" title="Reset Zoom" aria-label="Reset zoom">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </button>
                        </div>
                        <!-- Document Navigation -->
                        <div id="navControls" class="hidden items-center space-x-1 mr-2 bg-white/20 rounded-lg p-1">
                            <button onclick="prevDocument()" class="p-2 hover:bg-white/30 rounded transition-all disabled:opacity-50 disabled:cursor-not-allowed" title="Previous Document" aria-label="Previous document">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <span id="currentDocNum" class="px-3 text-sm font-medium whitespace-nowrap">1 / 1</span>
                            <button onclick="nextDocument()" class="p-2 hover:bg-white/30 rounded transition-all disabled:opacity-50 disabled:cursor-not-allowed" title="Next Document" aria-label="Next document">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                        <a id="docViewerDownload" href="#" download target="_blank" rel="noopener noreferrer" 
                           class="hidden sm:flex items-center space-x-2 px-3 sm:px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition-all duration-200"
                           aria-label="Download document">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <span class="text-sm font-medium hidden sm:inline">Download</span>
                        </a>
                        <a id="docViewerOpenTab" href="#" target="_blank" rel="noopener noreferrer" 
                           class="flex items-center space-x-2 px-3 sm:px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition-all duration-200"
                           aria-label="Open in new tab">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            <span class="text-sm font-medium hidden sm:inline">Open</span>
                        </a>
                        <button onclick="closeDocViewer()" class="p-2 hover:bg-white/20 rounded-lg transition-all duration-200" aria-label="Close modal">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="flex-1 relative overflow-hidden bg-gray-50">
                    <!-- Loading Spinner -->
                    <div id="docViewerLoader" class="absolute inset-0 flex items-center justify-center bg-gray-50 z-20">
                        <div class="flex flex-col items-center space-y-4">
                            <div class="relative">
                                <div class="w-16 h-16 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-gray-600 font-medium animate-pulse">Loading document...</p>
                        </div>
                    </div>

                    <!-- Image Viewer -->
                    <div id="imageViewerContainer" class="hidden absolute inset-0 overflow-auto bg-gray-900">
                        <div class="min-h-full flex items-center justify-center p-4">
                            <img id="imageViewer" src="" alt="Document preview" 
                                 class="max-w-full h-auto rounded-lg shadow-2xl cursor-move transition-transform duration-200"
                                 draggable="false"
                                 style="transform-origin: center center;">
                        </div>
                    </div>

                    <!-- PDF Viewer -->
                    <div id="pdfViewerContainer" class="hidden absolute inset-0 bg-gray-100">
                        <iframe id="pdfViewer" class="w-full h-full border-0" title="PDF Document" loading="lazy"></iframe>
                    </div>

                    <!-- Error Message -->
                    <div id="docViewerError" class="hidden absolute inset-0 items-center justify-center p-4 sm:p-8 bg-gray-50">
                        <div class="text-center max-w-md">
                            <div class="mb-6 relative">
                                <svg class="w-24 h-24 text-red-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="absolute inset-0 animate-ping opacity-20">
                                    <svg class="w-24 h-24 text-red-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <h4 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">Unable to Preview</h4>
                            <p class="text-gray-600 mb-6 text-sm sm:text-base">This document format cannot be previewed directly in the browser. Please download or open it in a new tab.</p>
                            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                <a id="docViewerErrorDownload" href="#" download
                                   class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold rounded-xl hover:from-green-700 hover:to-green-800 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Download File
                                </a>
                                <a id="docViewerErrorLink" href="#" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-600 to-secondary-600 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-secondary-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    Open in New Tab
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-4 sm:px-6 py-3 bg-gradient-to-r from-gray-50 to-gray-100 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs sm:text-sm text-gray-600">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-primary-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <span id="docViewerFileType" class="font-medium">Document</span>
                    </div>
                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                        <span class="hidden sm:inline">
                            <kbd class="px-2 py-1 bg-white border border-gray-300 rounded text-xs font-mono">←→</kbd> Navigate • 
                            <kbd class="px-2 py-1 bg-white border border-gray-300 rounded text-xs font-mono">ESC</kbd> Close
                        </span>
                        <span class="sm:hidden">Tap outside to close</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Collect all documents for unified viewer
        const allDocuments = [
            @if($passportPhotoUrl)
            { url: '{{ $passportPhotoUrl }}', title: 'Passport Photo', type: 'image', category: 'Personal' },
            @endif
            @if($paymentSlipUrl)
            { url: '{{ $paymentSlipUrl }}', title: 'Payment Slip', type: 'auto', category: 'Payment' },
            @endif
            @if(is_array($registration->academic_qualification_documents) && count($registration->academic_qualification_documents) > 0)
                @foreach($registration->academic_qualification_documents as $index => $doc)
                { url: '{{ $doc['url'] ?? '#' }}', title: 'Academic Document {{ $index + 1 }}', type: 'auto', category: 'Academic' },
                @endforeach
            @endif
            @if(is_array($registration->nic_documents) && count($registration->nic_documents) > 0)
                @foreach($registration->nic_documents as $index => $doc)
                { url: '{{ $doc['url'] ?? '#' }}', title: 'NIC Document {{ $index + 1 }}', type: 'auto', category: 'Identity' },
                @endforeach
            @endif
            @if(is_array($registration->passport_documents) && count($registration->passport_documents) > 0)
                @foreach($registration->passport_documents as $index => $doc)
                { url: '{{ $doc['url'] ?? '#' }}', title: 'Passport Document {{ $index + 1 }}', type: 'auto', category: 'Identity' },
                @endforeach
            @endif
        ];

        let currentDocIndex = 0;

        function openAllDocumentsViewer() {
            if (allDocuments.length === 0) {
                alert('No documents available to preview');
                return;
            }
            currentDocIndex = 0;
            loadDocumentAtIndex(0);
        }

        function loadDocumentAtIndex(index) {
            if (index < 0 || index >= allDocuments.length) return;
            currentDocIndex = index;
            const doc = allDocuments[index];
            
            const modal = document.getElementById('docViewerModal');
            const loader = document.getElementById('docViewerLoader');
            const imageContainer = document.getElementById('imageViewerContainer');
            const imageViewer = document.getElementById('imageViewer');
            const pdfContainer = document.getElementById('pdfViewerContainer');
            const pdfViewer = document.getElementById('pdfViewer');
            const errorContainer = document.getElementById('docViewerError');
            const titleElement = document.getElementById('docViewerTitle');
            const downloadLink = document.getElementById('docViewerDownload');
            const openTabLink = document.getElementById('docViewerOpenTab');
            const errorLink = document.getElementById('docViewerErrorLink');
            const errorDownload = document.getElementById('docViewerErrorDownload');
            const fileTypeElement = document.getElementById('docViewerFileType');
            const zoomControls = document.getElementById('zoomControls');
            const navControls = document.getElementById('navControls');
            const currentDocNum = document.getElementById('currentDocNum');

            // Reset states
            loader.classList.remove('hidden');
            imageContainer.classList.add('hidden');
            pdfContainer.classList.add('hidden');
            errorContainer.classList.add('hidden');
            zoomControls.classList.add('hidden');
            resetZoom();

            // Set content
            titleElement.innerHTML = `
                <span class="inline-block px-2 py-1 bg-white/20 rounded text-xs mr-2">${doc.category}</span>
                ${doc.title}
            `;
            downloadLink.href = doc.url;
            openTabLink.href = doc.url;
            errorLink.href = doc.url;
            errorDownload.href = doc.url;
            fileTypeElement.textContent = getFileTypeDisplay(doc.url);

            // Update navigation
            currentDocNum.textContent = `${index + 1} / ${allDocuments.length}`;
            navControls.classList.remove('hidden');

            // Show modal
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                const modalContent = modal.querySelector('.max-w-7xl');
                if (modalContent) {
                    setTimeout(() => modalContent.classList.add('scale-100'), 10);
                }
            }

            // Load document
            const detectedType = doc.type === 'auto' ? getFileType(doc.url) : doc.type;

            if (detectedType === 'image') {
                zoomControls.classList.remove('hidden');
                zoomControls.classList.add('flex');
                
                imageViewer.onload = function() {
                    loader.classList.add('hidden');
                    imageContainer.classList.remove('hidden');
                    setupImageDrag();
                };
                imageViewer.onerror = function() {
                    loader.classList.add('hidden');
                    errorContainer.classList.remove('hidden');
                };
                imageViewer.src = doc.url;
            } else if (detectedType === 'pdf') {
                pdfViewer.src = doc.url + '#toolbar=1&navpanes=1&view=FitH';
                setTimeout(() => {
                    loader.classList.add('hidden');
                    pdfContainer.classList.remove('hidden');
                }, 1500);
                setTimeout(() => {
                    if (!loader.classList.contains('hidden')) {
                        loader.classList.add('hidden');
                        errorContainer.classList.remove('hidden');
                        errorContainer.classList.add('flex');
                    }
                }, 5000);
            } else {
                loader.classList.add('hidden');
                errorContainer.classList.remove('hidden');
                errorContainer.classList.add('flex');
            }
        }

        function nextDocument() {
            if (currentDocIndex < allDocuments.length - 1) {
                loadDocumentAtIndex(currentDocIndex + 1);
            }
        }

        function prevDocument() {
            if (currentDocIndex > 0) {
                loadDocumentAtIndex(currentDocIndex - 1);
            }
        }

        // Document Viewer State Management
        const docViewerState = {
            currentZoom: 1,
            minZoom: 0.25,
            maxZoom: 4,
            zoomStep: 0.25,
            isDragging: false,
            startX: 0,
            startY: 0,
            scrollLeft: 0,
            scrollTop: 0
        };

        function getFileExtension(url) {
            return url.split('.').pop().split('?')[0].toLowerCase();
        }

        function getFileType(url) {
            const ext = getFileExtension(url);
            const imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'heic', 'svg', 'bmp', 'ico'];
            const pdfExts = ['pdf'];
            const docExts = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
            
            if (imageExts.includes(ext)) return 'image';
            if (pdfExts.includes(ext)) return 'pdf';
            if (docExts.includes(ext)) return 'office';
            return 'unknown';
        }

        function getFileTypeDisplay(url) {
            const ext = getFileExtension(url).toUpperCase();
            const type = getFileType(url);
            const typeNames = {
                'image': 'Image',
                'pdf': 'PDF Document',
                'office': 'Office Document',
                'unknown': 'File'
            };
            return `${ext} ${typeNames[type]}`;
        }

        function openDocViewer(url, title, type = 'auto') {
            const modal = document.getElementById('docViewerModal');
            const loader = document.getElementById('docViewerLoader');
            const imageContainer = document.getElementById('imageViewerContainer');
            const imageViewer = document.getElementById('imageViewer');
            const pdfContainer = document.getElementById('pdfViewerContainer');
            const pdfViewer = document.getElementById('pdfViewer');
            const errorContainer = document.getElementById('docViewerError');
            const titleElement = document.getElementById('docViewerTitle');
            const downloadLink = document.getElementById('docViewerDownload');
            const openTabLink = document.getElementById('docViewerOpenTab');
            const errorLink = document.getElementById('docViewerErrorLink');
            const errorDownload = document.getElementById('docViewerErrorDownload');
            const fileTypeElement = document.getElementById('docViewerFileType');
            const zoomControls = document.getElementById('zoomControls');

            // Reset states
            loader.classList.remove('hidden');
            imageContainer.classList.add('hidden');
            pdfContainer.classList.add('hidden');
            errorContainer.classList.add('hidden');
            zoomControls.classList.add('hidden');
            resetZoom();

            // Set content
            titleElement.textContent = title;
            downloadLink.href = url;
            openTabLink.href = url;
            errorLink.href = url;
            errorDownload.href = url;
            fileTypeElement.textContent = getFileTypeDisplay(url);

            // Show modal with animation
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => modal.querySelector('.bg-white').classList.add('scale-100'), 10);

            // Detect and load file type
            const detectedType = type === 'auto' ? getFileType(url) : type;

            if (detectedType === 'image') {
                zoomControls.classList.remove('hidden');
                zoomControls.classList.add('flex');
                
                imageViewer.onload = function() {
                    loader.classList.add('hidden');
                    imageContainer.classList.remove('hidden');
                    setupImageDrag();
                };
                imageViewer.onerror = function() {
                    loader.classList.add('hidden');
                    errorContainer.classList.remove('hidden');
                    errorContainer.classList.add('flex');
                };
                imageViewer.src = url;
            } else if (detectedType === 'pdf') {
                // For PDFs, use a simpler approach with timeout
                // PDFs in iframes don't reliably fire onload events
                pdfViewer.src = url + '#toolbar=1&navpanes=1&view=FitH';
                
                // Show PDF after a short delay (PDFs don't fire reliable load events)
                setTimeout(() => {
                    loader.classList.add('hidden');
                    pdfContainer.classList.remove('hidden');
                }, 1500);
                
                // Fallback: if PDF fails to load, show error after timeout
                setTimeout(() => {
                    // Check if still loading
                    if (!loader.classList.contains('hidden')) {
                        loader.classList.add('hidden');
                        errorContainer.classList.remove('hidden');
                        errorContainer.classList.add('flex');
                    }
                }, 5000);
            } else {
                loader.classList.add('hidden');
                errorContainer.classList.remove('hidden');
                errorContainer.classList.add('flex');
            }
        }

        function closeDocViewer() {
            const modal = document.getElementById('docViewerModal');
            const imageViewer = document.getElementById('imageViewer');
            const pdfViewer = document.getElementById('pdfViewer');
            
            const modalContent = modal.querySelector('.max-w-7xl');
            if (modalContent) {
                modalContent.classList.remove('scale-100');
            }
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                if (imageViewer) imageViewer.src = '';
                if (pdfViewer) pdfViewer.src = '';
                resetZoom();
            }, 200);
        }

        // Zoom Functions
        function updateZoom() {
            const imageViewer = document.getElementById('imageViewer');
            const zoomLevel = document.getElementById('zoomLevel');
            imageViewer.style.transform = `scale(${docViewerState.currentZoom})`;
            zoomLevel.textContent = Math.round(docViewerState.currentZoom * 100) + '%';
        }

        function zoomIn() {
            if (docViewerState.currentZoom < docViewerState.maxZoom) {
                docViewerState.currentZoom += docViewerState.zoomStep;
                updateZoom();
            }
        }

        function zoomOut() {
            if (docViewerState.currentZoom > docViewerState.minZoom) {
                docViewerState.currentZoom -= docViewerState.zoomStep;
                updateZoom();
            }
        }

        function resetZoom() {
            docViewerState.currentZoom = 1;
            updateZoom();
        }

        // Image Drag Functionality
        function setupImageDrag() {
            const container = document.getElementById('imageViewerContainer');
            const image = document.getElementById('imageViewer');

            container.addEventListener('mousedown', startDrag);
            container.addEventListener('mousemove', drag);
            container.addEventListener('mouseup', endDrag);
            container.addEventListener('mouseleave', endDrag);

            // Touch support
            container.addEventListener('touchstart', handleTouchStart);
            container.addEventListener('touchmove', handleTouchMove);
            container.addEventListener('touchend', endDrag);
        }

        function startDrag(e) {
            if (docViewerState.currentZoom > 1) {
                docViewerState.isDragging = true;
                const container = document.getElementById('imageViewerContainer');
                docViewerState.startX = e.pageX - container.offsetLeft;
                docViewerState.startY = e.pageY - container.offsetTop;
                docViewerState.scrollLeft = container.scrollLeft;
                docViewerState.scrollTop = container.scrollTop;
                document.getElementById('imageViewer').style.cursor = 'grabbing';
            }
        }

        function drag(e) {
            if (!docViewerState.isDragging) return;
            e.preventDefault();
            const container = document.getElementById('imageViewerContainer');
            const x = e.pageX - container.offsetLeft;
            const y = e.pageY - container.offsetTop;
            const walkX = (x - docViewerState.startX) * 2;
            const walkY = (y - docViewerState.startY) * 2;
            container.scrollLeft = docViewerState.scrollLeft - walkX;
            container.scrollTop = docViewerState.scrollTop - walkY;
        }

        function endDrag() {
            docViewerState.isDragging = false;
            const image = document.getElementById('imageViewer');
            if (image) {
                image.style.cursor = docViewerState.currentZoom > 1 ? 'grab' : 'move';
            }
        }

        function handleTouchStart(e) {
            const touch = e.touches[0];
            const mouseEvent = new MouseEvent('mousedown', {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            e.target.dispatchEvent(mouseEvent);
        }

        function handleTouchMove(e) {
            if (docViewerState.isDragging) {
                e.preventDefault();
                const touch = e.touches[0];
                const mouseEvent = new MouseEvent('mousemove', {
                    clientX: touch.clientX,
                    clientY: touch.clientY
                });
                e.target.dispatchEvent(mouseEvent);
            }
        }

        // Keyboard Navigation
        document.addEventListener('keydown', function(e) {
            const modal = document.getElementById('docViewerModal');
            if (!modal.classList.contains('hidden')) {
                switch(e.key) {
                    case 'Escape':
                        closeDocViewer();
                        break;
                    case 'ArrowLeft':
                        e.preventDefault();
                        prevDocument();
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        nextDocument();
                        break;
                    case '+':
                    case '=':
                        e.preventDefault();
                        zoomIn();
                        break;
                    case '-':
                    case '_':
                        e.preventDefault();
                        zoomOut();
                        break;
                    case '0':
                        if (e.ctrlKey || e.metaKey) {
                            e.preventDefault();
                            resetZoom();
                        }
                        break;
                }
            }
        });

        // Prevent modal content clicks from closing
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('docViewerModal');
            if (modal) {
                const modalContent = modal.querySelector('.bg-white');
                if (modalContent) {
                    modalContent.addEventListener('click', function(e) {
                        e.stopPropagation();
                    });
                }
            }
        });

        // Mouse wheel zoom (with Ctrl/Cmd)
        document.addEventListener('wheel', function(e) {
            const imageContainer = document.getElementById('imageViewerContainer');
            if (!imageContainer.classList.contains('hidden') && (e.ctrlKey || e.metaKey)) {
                e.preventDefault();
                if (e.deltaY < 0) {
                    zoomIn();
                } else {
                    zoomOut();
                }
            }
        }, { passive: false });
    </script>
@endsection
