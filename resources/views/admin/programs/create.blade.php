@extends('admin.layouts.app')

@section('title', 'Create Program | ' . config('app.name', 'CCA'))
@section('meta_description', 'Create a new program and configure catalog details in the admin portal.')

@section('body')
    <div class="fixed inset-0 bg-gradient-to-br from-violet-50 via-purple-50 to-indigo-50 overflow-hidden -z-10">
        <div class="absolute top-0 -left-4 w-96 h-96 bg-violet-200 rounded-full mix-blend-multiply filter blur-xl opacity-60 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-50 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-xl opacity-60 animate-blob animation-delay-4000"></div>
    </div>

    <div class="min-h-screen">
        @include('admin.partials.navigation')

        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-6">
                <a href="{{ route('admin.programs.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-primary-600 mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Program Management
                </a>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                    Create Program
                </h1>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg p-8">
                <form method="POST" action="{{ route('admin.programs.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf

                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Program Code *</label>
                        <input id="code" name="code" type="text" value="{{ old('code') }}" placeholder="CCA-PM27"
                               class="w-full px-4 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent uppercase" required>
                        <p class="text-xs text-gray-500 mt-1">Format: 3 letters, dash, 2-4 letters + 2 digits (e.g. CCA-PM27)</p>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Program Name *</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}"
                               class="w-full px-4 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                    </div>

                    <div>
                        <label for="year_label" class="block text-sm font-medium text-gray-700 mb-2">Year *</label>
                        <input id="year_label" name="year_label" type="text" value="{{ old('year_label') }}" placeholder="2027"
                               class="w-full px-4 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                    </div>

                    <div>
                        <label for="duration_label" class="block text-sm font-medium text-gray-700 mb-2">Duration *</label>
                        <input id="duration_label" name="duration_label" type="text" value="{{ old('duration_label', '6 Months') }}"
                               class="w-full px-4 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                    </div>

                    <div>
                        <label for="base_price" class="block text-sm font-medium text-gray-700 mb-2">Base Price</label>
                        <input id="base_price" name="base_price" type="number" step="0.01" min="0" value="{{ old('base_price', '0') }}"
                               class="w-full px-4 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Currency *</label>
                        <input id="currency" name="currency" type="text" maxlength="3" value="{{ old('currency', 'LKR') }}"
                               class="w-full px-4 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent uppercase" required>
                    </div>

                    <div>
                        <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                        <input id="display_order" name="display_order" type="number" min="0" value="{{ old('display_order', 0) }}"
                               class="w-full px-4 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>

                    <div class="flex items-center gap-2 mt-8">
                        <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        <label for="is_active" class="text-sm font-medium text-gray-700">Program active</label>
                    </div>

                    <div class="md:col-span-2 flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('admin.programs.index') }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-xl transition-all">Cancel</a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-primary-600 to-secondary-600 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-secondary-700 transition-all duration-200 shadow-lg">
                            Create Program
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
@endsection
