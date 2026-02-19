@extends('admin.layouts.app')

@section('title', 'Admin Accounts | ' . config('app.name', 'CCA'))
@section('meta_description', 'Manage admin accounts, recover deactivated admins, and enforce account safety in the admin portal.')

@section('body')
    <div class="fixed inset-0 bg-gradient-to-br from-violet-50 via-purple-50 to-indigo-50 overflow-hidden -z-10">
        <div class="absolute top-0 -left-4 w-96 h-96 bg-violet-200 rounded-full mix-blend-multiply filter blur-xl opacity-60 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-50 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-xl opacity-60 animate-blob animation-delay-4000"></div>
    </div>

    <div class="min-h-screen">
        @include('admin.partials.navigation')

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-6">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-primary-600 mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Dashboard
                </a>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">Admin Accounts</h1>
                <p class="text-gray-600 mt-1">Create admins, deactivate accounts safely, and restore previously deactivated admins.</p>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="p-5 bg-white/60 backdrop-blur-xl border border-white/60 rounded-xl shadow-lg">
                    <p class="text-xs text-gray-500 uppercase">Active Admins</p>
                    <p class="text-2xl font-bold text-indigo-600 mt-1">{{ $activeAdminCount }}</p>
                </div>
                <div class="p-5 bg-white/60 backdrop-blur-xl border border-white/60 rounded-xl shadow-lg">
                    <p class="text-xs text-gray-500 uppercase">Deactivated Admins</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1">{{ $deletedAdmins->count() }}</p>
                </div>
                <div class="p-5 bg-white/60 backdrop-blur-xl border border-white/60 rounded-xl shadow-lg">
                    <p class="text-xs text-gray-500 uppercase">Safety Rule</p>
                    <p class="text-sm font-semibold text-gray-700 mt-2">At least one active admin is always required.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1 bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Create Admin</h2>
                    <form method="POST" action="{{ route('admin.accounts.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}"
                                   class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}"
                                   class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                            <input id="password" name="password" type="password"
                                   class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                   class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                        </div>

                        <button type="submit" class="w-full px-4 py-2.5 bg-gradient-to-r from-primary-600 to-secondary-600 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-secondary-700 transition-all duration-200 shadow-lg">
                            Create Admin
                        </button>
                    </form>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-white/40">
                            <h2 class="text-lg font-bold text-gray-800">Active Admin Accounts</h2>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-white/50">
                                    <tr>
                                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Name</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Email</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white/20 divide-y divide-gray-200">
                                    @forelse($activeAdmins as $admin)
                                        <tr>
                                            <td class="px-5 py-4 text-sm font-semibold text-gray-800">
                                                {{ $admin->name }}
                                                @if((int) $admin->id === (int) $currentAdminId)
                                                    <span class="ml-2 inline-flex px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold">You</span>
                                                @endif
                                            </td>
                                            <td class="px-5 py-4 text-sm text-gray-700">{{ $admin->email }}</td>
                                            <td class="px-5 py-4 text-sm">
                                                <span class="inline-flex px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Active</span>
                                            </td>
                                            <td class="px-5 py-4 text-center">
                                                <form method="POST" action="{{ route('admin.accounts.destroy', $admin->id) }}" class="inline"
                                                      onsubmit="return confirm('Deactivate this admin account?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-xs font-semibold">
                                                        Deactivate
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">No active admins found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-white/40">
                            <h2 class="text-lg font-bold text-gray-800">Deactivated Admin Accounts</h2>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-white/50">
                                    <tr>
                                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Name</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Email</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Deleted At</th>
                                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white/20 divide-y divide-gray-200">
                                    @forelse($deletedAdmins as $admin)
                                        <tr>
                                            <td class="px-5 py-4 text-sm font-semibold text-gray-800">{{ $admin->name }}</td>
                                            <td class="px-5 py-4 text-sm text-gray-700">{{ $admin->email }}</td>
                                            <td class="px-5 py-4 text-sm text-gray-700">{{ $admin->deleted_at?->format('Y-m-d H:i') }}</td>
                                            <td class="px-5 py-4 text-center">
                                                <form method="POST" action="{{ route('admin.accounts.restore', $admin->id) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-xs font-semibold">
                                                        Restore
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">No deactivated admins found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
