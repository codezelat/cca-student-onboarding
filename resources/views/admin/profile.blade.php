@extends('admin.layouts.app')

@section('title', 'Admin Profile | ' . config('app.name', 'CCA'))
@section('meta_description', 'Update your Codezela Career Accelerator admin profile, password, and account preferences.')
@section('og_title', 'Admin Profile | ' . config('app.name', 'CCA'))
@section('og_description', 'Manage your Codezela Career Accelerator admin account details in a secure environment.')
@section('twitter_title', 'Admin Profile | ' . config('app.name', 'CCA'))
@section('twitter_description', 'Manage your Codezela Career Accelerator admin account details in a secure environment.')

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

        <!-- Page Content -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Back to Dashboard -->
                <div class="mb-6">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-primary-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Dashboard
                    </a>
                </div>

                <!-- Page Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                        Admin Profile
                    </h1>
                    <p class="mt-2 text-gray-600">Manage your admin account settings and profile information</p>
                </div>

                <div class="space-y-6">
                    <!-- Update Profile Information -->
                    <div class="bg-white/60 backdrop-blur-xl border border-white/60 shadow-xl rounded-2xl overflow-hidden">
                        <div class="p-8">
                            <div class="max-w-xl">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>
                    </div>

                    <!-- Update Password -->
                    <div class="bg-white/60 backdrop-blur-xl border border-white/60 shadow-xl rounded-2xl overflow-hidden">
                        <div class="p-8">
                            <div class="max-w-xl">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </div>

                    <!-- Delete Account -->
                    <div class="bg-white/60 backdrop-blur-xl border border-white/60 shadow-xl rounded-2xl overflow-hidden">
                        <div class="p-8">
                            <div class="max-w-xl">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
