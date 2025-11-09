<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Registration - {{ config('app.name', 'CCA') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <!-- Glassmorphic Background with Purple Liquid Blobs -->
    <div class="fixed inset-0 bg-gradient-to-br from-violet-50 via-purple-50 to-indigo-50 overflow-hidden -z-10">
        <div class="absolute top-0 -left-4 w-96 h-96 bg-violet-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-20 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-flex items-center space-x-3 mb-6 group">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <span class="text-white font-bold text-2xl">C</span>
                    </div>
                    <span class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                        {{ config('app.name', 'CCA') }}
                    </span>
                </a>
                
                <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent mb-3">
                    Student Registration
                </h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Begin your journey with us. Complete the form below to register for your chosen program.
                </p>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 p-6 rounded-2xl bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 glass">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Registration Form -->
            <form method="POST" 
                  action="{{ route('cca.register.store') }}" 
                  enctype="multipart/form-data"
                  x-data="registrationForm()"
                  @submit="handleSubmit"
                  class="space-y-8">
                @csrf

                <!-- Section 1: Program Selection -->
                <div class="card-glass">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Program Information</h2>
                            <p class="text-sm text-gray-600">Select your program and enter your program ID</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="program_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Program ID <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="program_id" 
                                   name="program_id" 
                                   x-model="formData.program_id"
                                   @input="validateProgramId"
                                   value="{{ old('program_id') }}"
                                   placeholder="e.g., PM25, DM25"
                                   class="input-glass uppercase" 
                                   required>
                            <p class="mt-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Don't have a Program ID? Contact our support team. One registration per program allowed.
                            </p>
                            @error('program_id')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                            
                            <!-- Program Info Display -->
                            <div x-show="programInfo" 
                                 x-transition
                                 class="mt-4 p-4 rounded-xl bg-gradient-to-r from-primary-50 to-secondary-50 border border-primary-200">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600 font-medium">Program:</span>
                                        <p class="text-gray-800 font-semibold" x-text="programInfo?.name"></p>
                                    </div>
                                    <div>
                                        <span class="text-gray-600 font-medium">Year:</span>
                                        <p class="text-gray-800 font-semibold" x-text="programInfo?.year"></p>
                                    </div>
                                    <div>
                                        <span class="text-gray-600 font-medium">Duration:</span>
                                        <p class="text-gray-800 font-semibold" x-text="programInfo?.duration"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Personal Information -->
                <div class="card-glass">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Personal Information</h2>
                            <p class="text-sm text-gray-600">Your basic personal details</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div>
                            <label for="full_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="full_name" 
                                   name="full_name" 
                                   value="{{ old('full_name') }}"
                                   placeholder="John Michael Doe"
                                   class="input-glass" 
                                   required>
                            @error('full_name')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Name with Initials -->
                        <div>
                            <label for="name_with_initials" class="block text-sm font-semibold text-gray-700 mb-2">
                                Name with Initials <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name_with_initials" 
                                   name="name_with_initials" 
                                   value="{{ old('name_with_initials') }}"
                                   placeholder="J.M. Doe"
                                   class="input-glass" 
                                   required>
                            @error('name_with_initials')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">
                                Gender <span class="text-red-500">*</span>
                            </label>
                            <select id="gender" name="gender" class="input-glass" required>
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label for="date_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">
                                Date of Birth <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="date_of_birth" 
                                   name="date_of_birth" 
                                   value="{{ old('date_of_birth') }}"
                                   max="{{ date('Y-m-d') }}"
                                   class="input-glass" 
                                   required>
                            @error('date_of_birth')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NIC Number -->
                        <div>
                            <label for="nic_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                NIC Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="nic_number" 
                                   name="nic_number" 
                                   value="{{ old('nic_number') }}"
                                   placeholder="200012345678 or 991234567V"
                                   class="input-glass" 
                                   required>
                            @error('nic_number')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Passport Number -->
                        <div>
                            <label for="passport_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                Passport Number <span class="text-gray-400 text-xs">(if available)</span>
                            </label>
                            <input type="text" 
                                   id="passport_number" 
                                   name="passport_number" 
                                   value="{{ old('passport_number') }}"
                                   placeholder="N1234567"
                                   class="input-glass">
                            @error('passport_number')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nationality -->
                        <div>
                            <label for="nationality" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nationality <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="nationality" 
                                   name="nationality" 
                                   value="{{ old('nationality') }}"
                                   placeholder="Sri Lankan"
                                   class="input-glass" 
                                   required>
                            @error('nationality')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Country of Birth -->
                        <div>
                            <label for="country_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">
                                Country of Birth <span class="text-red-500">*</span>
                            </label>
                            <select id="country_of_birth" 
                                    name="country_of_birth" 
                                    class="input-glass" 
                                    required>
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country }}" {{ old('country_of_birth') == $country ? 'selected' : '' }}>
                                        {{ $country }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country_of_birth')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Country of Permanent Residence -->
                        <div>
                            <label for="country_of_residence" class="block text-sm font-semibold text-gray-700 mb-2">
                                Country of Permanent Residence <span class="text-red-500">*</span>
                            </label>
                            <select id="country_of_residence" 
                                    name="country_of_residence" 
                                    x-model="formData.country_of_residence"
                                    class="input-glass" 
                                    required>
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country }}" {{ old('country_of_residence') == $country ? 'selected' : '' }}>
                                        {{ $country }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country_of_residence')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Contact Information -->
                <div class="card-glass">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Contact Information</h2>
                            <p class="text-sm text-gray-600">How we can reach you</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Permanent Address -->
                        <div>
                            <label for="permanent_address" class="block text-sm font-semibold text-gray-700 mb-2">
                                Permanent Address <span class="text-red-500">*</span>
                            </label>
                            <textarea id="permanent_address" 
                                      name="permanent_address" 
                                      rows="3"
                                      placeholder="Enter your full permanent address"
                                      class="input-glass" 
                                      required>{{ old('permanent_address') }}</textarea>
                            @error('permanent_address')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Country -->
                            <div>
                                <label for="country" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Country <span class="text-red-500">*</span>
                                </label>
                                <select id="country" 
                                        name="country" 
                                        x-model="formData.country"
                                        @change="handleCountryChange"
                                        class="input-glass" 
                                        required>
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country }}" {{ old('country') == $country ? 'selected' : '' }}>
                                            {{ $country }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country')
                                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Postal Code -->
                            <div>
                                <label for="postal_code" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Postal Code <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="postal_code" 
                                       name="postal_code" 
                                       value="{{ old('postal_code') }}"
                                       placeholder="10100"
                                       class="input-glass" 
                                       required>
                                @error('postal_code')
                                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Province (Sri Lanka only) -->
                            <div x-show="formData.country === 'Sri Lanka'" x-transition>
                                <label for="province" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Province <span class="text-red-500">*</span>
                                </label>
                                <select id="province" 
                                        name="province" 
                                        x-model="formData.province"
                                        @change="handleProvinceChange"
                                        class="input-glass"
                                        :required="formData.country === 'Sri Lanka'">
                                    <option value="">Select Province</option>
                                    @foreach($sriLankaDistricts as $province => $districts)
                                        <option value="{{ $province }}" {{ old('province') == $province ? 'selected' : '' }}>
                                            {{ $province }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('province')
                                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- District (Sri Lanka only) -->
                            <div x-show="formData.country === 'Sri Lanka' && formData.province" x-transition>
                                <label for="district" class="block text-sm font-semibold text-gray-700 mb-2">
                                    District <span class="text-red-500">*</span>
                                </label>
                                <select id="district" 
                                        name="district" 
                                        x-model="formData.district"
                                        class="input-glass"
                                        :required="formData.country === 'Sri Lanka'">
                                    <option value="">Select District</option>
                                    <template x-for="district in availableDistricts" :key="district">
                                        <option :value="district" x-text="district"></option>
                                    </template>
                                </select>
                                @error('district')
                                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Email Address -->
                            <div>
                                <label for="email_address" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       id="email_address" 
                                       name="email_address" 
                                       value="{{ old('email_address') }}"
                                       placeholder="john.doe@example.com"
                                       class="input-glass" 
                                       required>
                                @error('email_address')
                                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- WhatsApp Number -->
                            <div>
                                <label for="whatsapp_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                    WhatsApp Number <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" 
                                       id="whatsapp_number" 
                                       name="whatsapp_number" 
                                       value="{{ old('whatsapp_number') }}"
                                       placeholder="+94771234567"
                                       class="input-glass" 
                                       required>
                                @error('whatsapp_number')
                                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Home Contact Number -->
                            <div>
                                <label for="home_contact_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Home Contact Number <span class="text-gray-400 text-xs">(optional)</span>
                                </label>
                                <input type="tel" 
                                       id="home_contact_number" 
                                       name="home_contact_number" 
                                       value="{{ old('home_contact_number') }}"
                                       placeholder="+94112345678"
                                       class="input-glass">
                                @error('home_contact_number')
                                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Guardian Contact Name -->
                            <div>
                                <label for="guardian_contact_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Guardian/Emergency Contact Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="guardian_contact_name" 
                                       name="guardian_contact_name" 
                                       value="{{ old('guardian_contact_name') }}"
                                       placeholder="Jane Doe"
                                       class="input-glass" 
                                       required>
                                @error('guardian_contact_name')
                                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Guardian Contact Number -->
                            <div class="md:col-span-2">
                                <label for="guardian_contact_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Guardian/Emergency Contact Number <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" 
                                       id="guardian_contact_number" 
                                       name="guardian_contact_number" 
                                       value="{{ old('guardian_contact_number') }}"
                                       placeholder="+94771234567"
                                       class="input-glass" 
                                       required>
                                @error('guardian_contact_number')
                                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Qualification Information -->
                <div class="card-glass">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Qualification Information</h2>
                            <p class="text-sm text-gray-600">Your educational background</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Highest Qualification -->
                        <div>
                            <label for="highest_qualification" class="block text-sm font-semibold text-gray-700 mb-2">
                                Highest Qualification <span class="text-red-500">*</span>
                            </label>
                            <select id="highest_qualification" 
                                    name="highest_qualification" 
                                    x-model="formData.highest_qualification"
                                    class="input-glass" 
                                    required>
                                <option value="">Select Qualification</option>
                                <option value="phd" {{ old('highest_qualification') == 'phd' ? 'selected' : '' }}>PhD / Doctorate</option>
                                <option value="msc" {{ old('highest_qualification') == 'msc' ? 'selected' : '' }}>Master's Degree (MSc/MBA/MA)</option>
                                <option value="postgraduate" {{ old('highest_qualification') == 'postgraduate' ? 'selected' : '' }}>Postgraduate Diploma</option>
                                <option value="degree" {{ old('highest_qualification') == 'degree' ? 'selected' : '' }}>Bachelor's Degree</option>
                                <option value="diploma" {{ old('highest_qualification') == 'diploma' ? 'selected' : '' }}>Diploma</option>
                                <option value="work_experience" {{ old('highest_qualification') == 'work_experience' ? 'selected' : '' }}>Professional Work Experience</option>
                                <option value="other" {{ old('highest_qualification') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('highest_qualification')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Other Qualification Details -->
                        <div x-show="formData.highest_qualification === 'other'" 
                             x-transition
                             x-cloak>
                            <label for="qualification_other_details" class="block text-sm font-semibold text-gray-700 mb-2">
                                Please Specify Your Qualification <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="qualification_other_details" 
                                   name="qualification_other_details" 
                                   value="{{ old('qualification_other_details') }}"
                                   placeholder="Describe your qualification"
                                   class="input-glass"
                                   :required="formData.highest_qualification === 'other'">
                            @error('qualification_other_details')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Qualification Status -->
                            <div>
                                <label for="qualification_status" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Qualification Status <span class="text-red-500">*</span>
                                </label>
                                <select id="qualification_status" 
                                        name="qualification_status" 
                                        x-model="formData.qualification_status"
                                        class="input-glass" 
                                        required>
                                    <option value="">Select Status</option>
                                    <option value="completed" {{ old('qualification_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="ongoing" {{ old('qualification_status') == 'ongoing' ? 'selected' : '' }}>Currently Pursuing</option>
                                </select>
                                @error('qualification_status')
                                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Completion Date (if completed) -->
                            <div x-show="formData.qualification_status === 'completed'" 
                                 x-transition
                                 x-cloak>
                                <label for="qualification_completed_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Date of Completion <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       id="qualification_completed_date" 
                                       name="qualification_completed_date" 
                                       value="{{ old('qualification_completed_date') }}"
                                       max="{{ date('Y-m-d') }}"
                                       class="input-glass"
                                       :required="formData.qualification_status === 'completed'">
                                @error('qualification_completed_date')
                                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Expected Completion Date (if ongoing) -->
                            <div x-show="formData.qualification_status === 'ongoing'" 
                                 x-transition
                                 x-cloak>
                                <label for="qualification_expected_completion_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Expected Completion Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       id="qualification_expected_completion_date" 
                                       name="qualification_expected_completion_date" 
                                       value="{{ old('qualification_expected_completion_date') }}"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       class="input-glass"
                                       :required="formData.qualification_status === 'ongoing'">
                                @error('qualification_expected_completion_date')
                                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 5: Required Documents -->
                <div class="card-glass">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Required Documents</h2>
                            <p class="text-sm text-gray-600">Upload necessary documents (max 10MB per file)</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Academic/Work Qualification Proof -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Academic or Work Qualification Proof <span class="text-red-500">*</span>
                                <span class="text-gray-500 font-normal">(Upload up to 2 documents)</span>
                            </label>
                            <div class="space-y-3">
                                <div>
                                    <input type="file" 
                                           name="academic_qualification_documents[]" 
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                           @change="validateFileSize($event)"
                                           class="file-input"
                                           required>
                                </div>
                                <div>
                                    <input type="file" 
                                           name="academic_qualification_documents[]" 
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                           @change="validateFileSize($event)"
                                           class="file-input">
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-gray-600">Accepted: PDF, DOC, DOCX, JPG, PNG (max 10MB each)</p>
                            @error('academic_qualification_documents.*')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NIC Documents -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                NIC Copy <span class="text-red-500">*</span>
                                <span class="text-gray-500 font-normal">(Front and back - up to 2 files)</span>
                            </label>
                            <div class="space-y-3">
                                <div>
                                    <input type="file" 
                                           name="nic_documents[]" 
                                           accept=".pdf,.jpg,.jpeg,.png"
                                           @change="validateFileSize($event)"
                                           class="file-input"
                                           required>
                                </div>
                                <div>
                                    <input type="file" 
                                           name="nic_documents[]" 
                                           accept=".pdf,.jpg,.jpeg,.png"
                                           @change="validateFileSize($event)"
                                           class="file-input">
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-gray-600">Accepted: PDF, JPG, PNG (max 10MB each)</p>
                            @error('nic_documents.*')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Passport Documents -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Passport Copy <span class="text-gray-400 text-xs">(if available, up to 2 files)</span>
                            </label>
                            <div class="space-y-3">
                                <div>
                                    <input type="file" 
                                           name="passport_documents[]" 
                                           accept=".pdf,.jpg,.jpeg,.png"
                                           @change="validateFileSize($event)"
                                           class="file-input">
                                </div>
                                <div>
                                    <input type="file" 
                                           name="passport_documents[]" 
                                           accept=".pdf,.jpg,.jpeg,.png"
                                           @change="validateFileSize($event)"
                                           class="file-input">
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-gray-600">Accepted: PDF, JPG, PNG (max 10MB each)</p>
                            @error('passport_documents.*')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Passport Size Photo -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Passport Size Photo (2x2 inch) <span class="text-red-500">*</span>
                            </label>
                            <input type="file" 
                                   name="passport_photo" 
                                   accept=".jpg,.jpeg,.png"
                                   @change="validateFileSize($event)"
                                   class="file-input"
                                   required>
                            <p class="mt-2 text-xs text-gray-600">Accepted: JPG, PNG (max 10MB)</p>
                            @error('passport_photo')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Slip -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Payment Slip <span class="text-red-500">*</span>
                            </label>
                            <input type="file" 
                                   name="payment_slip" 
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   @change="validateFileSize($event)"
                                   class="file-input"
                                   required>
                            <p class="mt-2 text-xs text-gray-600">Accepted: PDF, JPG, PNG (max 10MB)</p>
                            @error('payment_slip')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Terms and Submit -->
                <div class="card-glass">
                    <div class="space-y-6">
                        <!-- Terms Acceptance -->
                        <div class="flex items-start gap-3">
                            <input type="checkbox" 
                                   id="terms_accepted" 
                                   name="terms_accepted" 
                                   value="1"
                                   class="mt-1 w-5 h-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                   required>
                            <label for="terms_accepted" class="text-sm text-gray-700">
                                I hereby declare that all the information provided above is true and accurate to the best of my knowledge. I understand that any false information may result in the rejection of my application or termination of enrollment. I agree to abide by all rules and regulations of the institution.
                                <span class="text-red-500">*</span>
                            </label>
                        </div>
                        @error('terms_accepted')
                            <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror

                        <!-- Submit Button -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            <button type="submit" 
                                    class="flex-1 px-8 py-4 rounded-xl bg-gradient-to-r from-primary-500 to-secondary-500 
                                           text-white font-semibold text-lg hover:from-primary-600 hover:to-secondary-600 
                                           transition-all duration-300 shadow-xl hover:shadow-2xl hover:scale-105
                                           flex items-center justify-center gap-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Submit Registration
                            </button>
                            
                            <a href="{{ url('/') }}" 
                               class="px-8 py-4 rounded-xl bg-white/60 backdrop-blur-md border-2 border-white/80
                                      text-gray-700 font-semibold text-lg hover:bg-white/80 
                                      transition-all duration-300 shadow-lg hover:shadow-xl
                                      flex items-center justify-center gap-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function registrationForm() {
            return {
                formData: {
                    program_id: '{{ old('program_id') }}',
                    country_of_residence: '{{ old('country_of_residence') }}',
                    country: '{{ old('country') }}',
                    province: '{{ old('province') }}',
                    district: '{{ old('district') }}',
                    highest_qualification: '{{ old('highest_qualification') }}',
                    qualification_status: '{{ old('qualification_status') }}'
                },
                programInfo: null,
                availableDistricts: [],
                sriLankaDistricts: @json($sriLankaDistricts),
                programs: @json($programs),

                validateProgramId() {
                    const programId = this.formData.program_id.toUpperCase();
                    if (programId && this.programs[programId]) {
                        this.programInfo = this.programs[programId];
                    } else {
                        this.programInfo = null;
                    }
                },

                handleCountryChange() {
                    if (this.formData.country !== 'Sri Lanka') {
                        this.formData.province = '';
                        this.formData.district = '';
                        this.availableDistricts = [];
                    }
                },

                handleProvinceChange() {
                    if (this.formData.province && this.sriLankaDistricts[this.formData.province]) {
                        this.availableDistricts = this.sriLankaDistricts[this.formData.province];
                        this.formData.district = '';
                    } else {
                        this.availableDistricts = [];
                    }
                },

                validateFileSize(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const maxSize = 10 * 1024 * 1024; // 10MB
                        if (file.size > maxSize) {
                            alert('File size must not exceed 10MB. Please choose a smaller file.');
                            event.target.value = '';
                            return false;
                        }
                    }
                    return true;
                },

                handleSubmit(event) {
                    // Form will submit normally, this is just for any additional client-side logic
                    return true;
                },

                init() {
                    // Initialize program info if program_id is pre-filled
                    if (this.formData.program_id) {
                        this.validateProgramId();
                    }
                    // Initialize districts if province is pre-filled
                    if (this.formData.province) {
                        this.handleProvinceChange();
                    }
                }
            }
        }
    </script>

    <style>
        .file-input {
            @apply w-full px-4 py-3 bg-white/50 border border-white/60 rounded-xl 
                   focus:ring-2 focus:ring-primary-500 focus:border-transparent 
                   transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg 
                   file:border-0 file:text-sm file:font-semibold 
                   file:bg-primary-50 file:text-primary-700 
                   hover:file:bg-primary-100 cursor-pointer;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</body>
</html>
