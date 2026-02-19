@extends('admin.layouts.app')

@section('title', 'Activity Detail | ' . config('app.name', 'CCA'))
@section('meta_description', 'Review full details of a specific admin activity log entry.')

@section('body')
    <div class="fixed inset-0 bg-gradient-to-br from-violet-50 via-purple-50 to-indigo-50 overflow-hidden -z-10">
        <div class="absolute top-0 -left-4 w-96 h-96 bg-violet-200 rounded-full mix-blend-multiply filter blur-xl opacity-60 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-50 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-xl opacity-60 animate-blob animation-delay-4000"></div>
    </div>

    <div class="min-h-screen">
        @include('admin.partials.navigation')

        <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-6">
                <a href="{{ route('admin.activity.index', request()->query()) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-primary-600 mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Activity Timeline
                </a>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">Activity Detail</h1>
            </div>

            <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Action</p>
                        <p class="font-semibold text-gray-800">{{ $activity->action }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Category</p>
                        <p class="font-semibold text-gray-800">{{ ucfirst($activity->category) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Status</p>
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $activity->status === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($activity->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Actor</p>
                        <p class="font-semibold text-gray-800">{{ $activity->actor_name_snapshot ?: 'System' }}</p>
                        <p class="text-xs text-gray-500">{{ $activity->actor_email_snapshot ?: 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Subject</p>
                        <p class="font-semibold text-gray-800">{{ $activity->subject_label ?: 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $activity->subject_type ?: 'N/A' }}{{ $activity->subject_id ? ' #' . $activity->subject_id : '' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Date Time</p>
                        <p class="font-semibold text-gray-800">{{ $activity->created_at?->format('Y-m-d H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Route</p>
                        <p class="font-semibold text-gray-800">{{ $activity->route_name ?: 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $activity->http_method ?: 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Request ID</p>
                        <p class="font-mono text-xs text-gray-700 break-all">{{ $activity->request_id ?: 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">IP / User Agent</p>
                        <p class="text-gray-800">{{ $activity->ip_address ?: 'N/A' }}</p>
                        <p class="text-xs text-gray-500 break-all">{{ $activity->user_agent ?: 'N/A' }}</p>
                    </div>
                </div>

                @if($activity->message)
                    <div class="mt-5 p-4 bg-white/70 border border-gray-200 rounded-xl">
                        <p class="text-xs text-gray-500 uppercase mb-1">Message</p>
                        <p class="text-sm text-gray-800">{{ $activity->message }}</p>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-3">Before Data</h2>
                    @if($activity->before_data)
                        <pre class="text-xs bg-gray-900 text-gray-100 p-4 rounded-xl overflow-auto">{{ json_encode($activity->before_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    @else
                        <p class="text-sm text-gray-500">No before snapshot recorded.</p>
                    @endif
                </div>

                <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-3">After Data</h2>
                    @if($activity->after_data)
                        <pre class="text-xs bg-gray-900 text-gray-100 p-4 rounded-xl overflow-auto">{{ json_encode($activity->after_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    @else
                        <p class="text-sm text-gray-500">No after snapshot recorded.</p>
                    @endif
                </div>
            </div>

            <div class="mt-6 bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-3">Metadata</h2>
                @if($activity->meta)
                    <pre class="text-xs bg-gray-900 text-gray-100 p-4 rounded-xl overflow-auto">{{ json_encode($activity->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                @else
                    <p class="text-sm text-gray-500">No extra metadata recorded.</p>
                @endif
            </div>
        </main>
    </div>
@endsection
