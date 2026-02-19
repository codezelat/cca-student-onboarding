@extends('admin.layouts.app')

@section('title', 'Edit Payment | ' . config('app.name', 'CCA'))
@section('meta_description', 'Edit payment entry details in the Codezela Career Accelerator admin portal.')
@section('og_title', 'Edit Payment | ' . config('app.name', 'CCA'))
@section('og_description', 'Update payment ledger entry details for a registration.')
@section('twitter_title', 'Edit Payment | ' . config('app.name', 'CCA'))
@section('twitter_description', 'Update payment ledger entry details for a registration.')

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
                <a href="{{ route('admin.registrations.payments.index', $registration->id) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-primary-600 mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Payment Ledger
                </a>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                    Edit Payment #{{ $payment->payment_no }}
                </h1>
                <p class="text-gray-600 mt-1">{{ $registration->full_name }} ({{ $registration->register_id ?? 'cca-A' . str_pad($registration->id, 5, '0', STR_PAD_LEFT) }})</p>
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

            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 bg-white/60 backdrop-blur-xl border border-white/60 rounded-xl shadow-lg">
                    <p class="text-xs text-gray-500 uppercase">Full Amount</p>
                    <p class="text-xl font-bold text-green-600 mt-1">LKR {{ number_format($summary['full_amount'], 2) }}</p>
                </div>
                <div class="p-4 bg-white/60 backdrop-blur-xl border border-white/60 rounded-xl shadow-lg">
                    <p class="text-xs text-gray-500 uppercase">Total Paid</p>
                    <p class="text-xl font-bold text-indigo-600 mt-1">LKR {{ number_format($summary['paid_total'], 2) }}</p>
                </div>
                <div class="p-4 bg-white/60 backdrop-blur-xl border border-white/60 rounded-xl shadow-lg">
                    <p class="text-xs text-gray-500 uppercase">Balance</p>
                    <p class="text-xl font-bold {{ $summary['balance'] > 0 ? 'text-orange-600' : 'text-green-600' }} mt-1">
                        LKR {{ number_format(abs($summary['balance']), 2) }}
                    </p>
                </div>
            </div>

            <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg p-8">
                <form method="POST" action="{{ route('admin.registrations.payments.update', [$registration->id, $payment->id]) }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">Payment Date *</label>
                        <input id="payment_date" type="date" name="payment_date"
                               value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                               required>
                    </div>

                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount (LKR) *</label>
                        <input id="amount" type="number" name="amount" step="0.01"
                               value="{{ old('amount', number_format((float) $payment->amount, 2, '.', '')) }}"
                               class="w-full px-4 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                               required>
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                        <select id="payment_method" name="payment_method"
                                class="w-full px-4 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                required>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method }}" {{ old('payment_method', $payment->payment_method) === $method ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $method)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="receipt_reference" class="block text-sm font-medium text-gray-700 mb-2">Receipt Reference</label>
                        <input id="receipt_reference" type="text" name="receipt_reference"
                               value="{{ old('receipt_reference', $payment->receipt_reference) }}"
                               class="w-full px-4 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>

                    <div class="md:col-span-2">
                        <label for="note" class="block text-sm font-medium text-gray-700 mb-2">Note</label>
                        <textarea id="note" name="note" rows="3"
                                  class="w-full px-4 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('note', $payment->note) }}</textarea>
                    </div>

                    <div class="md:col-span-2 flex items-center justify-end space-x-4 pt-2">
                        <a href="{{ route('admin.registrations.payments.index', $registration->id) }}"
                           class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-xl transition-all duration-200">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-gradient-to-r from-primary-600 to-secondary-600 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-secondary-700 transition-all duration-200 shadow-lg">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
@endsection
