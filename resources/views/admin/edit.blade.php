@extends('admin.layouts.app')

@section('title', 'Edit Registration | ' . config('app.name', 'CCA'))
@section('meta_description', 'Edit student registration details within the Codezela Career Accelerator admin portal.')
@section('og_title', 'Edit Registration | ' . config('app.name', 'CCA'))
@section('og_description', 'Update applicant information securely inside the Codezela Career Accelerator admin dashboard.')
@section('twitter_title', 'Edit Registration | ' . config('app.name', 'CCA'))
@section('twitter_description', 'Update applicant information securely inside the Codezela Career Accelerator admin dashboard.')

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
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('admin.registrations.show', $registration->id) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-primary-600 mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Details
                </a>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                    Edit Registration
                </h1>
                <p class="text-gray-600 mt-1">Registration ID: #{{ $registration->id }}</p>
            </div>

            <!-- Edit Form -->
            <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg p-8">
                <form method="POST" action="{{ route('admin.registrations.update', $registration->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Program Selection -->
                    <div class="mb-8 pb-8 border-b border-gray-200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Program Selection</h2>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">Update the program this student is enrolled in.</p>
                        
                        <div class="bg-gradient-to-br from-primary-50/50 to-secondary-50/50 border border-primary-100 rounded-xl p-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Program *</label>
                            <select name="program_id" required 
                                    class="w-full px-4 py-3 bg-white border-2 border-primary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                                <option value="">Choose a program...</option>
                                @foreach($programs as $programId => $programDetails)
                                    <option value="{{ $programId }}" 
                                            {{ old('program_id', $registration->program_id) == $programId ? 'selected' : '' }}>
                                        {{ $programDetails['name'] }} ({{ $programDetails['year'] }} - {{ $programDetails['duration'] }})
                                    </option>
                                @endforeach
                            </select>
                            @error('program_id')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            
                            <!-- Current Program Display -->
                            <div class="mt-4 p-3 bg-white/60 rounded-lg border border-primary-100">
                                <p class="text-xs font-medium text-gray-500 mb-1">Current Program</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $registration->program_name }}</p>
                                <p class="text-xs text-gray-600 mt-1">Program ID: <span class="font-mono">{{ $registration->program_id }}</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Personal Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" name="full_name" value="{{ old('full_name', $registration->full_name) }}" required
                                       class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                @error('full_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Name with Initials *</label>
                                <input type="text" name="name_with_initials" value="{{ old('name_with_initials', $registration->name_with_initials) }}" required
                                       class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                @error('name_with_initials')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                                <select name="gender" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                    <option value="male" {{ $registration->gender == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ $registration->gender == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $registration->date_of_birth->format('Y-m-d')) }}" required
                                       class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                @error('date_of_birth')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NIC Number</label>
                                <input type="text" name="nic_number" value="{{ old('nic_number', $registration->nic_number) }}"
                                       class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                @error('nic_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Passport Number</label>
                                <input type="text" name="passport_number" value="{{ old('passport_number', $registration->passport_number) }}"
                                       class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                @error('passport_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nationality *</label>
                                <input type="text" name="nationality" value="{{ old('nationality', $registration->nationality) }}" required
                                       class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                @error('nationality')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Contact Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" name="email_address" value="{{ old('email_address', $registration->email_address) }}" required
                                       class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                @error('email_address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp Number *</label>
                                <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $registration->whatsapp_number) }}" required
                                       class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                @error('whatsapp_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Home Contact Number</label>
                                <input type="text" name="home_contact_number" value="{{ old('home_contact_number', $registration->home_contact_number) }}"
                                       class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                @error('home_contact_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Permanent Address *</label>
                                <textarea name="permanent_address" rows="3" required
                                          class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('permanent_address', $registration->permanent_address) }}</textarea>
                                @error('permanent_address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                                <input type="text" name="country" value="{{ old('country', $registration->country) }}" required
                                       class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                @error('country')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">District</label>
                                <input type="text" name="district" value="{{ old('district', $registration->district) }}"
                                       class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                @error('district')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Province</label>
                                <input type="text" name="province" value="{{ old('province', $registration->province) }}"
                                       class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                @error('province')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code *</label>
                                <input type="text" name="postal_code" value="{{ old('postal_code', $registration->postal_code) }}" required
                                       class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                @error('postal_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Guardian Information -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Guardian Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Guardian Name *</label>
                                <input type="text" name="guardian_contact_name" value="{{ old('guardian_contact_name', $registration->guardian_contact_name) }}" required
                                       class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                @error('guardian_contact_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Guardian Contact Number *</label>
                                <input type="text" name="guardian_contact_number" value="{{ old('guardian_contact_number', $registration->guardian_contact_number) }}" required
                                       class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                @error('guardian_contact_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Payment & Tags Management -->
                    <div class="mb-8 pb-8 border-b border-gray-200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Payment Information</h2>
                        </div>
                        <p class="text-sm text-gray-600 mb-6">Manage payment tags and track the amount paid by the student.</p>
                        
                        <div class="bg-gradient-to-br from-indigo-50/50 to-purple-50/50 border border-indigo-100 rounded-xl p-6 space-y-6">
                            <!-- Payment Tags Multi-Select -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Payment Tags
                                    <span class="text-xs text-gray-500 font-normal ml-2">(Select one or multiple tags)</span>
                                </label>
                                <div class="relative">
                                    <div id="tags-display" class="min-h-[120px] p-4 bg-white border-2 border-indigo-200 rounded-xl focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500 transition-all">
                                        <div id="selected-tags" class="flex flex-wrap gap-2 mb-3"></div>
                                        <button type="button" id="add-tag-btn" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                            Add Tag
                                        </button>
                                    </div>
                                    
                                    <!-- Dropdown Menu -->
                                    <div id="tags-dropdown" class="hidden absolute z-10 mt-2 w-full bg-white border-2 border-indigo-200 rounded-xl shadow-xl max-h-64 overflow-y-auto">
                                        @php
                                            $availableTags = [
                                                'Full Payment',
                                                'Special 50% Offer',
                                                'Registration Fee',
                                                'Partial Registration Fee',
                                                'General Rate',
                                                '125000',
                                                '105000',
                                                '62500'
                                            ];
                                        @endphp
                                        @foreach($availableTags as $tag)
                                            <div class="tag-option px-4 py-3 hover:bg-indigo-50 cursor-pointer transition-colors border-b border-gray-100 last:border-b-0" data-tag="{{ $tag }}">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                    </svg>
                                                    <span class="text-sm font-medium text-gray-700">{{ $tag }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Select applicable payment status and amount tags for this registration.</p>
                                @error('tags')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Current Paid Amount -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Current Paid Amount (LKR)
                                    <span class="text-xs text-gray-500 font-normal ml-2">(Enter the amount already paid)</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <input 
                                        type="number" 
                                        name="current_paid_amount" 
                                        id="current_paid_amount"
                                        value="{{ old('current_paid_amount', $registration->current_paid_amount) }}" 
                                        step="0.01"
                                        min="0"
                                        placeholder="0.00"
                                        class="w-full pl-12 pr-4 py-3 bg-white border-2 border-indigo-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 text-lg font-semibold"
                                    >
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Enter the total amount the student has paid so far.</p>
                                @error('current_paid_amount')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Current Status Display (if viewing payment slip) -->
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
                            <div class="pt-4 border-t border-indigo-200">
                                <p class="text-xs font-medium text-gray-600 mb-2">Payment Slip</p>
                                <a href="{{ $paymentSlipUrl }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center px-4 py-2 bg-white border-2 border-indigo-300 hover:border-indigo-400 rounded-lg transition-all">
                                    <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">View Payment Slip</span>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.registrations.show', $registration->id) }}" 
                           class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-xl transition-all duration-200">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-gradient-to-r from-primary-600 to-secondary-600 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-secondary-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- Tags Management Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addTagBtn = document.getElementById('add-tag-btn');
            const tagsDropdown = document.getElementById('tags-dropdown');
            const selectedTagsContainer = document.getElementById('selected-tags');
            const tagOptions = document.querySelectorAll('.tag-option');
            
            // Initialize with existing tags
            const existingTags = @json(old('tags', $registration->tags ?? []));
            const selectedTags = new Set(existingTags);
            
            // Tag color mapping
            const tagColors = {
                'Full Payment': 'from-green-500 to-green-600',
                'Special 50% Offer': 'from-purple-500 to-purple-600',
                'Registration Fee': 'from-blue-500 to-blue-600',
                'Partial Registration Fee': 'from-yellow-500 to-yellow-600',
                'General Rate': 'from-indigo-500 to-indigo-600',
                '125000': 'from-red-500 to-red-600',
                '105000': 'from-orange-500 to-orange-600',
                '62500': 'from-pink-500 to-pink-600'
            };
            
            function renderSelectedTags() {
                selectedTagsContainer.innerHTML = '';
                selectedTags.forEach(tag => {
                    const colorClass = tagColors[tag] || 'from-gray-500 to-gray-600';
                    const tagElement = document.createElement('div');
                    tagElement.className = `inline-flex items-center px-3 py-1.5 bg-gradient-to-r ${colorClass} text-white text-sm font-medium rounded-full shadow-md hover:shadow-lg transition-all duration-200`;
                    tagElement.innerHTML = `
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        ${tag}
                        <button type="button" class="ml-2 hover:bg-white/20 rounded-full p-0.5 transition-colors" data-remove-tag="${tag}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <input type="hidden" name="tags[]" value="${tag}">
                    `;
                    selectedTagsContainer.appendChild(tagElement);
                    
                    // Add remove event listener
                    tagElement.querySelector('[data-remove-tag]').addEventListener('click', function() {
                        selectedTags.delete(tag);
                        renderSelectedTags();
                        updateDropdownOptions();
                    });
                });
                updateDropdownOptions();
            }
            
            function updateDropdownOptions() {
                tagOptions.forEach(option => {
                    const tag = option.dataset.tag;
                    if (selectedTags.has(tag)) {
                        option.classList.add('hidden');
                    } else {
                        option.classList.remove('hidden');
                    }
                });
            }
            
            // Toggle dropdown
            addTagBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                tagsDropdown.classList.toggle('hidden');
            });
            
            // Add tag when option is clicked
            tagOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const tag = this.dataset.tag;
                    if (!selectedTags.has(tag)) {
                        selectedTags.add(tag);
                        renderSelectedTags();
                        tagsDropdown.classList.add('hidden');
                    }
                });
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!tagsDropdown.contains(e.target) && e.target !== addTagBtn) {
                    tagsDropdown.classList.add('hidden');
                }
            });
            
            // Initial render
            renderSelectedTags();
        });
    </script>
@endsection
