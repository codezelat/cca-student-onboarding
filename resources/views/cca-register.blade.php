<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>Registration Form - Codezela Career Accelerator</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icon.png') }}">
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-YDEF398QWX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-YDEF398QWX');
    </script>
    
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

    <div class="min-h-screen py-6 sm:py-12 px-3 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-6 sm:mb-8">
                <a href="{{ url('/') }}" class="inline-block mb-4 sm:mb-6 group">
                    <img src="{{ asset('images/logo-wide.png') }}" 
                         alt="Codezela Career Accelerator" 
                         class="h-12 sm:h-16 md:h-20 mx-auto transition-transform duration-300 group-hover:scale-105">
                </a>
                
                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent mb-2 sm:mb-3 px-2">
                    Codezela Career Accelerator
                </h1>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-semibold text-gray-700 mb-3 sm:mb-4 px-2">
                    Registration Form
                </h2>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-4 sm:mb-6 p-4 sm:p-6 rounded-xl sm:rounded-2xl bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 glass">
                    <div class="flex items-start gap-2 sm:gap-3">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm sm:text-base text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Registration Form -->
            <form method="POST" 
                  action="{{ route('cca.register.store') }}" 
                  enctype="multipart/form-data"
                  x-data="registrationForm()"
                  @submit="handleSubmit"
                  class="space-y-6 sm:space-y-8">
                @csrf

                <!-- Section 1: Program Selection -->
                <div class="card-glass">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-4 sm:mb-6">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center shadow-lg flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Program Information</h2>
                            <p class="text-xs sm:text-sm text-gray-600 mt-0.5">Enter your program ID provided by our team</p>
                        </div>
                    </div>

                    <!-- Multiple Programs Tip -->
                    <div class="mb-4 sm:mb-6 p-3 sm:p-4 rounded-lg sm:rounded-xl bg-blue-50 border border-blue-200">
                        <p class="text-xs sm:text-sm text-blue-800 leading-relaxed">
                            <strong class="inline-flex items-center gap-1">💡 Tip:</strong> Registering for multiple programs? Submit a separate registration form for each program.
                        </p>
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
                                   placeholder="Enter your program ID (E.g., CCA-PM25)"
                                   class="input-glass uppercase" 
                                   required>
                            <p class="mt-2 text-xs text-gray-600 flex items-start gap-2">
                                <svg class="w-4 h-4 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>
                                    Enter the Program ID provided by our team. <strong>Don't have one?</strong> Contact our support team to get yours.
                                </span>
                            </p>
                            @error('program_id')
                                <div class="mt-3 p-4 rounded-xl bg-red-50 border-l-4 border-red-500">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-sm text-red-700 font-medium">{{ $message }}</p>
                                    </div>
                                </div>
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
                            <p class="text-sm text-gray-600">Enter your details exactly as they appear on your official documents</p>
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
                                   placeholder="Enter your full name as per official documents"
                                   class="input-glass" 
                                   required>
                            <p class="mt-1 text-xs text-gray-600">Use your name exactly as it appears on your ID or passport</p>
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
                                   placeholder="Enter name with initials"
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
                                National ID / NIC Number <span class="text-gray-500 text-xs">(Sri Lankan students)</span>
                            </label>
                            <input type="text" 
                                   id="nic_number" 
                                   name="nic_number" 
                                   x-model="formData.nic_number"
                                   value="{{ old('nic_number') }}"
                                   placeholder="Enter your NIC number"
                                   class="input-glass">
                            <p class="mt-1 text-xs text-gray-600">
                                <strong>International students:</strong> Leave this blank and use your passport number instead
                            </p>
                            @error('nic_number')
                                <div class="mt-3 p-4 rounded-xl bg-red-50 border-l-4 border-red-500">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-sm text-red-700 font-medium">{{ $message }}</p>
                                    </div>
                                </div>
                            @enderror
                        </div>

                        <!-- Passport Number -->
                        <div>
                            <label for="passport_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                Passport Number <span class="text-gray-500 text-xs">(Required for international students)</span>
                            </label>
                            <input type="text" 
                                   id="passport_number" 
                                   name="passport_number" 
                                   value="{{ old('passport_number') }}"
                                   placeholder="Enter your passport number"
                                   class="input-glass">
                            <p class="mt-1 text-xs text-gray-600">
                                <strong>Note:</strong> You must provide either NIC or Passport number for identification
                            </p>
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
                                   placeholder="Enter your nationality"
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
                            <p class="text-sm text-gray-600">Provide accurate contact details - we'll use these for all official communications</p>
                        </div>
                    </div>

                    <!-- Important Tip -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📱</span>
                            <div>
                                <p class="text-sm font-semibold text-blue-900 mb-1">Communication Channels</p>
                                <p class="text-sm text-blue-800">
                                    <strong>WhatsApp:</strong> Our primary channel for quick updates, class notifications, and urgent announcements.<br>
                                    <strong>Email:</strong> Used for official documents, certificates, and formal correspondence. Make sure you have regular access to both.
                                </p>
                            </div>
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
                            <p class="mt-1 text-xs text-gray-600">
                                This address will be used for all official correspondence, courier deliveries, and on your certificates. Make sure it's complete and accurate.
                            </p>
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
                                       placeholder="Enter postal code"
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
                                       placeholder="Enter your email address"
                                       class="input-glass" 
                                       required>
                                <p class="mt-1 text-xs text-gray-600">We'll send important updates to this email</p>
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
                                       placeholder="Enter your WhatsApp number with country code"
                                       class="input-glass" 
                                       required>
                                <p class="mt-1 text-xs text-gray-600">Include country code (e.g., +94 for Sri Lanka)</p>
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
                                       placeholder="Enter home/landline number (optional)"
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
                                       placeholder="Enter guardian or emergency contact name"
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
                                       placeholder="Enter contact number with country code"
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
                            <p class="text-sm text-gray-600">Tell us about your educational background or work experience</p>
                        </div>
                    </div>

                    <!-- Important Tip -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">🎓</span>
                            <div>
                                <p class="text-sm font-semibold text-blue-900 mb-1">Choosing Between Academic & Work Experience</p>
                                <p class="text-sm text-blue-800">
                                    <strong>Select "Currently Pursuing" or "Completed":</strong> If you're a student or have graduated from school/university, choose your educational qualification.<br>
                                    <strong>Select "Work Experience":</strong> If you're a working professional or have significant job experience, even without formal higher education. You'll provide your current/most recent company details instead of institution information.
                                </p>
                            </div>
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

                        <!-- Work Experience Company Name (if work_experience selected) -->
                        <div x-show="formData.highest_qualification === 'work_experience'" 
                             x-transition
                             x-cloak>
                            <label for="qualification_other_details" class="block text-sm font-semibold text-gray-700 mb-2">
                                Company/Organization Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="qualification_other_details" 
                                   name="qualification_other_details" 
                                   value="{{ old('qualification_other_details') }}"
                                   placeholder="Enter your current or most recent employer"
                                   class="input-glass"
                                   :required="formData.highest_qualification === 'work_experience'">
                            @error('qualification_other_details')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Qualification Status -->
                            <div>
                                <label for="qualification_status" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <span x-show="formData.highest_qualification === 'work_experience'">Employment Status</span>
                                    <span x-show="formData.highest_qualification !== 'work_experience'">Qualification Status</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <select id="qualification_status" 
                                        name="qualification_status" 
                                        x-model="formData.qualification_status"
                                        class="input-glass" 
                                        required>
                                    <option value="">Select Status</option>
                                    <template x-if="formData.highest_qualification === 'work_experience'">
                                        <optgroup label="Employment Status">
                                            <option value="completed" {{ old('qualification_status') == 'completed' ? 'selected' : '' }}>Left/Completed</option>
                                            <option value="ongoing" {{ old('qualification_status') == 'ongoing' ? 'selected' : '' }}>Currently Working</option>
                                        </optgroup>
                                    </template>
                                    <template x-if="formData.highest_qualification !== 'work_experience'">
                                        <optgroup label="Study Status">
                                            <option value="completed" {{ old('qualification_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="ongoing" {{ old('qualification_status') == 'ongoing' ? 'selected' : '' }}>Currently Pursuing</option>
                                        </optgroup>
                                    </template>
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
                                    <span x-show="formData.highest_qualification === 'work_experience'">Employment End Date</span>
                                    <span x-show="formData.highest_qualification !== 'work_experience'">Date of Completion</span>
                                    <span class="text-red-500">*</span>
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
                                    <span x-show="formData.highest_qualification === 'work_experience'">Employment Start Date</span>
                                    <span x-show="formData.highest_qualification !== 'work_experience'">Expected Completion Date</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       id="qualification_expected_completion_date" 
                                       name="qualification_expected_completion_date" 
                                       value="{{ old('qualification_expected_completion_date') }}"
                                       :max="formData.highest_qualification === 'work_experience' ? '{{ date('Y-m-d') }}' : null"
                                       :min="formData.highest_qualification !== 'work_experience' ? '{{ date('Y-m-d', strtotime('+1 day')) }}' : null"
                                       class="input-glass"
                                       :required="formData.qualification_status === 'ongoing'">
                                <p class="mt-1 text-xs text-gray-600" x-show="formData.highest_qualification === 'work_experience'">
                                    When did you start working at this company?
                                </p>
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
                            <p class="text-sm text-gray-600">Upload clear copies of your documents - phone photos are perfectly acceptable</p>
                        </div>
                    </div>

                    <!-- Important Tip -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📸</span>
                            <div>
                                <p class="text-sm font-semibold text-blue-900 mb-1">About Document Photos</p>
                                <p class="text-sm text-blue-800">
                                    <strong>File size limit:</strong> Each file must be under 10MB. Quality photos are usually fine, but if your file is too big, try compressing the file or reducing the resolution slightly.<br>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <!-- Academic/Work Qualification Proof -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-800 mb-4">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Academic or Work Qualification Proof <span class="text-red-500">*</span>
                                </span>
                                <span class="text-xs font-normal text-gray-600 mt-1 block">Upload your degree, diploma, or experience letter (up to 2 files)</span>
                            </label>
                            <div class="space-y-4">
                                <div class="relative">
                                    <div class="upload-area group/upload" onclick="document.getElementById('academic_doc_1').click()">
                                        <input type="file" 
                                               id="academic_doc_1"
                                               name="academic_qualification_documents[]" 
                                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.heic,.webp"
                                               @change="handleFileSelect($event, 'academic_1')"
                                               class="hidden">
                                        <div class="text-center" x-show="!formData.academic_1">
                                            <svg class="w-12 h-12 mx-auto text-primary-400 group-hover/upload:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                            <p class="mt-3 text-sm font-semibold text-gray-700">Click to upload Document #1</p>
                                            <p class="mt-1 text-xs text-gray-500">PDF, DOC, DOCX, JPG, JPEG, PNG, HEIC, WEBP • Max 10MB</p>
                                        </div>
                                        <div x-show="formData.academic_1" class="flex items-center gap-3">
                                            <svg class="w-8 h-8 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 truncate" x-text="formData.academic_1"></p>
                                                <p class="text-xs text-gray-500">Click to change file</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative">
                                    <div class="upload-area-optional group/upload" onclick="document.getElementById('academic_doc_2').click()">
                                        <input type="file" 
                                               id="academic_doc_2"
                                               name="academic_qualification_documents[]" 
                                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.heic,.webp"
                                               @change="handleFileSelect($event, 'academic_2')"
                                               class="hidden">
                                        <div class="text-center" x-show="!formData.academic_2">
                                            <svg class="w-10 h-10 mx-auto text-gray-400 group-hover/upload:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            <p class="mt-2 text-sm font-medium text-gray-600">Add Document #2 (Optional)</p>
                                            <p class="mt-1 text-xs text-gray-500">PDF, DOC, DOCX, JPG, JPEG, PNG, HEIC, WEBP • Max 10MB</p>
                                        </div>
                                        <div x-show="formData.academic_2" class="flex items-center gap-3">
                                            <svg class="w-8 h-8 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 truncate" x-text="formData.academic_2"></p>
                                                <p class="text-xs text-gray-500">Click to change file</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('academic_qualification_documents.*')
                                <div class="mt-3 p-3 rounded-lg bg-red-50 border border-red-200">
                                    <p class="text-xs text-red-700 font-medium flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                </div>
                            @enderror
                        </div>

                        <!-- NIC Documents -->
                        <div class="group" x-show="formData.nic_number && formData.nic_number.trim() !== ''" x-transition>
                            <label class="block text-sm font-semibold text-gray-800 mb-4">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                    </svg>
                                    National ID Copy <span class="text-red-500">*</span>
                                </span>
                                <span class="text-xs font-normal text-gray-600 mt-1 block">Upload both sides of your NIC (Front & Back)</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="relative">
                                    <div class="upload-area group/upload" onclick="document.getElementById('nic_doc_1').click()">
                                        <input type="file" 
                                               id="nic_doc_1"
                                               name="nic_documents[]" 
                                               accept=".pdf,.jpg,.jpeg,.png,.heic,.webp"
                                               @change="handleFileSelect($event, 'nic_1')"
                                               class="hidden"
                                               :required="formData.nic_number && formData.nic_number.trim() !== ''">
                                        <div class="text-center" x-show="!formData.nic_1">
                                            <svg class="w-10 h-10 mx-auto text-primary-400 group-hover/upload:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <p class="mt-2 text-xs font-semibold text-gray-700">NIC Front</p>
                                            <p class="mt-1 text-xs text-gray-500">JPG, JPEG, PNG, PDF, HEIC, WEBP</p>
                                        </div>
                                        <div x-show="formData.nic_1" class="text-center">
                                            <svg class="w-8 h-8 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <p class="text-xs font-semibold text-gray-900 mt-2 truncate px-2" x-text="formData.nic_1"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative">
                                    <div class="upload-area-optional group/upload" onclick="document.getElementById('nic_doc_2').click()">
                                        <input type="file" 
                                               id="nic_doc_2"
                                               name="nic_documents[]" 
                                               accept=".pdf,.jpg,.jpeg,.png,.heic,.webp"
                                               @change="handleFileSelect($event, 'nic_2')"
                                               class="hidden">
                                        <div class="text-center" x-show="!formData.nic_2">
                                            <svg class="w-10 h-10 mx-auto text-gray-400 group-hover/upload:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <p class="mt-2 text-xs font-medium text-gray-600">NIC Back</p>
                                            <p class="mt-1 text-xs text-gray-500">JPG, JPEG, PNG, PDF, HEIC, WEBP</p>
                                        </div>
                                        <div x-show="formData.nic_2" class="text-center">
                                            <svg class="w-8 h-8 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <p class="text-xs font-semibold text-gray-900 mt-2 truncate px-2" x-text="formData.nic_2"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('nic_documents.*')
                                <div class="mt-3 p-3 rounded-lg bg-red-50 border border-red-200">
                                    <p class="text-xs text-red-700 font-medium flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                </div>
                            @enderror
                        </div>

                        <!-- Passport Documents (Optional) -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-800 mb-4">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                    Passport Copy <span class="text-gray-500 text-xs font-normal">(Optional)</span>
                                </span>
                                <span class="text-xs font-normal text-gray-600 mt-1 block">If you have a passport, upload both information page & back page</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="relative">
                                    <div class="upload-area-optional group/upload" onclick="document.getElementById('passport_doc_1').click()">
                                        <input type="file" 
                                               id="passport_doc_1"
                                               name="passport_documents[]" 
                                               accept=".pdf,.jpg,.jpeg,.png,.heic,.webp"
                                               @change="handleFileSelect($event, 'passport_1')"
                                               class="hidden">
                                        <div class="text-center" x-show="!formData.passport_1">
                                            <svg class="w-10 h-10 mx-auto text-gray-400 group-hover/upload:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            <p class="mt-2 text-xs font-medium text-gray-600">Information Page</p>
                                            <p class="mt-1 text-xs text-gray-500">JPG, JPEG, PNG, PDF, HEIC, WEBP</p>
                                        </div>
                                        <div x-show="formData.passport_1" class="text-center">
                                            <svg class="w-8 h-8 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <p class="text-xs font-semibold text-gray-900 mt-2 truncate px-2" x-text="formData.passport_1"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative">
                                    <div class="upload-area-optional group/upload" onclick="document.getElementById('passport_doc_2').click()">
                                        <input type="file" 
                                               id="passport_doc_2"
                                               name="passport_documents[]" 
                                               accept=".pdf,.jpg,.jpeg,.png,.heic,.webp"
                                               @change="handleFileSelect($event, 'passport_2')"
                                               class="hidden">
                                        <div class="text-center" x-show="!formData.passport_2">
                                            <svg class="w-10 h-10 mx-auto text-gray-400 group-hover/upload:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            <p class="mt-2 text-xs font-medium text-gray-600">Back Page</p>
                                            <p class="mt-1 text-xs text-gray-500">JPG, JPEG, PNG, PDF, HEIC, WEBP</p>
                                        </div>
                                        <div x-show="formData.passport_2" class="text-center">
                                            <svg class="w-8 h-8 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <p class="text-xs font-semibold text-gray-900 mt-2 truncate px-2" x-text="formData.passport_2"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('passport_documents.*')
                                <div class="mt-3 p-3 rounded-lg bg-red-50 border border-red-200">
                                    <p class="text-xs text-red-700 font-medium flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                </div>
                            @enderror
                        </div>

                        <!-- Passport Size Photo -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-800 mb-4">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Passport-Size Photo (2x2 inch) <span class="text-red-500">*</span>
                                </span>
                                <span class="text-xs font-normal text-gray-600 mt-1 block">Recent color photo for your student ID card</span>
                            </label>
                            <div class="upload-area group/upload" onclick="document.getElementById('passport_photo').click()">
                                <input type="file" 
                                       id="passport_photo"
                                       name="passport_photo" 
                                       accept=".jpg,.jpeg,.png,.heic,.webp"
                                       @change="handleFileSelect($event, 'photo')"
                                       class="hidden">
                                <div class="text-center" x-show="!formData.photo">
                                    <svg class="w-12 h-12 mx-auto text-primary-400 group-hover/upload:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="mt-3 text-sm font-semibold text-gray-700">Upload Your Photo</p>
                                    <p class="mt-1 text-xs text-gray-500">JPG, JPEG, PNG, HEIC, WEBP • Max 10MB</p>
                                </div>
                                <div x-show="formData.photo" class="flex items-center gap-3">
                                    <svg class="w-8 h-8 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate" x-text="formData.photo"></p>
                                        <p class="text-xs text-gray-500">Click to change photo</p>
                                    </div>
                                </div>
                            </div>
                            @error('passport_photo')
                                <div class="mt-3 p-3 rounded-lg bg-red-50 border border-red-200">
                                    <p class="text-xs text-red-700 font-medium flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                </div>
                            @enderror
                        </div>

                        <!-- Payment Slip -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-800 mb-4">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/>
                                    </svg>
                                    Payment Confirmation Slip <span class="text-red-500">*</span>
                                </span>
                                <span class="text-xs font-normal text-gray-600 mt-1 block">Upload proof of payment for program registration fee</span>
                            </label>
                            <div class="upload-area group/upload" onclick="document.getElementById('payment_slip').click()">
                                <input type="file" 
                                       id="payment_slip"
                                       name="payment_slip" 
                                       accept=".pdf,.jpg,.jpeg,.png,.heic,.webp"
                                       @change="handleFileSelect($event, 'payment')"
                                       class="hidden">
                                <div class="text-center" x-show="!formData.payment">
                                    <svg class="w-12 h-12 mx-auto text-primary-400 group-hover/upload:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="mt-3 text-sm font-semibold text-gray-700">Upload Payment Slip</p>
                                    <p class="mt-1 text-xs text-gray-500">PDF, JPG, JPEG, PNG, HEIC, WEBP • Max 10MB</p>
                                </div>
                                <div x-show="formData.payment" class="flex items-center gap-3">
                                    <svg class="w-8 h-8 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate" x-text="formData.payment"></p>
                                        <p class="text-xs text-gray-500">Click to change file</p>
                                    </div>
                                </div>
                            </div>
                            @error('payment_slip')
                                <div class="mt-3 p-3 rounded-lg bg-red-50 border border-red-200">
                                    <p class="text-xs text-red-700 font-medium flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Terms and Submit -->
                <div class="card-glass">
                    <div class="space-y-6">
                        <!-- Terms Acceptance -->
                        <div class="p-5 rounded-xl bg-gradient-to-br from-primary-50 to-secondary-50 border-2 border-primary-200">
                            <div class="flex items-start gap-4">
                                <input type="checkbox" 
                                       id="terms_accepted" 
                                       name="terms_accepted" 
                                       value="1"
                                       class="mt-1.5 w-5 h-5 rounded border-primary-300 text-primary-600 focus:ring-primary-500 focus:ring-2 flex-shrink-0 cursor-pointer"
                                       required>
                                <label for="terms_accepted" class="text-sm text-gray-800 cursor-pointer select-none">
                                    <span class="font-semibold">I confirm that:</span>
                                    <ul class="mt-2 space-y-1 text-gray-700">
                                        <li class="flex items-start gap-2">
                                            <svg class="w-4 h-4 text-primary-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            All information provided is true and accurate
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <svg class="w-4 h-4 text-primary-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            I understand false information may result in application rejection
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <svg class="w-4 h-4 text-primary-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            I agree to abide by all institution rules and regulations
                                        </li>
                                    </ul>
                                </label>
                            </div>
                        </div>
                        @error('terms_accepted')
                            <div class="p-4 rounded-xl bg-red-50 border-l-4 border-red-500">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm text-red-700 font-medium">{{ $message }}</p>
                                </div>
                            </div>
                        @enderror

                        <!-- Submit Button -->
                        <button type="submit" 
                                id="submitBtn"
                                class="w-full px-8 py-5 rounded-2xl bg-gradient-to-r from-primary-500 to-secondary-500 
                                       text-white font-bold text-lg hover:from-primary-600 hover:to-secondary-600 
                                       transition-all duration-300 shadow-2xl hover:shadow-primary-500/50 hover:scale-[1.02]
                                       flex items-center justify-center gap-3 group">
                            <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Submit My Registration
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </button>

                        <!-- Debug: Backup submit button for testing -->
                        <!-- <button type="button" 
                                id="backupSubmitBtn"
                                onclick="document.getElementById('registrationForm').submit();"
                                class="w-full mt-4 px-8 py-3 rounded-xl bg-gray-500 hover:bg-gray-600 
                                       text-white font-semibold transition-all duration-300
                                       flex items-center justify-center gap-2"
                                style="display: none;">
                            🔧 Debug: Force Submit (if main button doesn't work)
                        </button> -->

                        <!-- Important Notes -->
                        <div class="mt-4 p-4 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="text-sm text-blue-900">
                                    <p class="font-semibold mb-1">Need Help?</p>
                                    <p class="text-blue-800">If you have any questions or issues while filling out this form, please contact our support team for assistance.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Debug: Check if form submission is working
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, setting up form debugging...');
            
            try {
                const form = document.querySelector('form');
                const submitBtn = document.getElementById('submitBtn');
                
                if (form) {
                    console.log('Form found:', form);
                    console.log('Form action:', form.action);
                    console.log('Form method:', form.method);
                    console.log('Form enctype:', form.enctype);
                    
                    // Check for required fields
                    const requiredFields = form.querySelectorAll('[required]');
                    console.log('Required fields found:', requiredFields.length);
                    
                    form.addEventListener('submit', function(e) {
                        console.log('Form submit event triggered');
                        
                        // Check if this is a test submission
                        if (e.submitter && e.submitter.id === 'submitBtn') {
                            console.log('Submit button triggered the form submission');
                        }
                        
                        // Basic validation check - but don't prevent submission, let browser handle it
                        let hasErrors = false;
                        const requiredFieldsArray = Array.from(requiredFields);
                        
                        // Custom validation for required file uploads
                        const academicDoc1 = form.querySelector('#academic_doc_1');
                        const passportPhoto = form.querySelector('#passport_photo');
                        const paymentSlip = form.querySelector('#payment_slip');
                        
                        if (academicDoc1 && (!academicDoc1.files || academicDoc1.files.length === 0)) {
                            console.log('Academic qualification document is required');
                            hasErrors = true;
                            alert('Please upload your academic qualification document before submitting.');
                            // Scroll to the academic documents section
                            academicDoc1.closest('.group').scrollIntoView({ behavior: 'smooth', block: 'center' });
                            e.preventDefault();
                            return false;
                        }
                        
                        if (passportPhoto && (!passportPhoto.files || passportPhoto.files.length === 0)) {
                            console.log('Passport photo is required');
                            hasErrors = true;
                            alert('Please upload your passport-size photo before submitting.');
                            // Scroll to the photo section
                            passportPhoto.closest('.group').scrollIntoView({ behavior: 'smooth', block: 'center' });
                            e.preventDefault();
                            return false;
                        }
                        
                        if (paymentSlip && (!paymentSlip.files || paymentSlip.files.length === 0)) {
                            console.log('Payment slip is required');
                            hasErrors = true;
                            alert('Please upload your payment confirmation slip before submitting.');
                            // Scroll to the payment section
                            paymentSlip.closest('.group').scrollIntoView({ behavior: 'smooth', block: 'center' });
                            e.preventDefault();
                            return false;
                        }

                        requiredFieldsArray.forEach(field => {
                            if (field.type === 'file') {
                                // Skip file validation here as we handle it above
                                return;
                            } else if (field.type === 'checkbox') {
                                // For checkboxes, check if checked
                                if (field.hasAttribute('required') && !field.checked) {
                                    console.log('Unchecked required checkbox:', field.name || field.id);
                                    hasErrors = true;
                                }
                            } else {
                                // For other inputs, check if empty
                                if (field.hasAttribute('required') && (!field.value || field.value.trim() === '')) {
                                    console.log('Empty required field:', field.name || field.id);
                                    hasErrors = true;
                                }
                            }
                        });
                        
                        if (hasErrors) {
                            console.log('Form has validation errors, preventing submission');
                            e.preventDefault();
                            return false;
                        } else {
                            console.log('Form validation passed, submitting...');
                        }
                    });
                    
                    if (submitBtn) {
                        submitBtn.addEventListener('click', function(e) {
                            console.log('Submit button clicked');
                            
                            // Add a small delay to ensure all Alpine.js updates are processed
                            setTimeout(() => {
                                console.log('Attempting to submit form after Alpine.js processing');
                                // Don't prevent default - let the normal form submission happen
                            }, 100);
                        });
                    }
                } else {
                    console.error('Form not found!');
                }
                
                // Show backup submit button after 5 seconds for debugging
                setTimeout(() => {
                    const backupBtn = document.getElementById('backupSubmitBtn');
                    if (backupBtn) {
                        backupBtn.style.display = 'flex';
                        console.log('Backup submit button is now visible for debugging');
                    }
                }, 5000);
                
            } catch (error) {
                console.error('Error setting up form debugging:', error);
            }
        });

        function registrationForm() {
            return {
                formData: {
                    program_id: '{{ old('program_id') }}',
                    nic_number: '{{ old('nic_number') }}',
                    country_of_residence: '{{ old('country_of_residence') }}',
                    country: '{{ old('country') }}',
                    province: '{{ old('province') }}',
                    district: '{{ old('district') }}',
                    highest_qualification: '{{ old('highest_qualification') }}',
                    qualification_status: '{{ old('qualification_status') }}',
                    academic_1: '',
                    academic_2: '',
                    nic_1: '',
                    nic_2: '',
                    passport_1: '',
                    passport_2: '',
                    photo: '',
                    payment: ''
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

                handleFileSelect(event, fieldName) {
                    try {
                        const file = event.target.files[0];
                        if (file) {
                            const maxSize = 10 * 1024 * 1024; // 10MB
                            if (file.size > maxSize) {
                                alert(`File "${file.name}" is too large!\n\nMaximum file size is 10MB.\nYour file is ${(file.size / (1024 * 1024)).toFixed(2)}MB.\n\nPlease compress or choose a smaller file.`);
                                event.target.value = '';
                                this.formData[fieldName] = '';
                                return false;
                            }
                            this.formData[fieldName] = file.name;
                            console.log(`File selected for ${fieldName}:`, file.name);
                        }
                        return true;
                    } catch (error) {
                        console.error('Error handling file select:', error);
                        return false;
                    }
                },

                validateFileSize(event) {
                    return this.handleFileSelect(event, 'temp');
                },

                handleSubmit(event) {
                    console.log('Alpine.js handleSubmit called');
                    // Form will submit normally
                    return true;
                },

                init() {
                    console.log('Alpine.js registrationForm initialized');
                    if (this.formData.program_id) {
                        this.validateProgramId();
                    }
                    if (this.formData.province) {
                        this.handleProvinceChange();
                    }
                    
                    // Additional debugging
                    this.$nextTick(() => {
                        console.log('Alpine.js form data ready:', this.formData);
                    });
                }
            }
        }
    </script>

    <style>
        /* Premium Card Glass Effect with Depth */
        .card-glass {
            @apply p-4 sm:p-6 lg:p-8 rounded-xl sm:rounded-2xl lg:rounded-3xl 
                   bg-white/75 backdrop-blur-2xl border border-white/90 
                   shadow-xl hover:shadow-2xl
                   transition-all duration-500 ease-out
                   hover:bg-white/80 hover:border-white/95
                   hover:-translate-y-0.5;
            background-image: linear-gradient(135deg, rgba(255, 255, 255, 0.8) 0%, rgba(255, 255, 255, 0.7) 100%);
        }

        /* Glass Effect */
        .glass {
            @apply backdrop-blur-xl;
        }

        /* Premium Input Glass Effect */
        .input-glass {
            @apply w-full px-3 py-2.5 sm:px-4 sm:py-3.5 lg:px-5 lg:py-4 text-sm sm:text-base lg:text-lg
                   bg-white/70 backdrop-blur-md rounded-lg sm:rounded-xl lg:rounded-2xl
                   focus:ring-4 focus:ring-primary-500/30 focus:border-primary-400 
                   transition-all duration-300 placeholder:text-gray-500
                   hover:bg-white/80 hover:shadow-md
                   shadow-sm
                   font-medium text-gray-800;
            border: 2px solid rgba(139, 92, 246, 0.25);
            background-image: linear-gradient(to bottom, rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.65));
        }

        .input-glass:hover {
            border-color: rgba(139, 92, 246, 0.4);
            box-shadow: 0 4px 6px -1px rgba(139, 92, 246, 0.1), 0 2px 4px -1px rgba(139, 92, 246, 0.06);
        }

        .input-glass:focus {
            background-image: linear-gradient(to bottom, rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.75));
            border-color: rgba(139, 92, 246, 0.6);
        }

        /* Premium Upload Areas with Enhanced Glassmorphism */
        .upload-area {
            @apply relative p-4 sm:p-6 lg:p-8 rounded-xl sm:rounded-2xl lg:rounded-3xl
                   backdrop-blur-xl border-2 
                   active:scale-[0.98]
                   transition-all duration-300 cursor-pointer 
                   min-h-[120px] sm:min-h-[140px] lg:min-h-[160px]
                   flex items-center justify-center
                   touch-manipulation
                   shadow-lg hover:shadow-2xl;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.65) 0%, rgba(255, 255, 255, 0.5) 100%);
            border-color: rgba(139, 92, 246, 0.3);
            border-style: dashed;
        }

        .upload-area:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.8) 0%, rgba(255, 255, 255, 0.65) 100%);
            border-color: rgba(139, 92, 246, 0.5);
            transform: translateY(-2px);
        }

        .upload-area-optional {
            @apply relative p-4 sm:p-6 lg:p-8 rounded-xl sm:rounded-2xl lg:rounded-3xl
                   backdrop-blur-md border-2
                   active:scale-[0.98]
                   transition-all duration-300 cursor-pointer 
                   min-h-[120px] sm:min-h-[140px] lg:min-h-[160px]
                   flex items-center justify-center
                   touch-manipulation
                   shadow-md hover:shadow-xl;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.5) 0%, rgba(255, 255, 255, 0.35) 100%);
            border-color: rgba(156, 163, 175, 0.3);
            border-style: dashed;
        }

        .upload-area-optional:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.7) 0%, rgba(255, 255, 255, 0.55) 100%);
            border-color: rgba(139, 92, 246, 0.4);
            transform: translateY(-2px);
        }

        /* Enhanced Floating Animation */
        @keyframes blob {
            0%, 100% {
                transform: translate(0px, 0px) scale(1);
            }
            33% {
                transform: translate(30px, -50px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
        }

        .animate-blob {
            animation: blob 7s infinite cubic-bezier(0.4, 0, 0.2, 1);
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        /* Enhanced Gradient Glow Effect for Headers */
        .gradient-glow {
            position: relative;
            background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 50%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Premium Button Styles */
        button[type="submit"], .btn-primary {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 50%, #7c3aed 100%);
            background-size: 200% 200%;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        button[type="submit"]:hover, .btn-primary:hover {
            background-position: 100% 0;
            box-shadow: 0 20px 40px -15px rgba(139, 92, 246, 0.6);
        }

        button[type="submit"]::before, .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        button[type="submit"]:hover::before, .btn-primary:hover::before {
            left: 100%;
        }

        /* Enhanced Tip/Info Boxes */
        .tip-box {
            @apply p-3 sm:p-4 lg:p-5 rounded-lg sm:rounded-xl lg:rounded-2xl
                   backdrop-blur-xl border-2
                   shadow-lg hover:shadow-xl transition-all duration-300;
            background: linear-gradient(135deg, rgba(219, 234, 254, 0.9) 0%, rgba(191, 219, 254, 0.8) 100%);
        }

        /* Prevent zoom on input focus (iOS) */
        @media screen and (max-width: 640px) {
            select, textarea, input[type="text"], input[type="password"],
            input[type="datetime"], input[type="datetime-local"],
            input[type="date"], input[type="month"], input[type="time"],
            input[type="week"], input[type="number"], input[type="email"],
            input[type="url"], input[type="search"], input[type="tel"] {
                font-size: 16px !important;
            }
        }

        /* Better touch targets on mobile */
        @media (max-width: 640px) {
            button, a, input[type="checkbox"], input[type="radio"], select {
                min-height: 44px;
                min-width: 44px;
            }
            
            label {
                -webkit-tap-highlight-color: transparent;
            }
        }

        /* Desktop: Enhanced touch targets and visibility */
        @media (min-width: 1024px) {
            .input-glass, select {
                min-height: 56px;
            }

            button, .btn-primary {
                min-height: 60px;
            }

            /* Enhanced hover states for desktop */
            .card-glass:hover {
                transform: translateY(-4px);
                box-shadow: 0 25px 50px -12px rgba(139, 92, 246, 0.25);
            }

            /* Better select styling */
            select {
                background-size: 2em 2em;
                padding-right: 3rem;
            }
        }

        /* File input styling */
        .file-input {
            @apply w-full px-3 py-2.5 sm:px-4 sm:py-3 lg:px-5 lg:py-4
                   bg-white/60 backdrop-blur-md rounded-lg sm:rounded-xl lg:rounded-2xl
                   focus:ring-4 focus:ring-primary-500/30 focus:border-transparent 
                   transition-all duration-300
                   file:mr-3 sm:file:mr-4 file:py-1.5 file:px-3 sm:file:py-2 sm:file:px-4 lg:file:py-2.5 lg:file:px-5
                   file:rounded-md sm:file:rounded-lg lg:file:rounded-xl
                   file:border-0 file:text-xs sm:file:text-sm lg:file:text-base file:font-semibold 
                   file:bg-primary-50 file:text-primary-700 
                   hover:file:bg-primary-100 file:transition-colors
                   cursor-pointer shadow-sm hover:shadow-md
                   touch-manipulation;
            border: 2px solid rgba(139, 92, 246, 0.25);
            background-image: linear-gradient(to bottom, rgba(255, 255, 255, 0.65), rgba(255, 255, 255, 0.55));
        }

        .file-input:hover {
            border-color: rgba(139, 92, 246, 0.4);
        }

        .file-input:focus {
            border-color: rgba(139, 92, 246, 0.6);
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Custom Scrollbar - Premium Look */
        ::-webkit-scrollbar {
            width: 12px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(139, 92, 246, 0.8), rgba(168, 85, 247, 0.8));
            border-radius: 10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: background 0.3s;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, rgba(139, 92, 246, 1), rgba(168, 85, 247, 1));
        }

        /* Better select dropdown styling */
        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%238b5cf6' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-position: right 1rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 3rem;
            border: 2px solid rgba(139, 92, 246, 0.25) !important;
        }

        select:hover {
            border-color: rgba(139, 92, 246, 0.4) !important;
        }

        select:focus {
            border-color: rgba(139, 92, 246, 0.6) !important;
        }

        @media (max-width: 640px) {
            select {
                background-position: right 0.5rem center;
                padding-right: 2.5rem;
            }
        }

        /* Textarea borders */
        textarea.input-glass {
            resize: vertical;
            min-height: 100px;
        }

        /* Improve checkbox/radio button appearance */
        input[type="checkbox"], input[type="radio"] {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(139, 92, 246, 0.5);
            transition: all 0.2s;
        }

        @media (min-width: 1024px) {
            input[type="checkbox"], input[type="radio"] {
                width: 24px;
                height: 24px;
            }
        }

        input[type="checkbox"]:checked, input[type="radio"]:checked {
            background-color: #8b5cf6;
            border-color: #8b5cf6;
        }

        /* Enhanced Error Messages */
        .error-message {
            @apply p-3 sm:p-4 rounded-lg sm:rounded-xl 
                   bg-red-50/90 backdrop-blur-md border-l-4 border-red-500
                   shadow-lg;
        }

        /* Success Message Enhancement */
        .success-message {
            @apply p-4 sm:p-6 rounded-xl sm:rounded-2xl
                   backdrop-blur-xl border-2 border-green-200
                   shadow-xl;
            background: linear-gradient(135deg, rgba(236, 253, 245, 0.95) 0%, rgba(209, 250, 229, 0.9) 100%);
        }

        /* Label Enhancement for Better Readability */
        label {
            @apply font-semibold text-gray-800 tracking-tight;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
        }

        /* Placeholder Enhancement */
        ::placeholder {
            @apply text-gray-500 font-normal;
            opacity: 0.7;
        }

        /* Focus visible for accessibility */
        *:focus-visible {
            outline: 3px solid rgba(139, 92, 246, 0.5);
            outline-offset: 2px;
        }

        /* Alpine.js cloak */
        [x-cloak] {
            display: none !important;
        }
    </style>
</body>
</html>
