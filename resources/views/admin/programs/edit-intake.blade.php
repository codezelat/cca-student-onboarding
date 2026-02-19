@extends('admin.layouts.app')

@section('title', 'Edit Intake Window | ' . config('app.name', 'CCA'))
@section('meta_description', 'Edit program intake window details in the admin portal.')

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
                <a href="{{ route('admin.programs.edit', $program->id) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-primary-600 mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Program
                </a>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                    Edit Intake Window
                </h1>
                <p class="text-gray-600 mt-1">{{ $program->name }} · {{ $intake->window_name }}</p>
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
                <form method="POST" action="{{ route('admin.programs.intakes.update', [$program->id, $intake->id]) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="window_name" class="block text-sm font-medium text-gray-700 mb-1">Window Name *</label>
                        <input id="window_name" name="window_name" type="text" value="{{ old('window_name', $intake->window_name) }}"
                               class="w-full px-4 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="opens_at" class="block text-sm font-medium text-gray-700 mb-1">Opens At *</label>
                            <input id="opens_at" name="opens_at" type="datetime-local"
                                   value="{{ old('opens_at', $intake->opens_at->format('Y-m-d\TH:i')) }}"
                                   class="w-full px-4 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                        </div>
                        <div>
                            <label for="closes_at" class="block text-sm font-medium text-gray-700 mb-1">Closes At *</label>
                            <input id="closes_at" name="closes_at" type="datetime-local"
                                   value="{{ old('closes_at', $intake->closes_at->format('Y-m-d\TH:i')) }}"
                                   class="w-full px-4 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                        </div>
                    </div>

                    <div>
                        <label for="price_override" class="block text-sm font-medium text-gray-700 mb-1">Price Override (optional)</label>
                        <input id="price_override" name="price_override" type="number" min="0" step="0.01"
                               value="{{ old('price_override', $intake->price_override) }}"
                               class="w-full px-4 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $intake->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        <span class="text-sm font-medium text-gray-700">Intake active</span>
                    </label>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('admin.programs.edit', $program->id) }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-xl transition-all">Cancel</a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-primary-600 to-secondary-600 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-secondary-700 transition-all duration-200 shadow-lg">
                            Save Intake
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
@endsection
