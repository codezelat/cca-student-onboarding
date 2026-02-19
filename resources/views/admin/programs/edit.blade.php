@extends('admin.layouts.app')

@section('title', 'Edit Program | ' . config('app.name', 'CCA'))
@section('meta_description', 'Edit program details, pricing, and intake windows in the admin portal.')

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
                    <a href="{{ route('admin.programs.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-primary-600 mb-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Program Management
                    </a>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                        {{ $program->name }}
                    </h1>
                    <p class="text-gray-600 mt-1">Program Code: {{ $program->code }}</p>
                </div>
                <form method="POST" action="{{ route('admin.programs.toggle', $program->id) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="px-4 py-2 {{ $program->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-xl transition-all duration-200">
                        {{ $program->is_active ? 'Deactivate Program' : 'Activate Program' }}
                    </button>
                </form>
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

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Program Details</h2>
                    <form method="POST" action="{{ route('admin.programs.update', $program->id) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Program Code</label>
                            <input type="text" value="{{ $program->code }}" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-xl text-gray-700" readonly>
                            <p class="text-xs text-gray-500 mt-1">Code is immutable once created.</p>
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Program Name *</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $program->name) }}"
                                   class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="year_label" class="block text-sm font-medium text-gray-700 mb-1">Year *</label>
                                <input id="year_label" name="year_label" type="text" value="{{ old('year_label', $program->year_label) }}"
                                       class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                            </div>
                            <div>
                                <label for="duration_label" class="block text-sm font-medium text-gray-700 mb-1">Duration *</label>
                                <input id="duration_label" name="duration_label" type="text" value="{{ old('duration_label', $program->duration_label) }}"
                                       class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="base_price" class="block text-sm font-medium text-gray-700 mb-1">Base Price</label>
                                <input id="base_price" name="base_price" type="number" step="0.01" min="0" value="{{ old('base_price', $program->base_price) }}"
                                       class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">Currency *</label>
                                <input id="currency" name="currency" type="text" maxlength="3" value="{{ old('currency', $program->currency) }}"
                                       class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent uppercase" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 items-center">
                            <div>
                                <label for="display_order" class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                                <input id="display_order" name="display_order" type="number" min="0" value="{{ old('display_order', $program->display_order) }}"
                                       class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            </div>
                            <div class="pt-5">
                                <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $program->is_active) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    <span class="text-sm font-medium text-gray-700">Program active</span>
                                </label>
                            </div>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="px-5 py-2 bg-gradient-to-r from-primary-600 to-secondary-600 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-secondary-700 transition-all duration-200 shadow-lg">
                                Save Program
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Add Intake Window</h2>
                    <form method="POST" action="{{ route('admin.programs.intakes.store', $program->id) }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="window_name" class="block text-sm font-medium text-gray-700 mb-1">Window Name *</label>
                            <input id="window_name" name="window_name" type="text" value="{{ old('window_name') }}" placeholder="April 2026 Intake"
                                   class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="opens_at" class="block text-sm font-medium text-gray-700 mb-1">Opens At *</label>
                                <input id="opens_at" name="opens_at" type="datetime-local" value="{{ old('opens_at') }}"
                                       class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                            </div>
                            <div>
                                <label for="closes_at" class="block text-sm font-medium text-gray-700 mb-1">Closes At *</label>
                                <input id="closes_at" name="closes_at" type="datetime-local" value="{{ old('closes_at') }}"
                                       class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                            </div>
                        </div>

                        <div>
                            <label for="price_override" class="block text-sm font-medium text-gray-700 mb-1">Price Override (optional)</label>
                            <input id="price_override" name="price_override" type="number" min="0" step="0.01" value="{{ old('price_override') }}"
                                   class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>

                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm font-medium text-gray-700">Intake active</span>
                        </label>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md">
                                Add Intake
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-6 bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-white/40">
                    <h2 class="text-lg font-bold text-gray-800">Intake Windows</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white/50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Name</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Open</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Close</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Price Override</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/20 divide-y divide-gray-200">
                            @forelse($program->intakeWindows->sortByDesc('opens_at') as $intake)
                                @php
                                    $isCurrent = $intake->is_active && $intake->opens_at <= $now && $intake->closes_at >= $now;
                                @endphp
                                <tr>
                                    <td class="px-5 py-4 text-sm text-gray-800 font-semibold">{{ $intake->window_name }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-700">{{ $intake->opens_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-700">{{ $intake->closes_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-700">
                                        {{ $intake->price_override !== null ? number_format((float) $intake->price_override, 2) : 'N/A' }}
                                    </td>
                                    <td class="px-5 py-4 text-sm">
                                        @if($isCurrent)
                                            <span class="inline-flex px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">Current</span>
                                        @elseif($intake->is_active)
                                            <span class="inline-flex px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Active</span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('admin.programs.intakes.edit', [$program->id, $intake->id]) }}"
                                               class="px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors text-xs font-semibold">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.programs.intakes.toggle', [$program->id, $intake->id]) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="px-3 py-1.5 {{ $intake->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded-lg transition-colors text-xs font-semibold">
                                                    {{ $intake->is_active ? 'Disable' : 'Enable' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">No intake windows yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
@endsection
