@extends('admin.layouts.app')

@section('title', 'Program Management | ' . config('app.name', 'CCA'))
@section('meta_description', 'Manage program availability, intake windows, and pricing in the admin portal.')

@section('body')
    <div class="fixed inset-0 bg-gradient-to-br from-violet-50 via-purple-50 to-indigo-50 overflow-hidden -z-10">
        <div class="absolute top-0 -left-4 w-96 h-96 bg-violet-200 rounded-full mix-blend-multiply filter blur-xl opacity-60 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-50 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-xl opacity-60 animate-blob animation-delay-4000"></div>
    </div>

    <div class="min-h-screen">
        @include('admin.partials.navigation')

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-6 flex items-center justify-between gap-4">
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-primary-600 mb-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Dashboard
                    </a>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                        Program Management
                    </h1>
                    <p class="text-gray-600 mt-1">Control program activation, intake windows, and pricing from the admin area.</p>
                </div>
                <a href="{{ route('admin.programs.create') }}"
                   class="px-4 py-2 bg-gradient-to-r from-primary-600 to-secondary-600 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-secondary-700 transition-all duration-200 shadow-lg">
                    Add Program
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                @forelse($programs as $program)
                    @php
                        $currentWindow = $program->intakeWindows->first(function ($window) use ($now) {
                            return $window->is_active
                                && $window->opens_at <= $now
                                && $window->closes_at >= $now;
                        });
                        $openNow = $program->is_active && $currentWindow !== null;
                    @endphp

                    <div class="p-5 bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase">{{ $program->code }}</p>
                                <h2 class="text-lg font-bold text-gray-900 mt-1">{{ $program->name }}</h2>
                                <p class="text-sm text-gray-600 mt-1">{{ $program->year_label }} · {{ $program->duration_label }}</p>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $program->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $program->is_active ? 'Program Active' : 'Program Inactive' }}
                                </span>
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $openNow ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700' }}">
                                    {{ $openNow ? 'Open Now' : 'Closed Now' }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                            <div class="p-3 bg-white/70 border border-gray-200 rounded-xl">
                                <p class="text-xs text-gray-500">Base Price</p>
                                <p class="font-semibold text-gray-800">{{ $program->currency }} {{ number_format((float) $program->base_price, 2) }}</p>
                            </div>
                            <div class="p-3 bg-white/70 border border-gray-200 rounded-xl">
                                <p class="text-xs text-gray-500">Intake Windows</p>
                                <p class="font-semibold text-gray-800">{{ $program->intakeWindows->count() }}</p>
                            </div>
                        </div>

                        @if($currentWindow)
                            <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-800">
                                <p class="font-semibold">Current Intake: {{ $currentWindow->window_name }}</p>
                                <p class="text-xs mt-1">{{ $currentWindow->opens_at->format('Y-m-d H:i') }} to {{ $currentWindow->closes_at->format('Y-m-d H:i') }}</p>
                            </div>
                        @endif

                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('admin.programs.edit', $program->id) }}"
                               class="px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors text-xs font-semibold">
                                Edit / Intakes
                            </a>

                            <form method="POST" action="{{ route('admin.programs.toggle', $program->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="px-3 py-1.5 {{ $program->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded-lg transition-colors text-xs font-semibold">
                                    {{ $program->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 p-8 bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg text-center">
                        <p class="text-lg font-semibold text-gray-700">No programs found</p>
                        <p class="text-sm text-gray-500 mt-1">Create your first program to start managing intakes and pricing.</p>
                    </div>
                @endforelse
            </div>
        </main>
    </div>
@endsection
