@extends('admin.layouts.app')

@section('title', 'Activity Timeline | ' . config('app.name', 'CCA'))
@section('meta_description', 'Review a complete audit trail of admin actions with advanced filters and export support.')

@section('body')
    <div class="fixed inset-0 bg-gradient-to-br from-violet-50 via-purple-50 to-indigo-50 overflow-hidden -z-10">
        <div class="absolute top-0 -left-4 w-96 h-96 bg-violet-200 rounded-full mix-blend-multiply filter blur-xl opacity-60 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-50 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-xl opacity-60 animate-blob animation-delay-4000"></div>
    </div>

    <div class="min-h-screen">
        @include('admin.partials.navigation')

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-6 flex items-center justify-between gap-3">
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-primary-600 mb-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Dashboard
                    </a>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">Activity Timeline</h1>
                    <p class="text-gray-600 mt-1">Complete audit log of admin actions with search, filters, and export.</p>
                </div>
                <a href="{{ route('admin.activity.export', request()->query()) }}"
                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-semibold transition-all duration-200 shadow-lg">
                    Export CSV
                </a>
            </div>

            <div class="mb-6 p-5 bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg">
                <form method="GET" action="{{ route('admin.activity.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">From Date</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}"
                               class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">To Date</label>
                        <input type="date" name="to_date" value="{{ request('to_date') }}"
                               class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Actor</label>
                        <select name="actor_user_id" class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">All</option>
                            @foreach($actors as $actor)
                                <option value="{{ $actor->id }}" {{ (string) request('actor_user_id') === (string) $actor->id ? 'selected' : '' }}>
                                    {{ $actor->name }} ({{ $actor->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Category</label>
                        <select name="category" class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">All</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>{{ ucfirst($category) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Action</label>
                        <select name="action" class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">All</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>{{ $action }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">All</option>
                            <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Subject Type</label>
                        <select name="subject_type" class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">All</option>
                            @foreach($subjectTypes as $type)
                                <option value="{{ $type }}" {{ request('subject_type') === $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="message, subject, route, request id"
                               class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>

                    <div class="md:col-span-2 lg:col-span-4 flex gap-2 justify-end">
                        <a href="{{ route('admin.activity.index') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-xl transition-all">Clear</a>
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-primary-600 to-secondary-600 text-white rounded-xl font-semibold transition-all shadow-lg hover:from-primary-700 hover:to-secondary-700">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white/40">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Time</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Actor</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Action</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Subject</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Request</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Details</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/20 divide-y divide-gray-200">
                            @forelse($activities as $activity)
                                <tr class="hover:bg-white/40 transition-colors">
                                    <td class="px-5 py-4 text-sm text-gray-700 whitespace-nowrap">
                                        {{ $activity->created_at?->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-800">
                                        <p class="font-semibold">{{ $activity->actor_name_snapshot ?: 'System' }}</p>
                                        <p class="text-xs text-gray-500">{{ $activity->actor_email_snapshot ?: 'N/A' }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-700">
                                        <p class="font-semibold">{{ $activity->action }}</p>
                                        <p class="text-xs text-gray-500">{{ ucfirst($activity->category) }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-700">
                                        <p>{{ $activity->subject_label ?: 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">{{ $activity->subject_type ?: 'N/A' }}{{ $activity->subject_id ? ' #' . $activity->subject_id : '' }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-sm">
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $activity->status === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-600">
                                        <p>{{ $activity->http_method ?: 'N/A' }} {{ $activity->route_name ?: '' }}</p>
                                        <p class="text-xs text-gray-500">{{ $activity->request_id ?: 'N/A' }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <a href="{{ route('admin.activity.show', array_merge(['id' => $activity->id], request()->query())) }}"
                                           class="inline-flex px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-xs font-semibold">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No activity records found for the selected filters.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($activities->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-white/40">
                        {{ $activities->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>
@endsection
