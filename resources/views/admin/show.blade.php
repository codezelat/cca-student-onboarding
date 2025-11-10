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
        <nav class="bg-white/60 backdrop-blur-xl border-b border-white/60 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <span class="ml-3 text-xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                                CCA Admin
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-700">{{ Auth::guard('admin')->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-white/50 hover:bg-white/70 text-gray-700 rounded-xl transition-all duration-200 border border-gray-200">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

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
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Documents
                        </h3>
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
                            <div>
                                <p class="text-xs text-gray-600 mb-2">Passport Photo</p>
                                <a href="{{ $passportPhotoUrl }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="flex items-center p-3 bg-white/40 hover:bg-white/60 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    <span class="text-sm text-gray-700 truncate">View Photo</span>
                                </a>
                            </div>
                            @endif

                            <!-- Payment Slip -->
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
                            <div>
                                <p class="text-xs text-gray-600 mb-2">Payment Slip</p>
                                <a href="{{ $paymentSlipUrl }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="flex items-center p-3 bg-white/40 hover:bg-white/60 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    <span class="text-sm text-gray-700 truncate">View Slip (Opens in New Window)</span>
                                </a>
                            </div>
                            @endif

                            <!-- Academic Documents -->
                            @if(is_array($registration->academic_qualification_documents) && count($registration->academic_qualification_documents) > 0)
                            <div>
                                <p class="text-xs text-gray-600 mb-2">Academic Documents</p>
                                @foreach($registration->academic_qualification_documents as $index => $doc)
                                <a href="{{ $doc['url'] ?? '#' }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="flex items-center p-3 bg-white/40 hover:bg-white/60 rounded-lg transition-colors mb-2">
                                    <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    <span class="text-sm text-gray-700 truncate">Document {{ $index + 1 }}</span>
                                </a>
                                @endforeach
                            </div>
                            @endif
                            
                            <!-- NIC Documents -->
                            @if(is_array($registration->nic_documents) && count($registration->nic_documents) > 0)
                            <div>
                                <p class="text-xs text-gray-600 mb-2">NIC Documents</p>
                                @foreach($registration->nic_documents as $index => $doc)
                                <a href="{{ $doc['url'] ?? '#' }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="flex items-center p-3 bg-white/40 hover:bg-white/60 rounded-lg transition-colors mb-2">
                                    <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    <span class="text-sm text-gray-700 truncate">NIC Document {{ $index + 1 }}</span>
                                </a>
                                @endforeach
                            </div>
                            @endif
                            
                            <!-- Passport Documents -->
                            @if(is_array($registration->passport_documents) && count($registration->passport_documents) > 0)
                            <div>
                                <p class="text-xs text-gray-600 mb-2">Passport Documents</p>
                                @foreach($registration->passport_documents as $index => $doc)
                                <a href="{{ $doc['url'] ?? '#' }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="flex items-center p-3 bg-white/40 hover:bg-white/60 rounded-lg transition-colors mb-2">
                                    <svg class="w-5 h-5 text-teal-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    <span class="text-sm text-gray-700 truncate">Passport Document {{ $index + 1 }}</span>
                                </a>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
