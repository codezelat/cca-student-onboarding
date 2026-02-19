@extends('admin.layouts.app')

@section('title', 'Payment Ledger | ' . config('app.name', 'CCA'))
@section('meta_description', 'Track and manage registration payment history inside the Codezela Career Accelerator admin portal.')
@section('og_title', 'Payment Ledger | ' . config('app.name', 'CCA'))
@section('og_description', 'Manage payment installments, references, and balances for student registrations.')
@section('twitter_title', 'Payment Ledger | ' . config('app.name', 'CCA'))
@section('twitter_description', 'Manage payment installments, references, and balances for student registrations.')

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
                    <a href="{{ route('admin.registrations.show', $registration->id) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-primary-600 mb-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Registration
                    </a>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                        Payment Ledger
                    </h1>
                    <p class="text-gray-600 mt-1">
                        {{ $registration->full_name }} ({{ $registration->register_id ?? 'cca-A' . str_pad($registration->id, 5, '0', STR_PAD_LEFT) }})
                    </p>
                </div>
                <a href="{{ route('admin.registrations.edit', $registration->id) }}"
                   class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-xl transition-all duration-200 flex items-center whitespace-nowrap">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Registration
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                    <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
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

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="p-5 bg-white/60 backdrop-blur-xl border border-white/60 rounded-xl shadow-lg">
                    <p class="text-xs text-gray-500 uppercase">Full Amount</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">LKR {{ number_format($summary['full_amount'], 2) }}</p>
                </div>
                <div class="p-5 bg-white/60 backdrop-blur-xl border border-white/60 rounded-xl shadow-lg">
                    <p class="text-xs text-gray-500 uppercase">Total Paid</p>
                    <p class="text-2xl font-bold text-indigo-600 mt-1">LKR {{ number_format($summary['paid_total'], 2) }}</p>
                </div>
                <div class="p-5 bg-white/60 backdrop-blur-xl border border-white/60 rounded-xl shadow-lg">
                    <p class="text-xs text-gray-500 uppercase">Balance</p>
                    <p class="text-2xl font-bold {{ $summary['balance'] > 0 ? 'text-orange-600' : 'text-green-600' }} mt-1">
                        LKR {{ number_format(abs($summary['balance']), 2) }}
                    </p>
                </div>
                <div class="p-5 bg-white/60 backdrop-blur-xl border border-white/60 rounded-xl shadow-lg">
                    <p class="text-xs text-gray-500 uppercase">Status</p>
                    @php
                        $statusStyles = [
                            'unpaid' => 'bg-red-100 text-red-700',
                            'partial' => 'bg-yellow-100 text-yellow-700',
                            'paid' => 'bg-green-100 text-green-700',
                            'overpaid' => 'bg-purple-100 text-purple-700',
                        ];
                        $statusStyle = $statusStyles[$summary['status']] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <span class="inline-flex mt-2 px-3 py-1 rounded-full text-sm font-semibold {{ $statusStyle }}">
                        {{ ucfirst($summary['status']) }}
                    </span>
                </div>
            </div>

            <div class="mb-6 bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Add Payment</h2>
                <form method="POST" action="{{ route('admin.registrations.payments.store', $registration->id) }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @csrf

                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">Payment Date *</label>
                        <input id="payment_date" type="date" name="payment_date" value="{{ old('payment_date', now()->toDateString()) }}"
                               class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                    </div>

                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (LKR) *</label>
                        <input id="amount" type="number" name="amount" step="0.01" value="{{ old('amount') }}"
                               placeholder="e.g. 25000.00"
                               class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method *</label>
                        <select id="payment_method" name="payment_method"
                                class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                            <option value="">Select method</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method }}" {{ old('payment_method') === $method ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $method)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="receipt_reference" class="block text-sm font-medium text-gray-700 mb-1">Receipt Reference</label>
                        <input id="receipt_reference" type="text" name="receipt_reference" value="{{ old('receipt_reference') }}"
                               placeholder="Transaction / Bank Ref"
                               class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>

                    <div class="md:col-span-2">
                        <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                        <input id="note" type="text" name="note" value="{{ old('note') }}"
                               placeholder="Optional internal note"
                               class="w-full px-3 py-2 bg-white/70 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                                class="w-full px-4 py-2.5 bg-gradient-to-r from-primary-600 to-secondary-600 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-secondary-700 transition-all duration-200 shadow-lg">
                            Add Payment
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-xl overflow-hidden">
                <div class="px-6 py-4 bg-white/40 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800">Payment Entries</h2>
                    <span class="text-sm text-gray-600">{{ $payments->count() }} entries</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white/50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">#</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Amount</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Method</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Reference</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Note</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/20 divide-y divide-gray-200">
                            @forelse($payments as $payment)
                                @php
                                    $isVoid = $payment->status === \App\Models\RegistrationPayment::STATUS_VOID;
                                @endphp
                                <tr class="{{ $isVoid ? 'opacity-70' : '' }}">
                                    <td class="px-5 py-4 text-sm font-semibold text-gray-800">#{{ $payment->payment_no }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-700">{{ $payment->payment_date->format('Y-m-d') }}</td>
                                    <td class="px-5 py-4 text-sm font-semibold {{ (float) $payment->amount >= 0 ? 'text-green-700' : 'text-red-700' }}">
                                        LKR {{ number_format((float) $payment->amount, 2) }}
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-700">{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-700">{{ $payment->receipt_reference ?: 'N/A' }}</td>
                                    <td class="px-5 py-4 text-sm">
                                        @if($isVoid)
                                            <span class="inline-flex px-2.5 py-1 rounded-full bg-red-100 text-red-700 font-semibold text-xs">Void</span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 rounded-full bg-green-100 text-green-700 font-semibold text-xs">Active</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-700 max-w-xs truncate" title="{{ $payment->note ?: '' }}">
                                        {{ $payment->note ?: 'N/A' }}
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <div class="inline-flex items-center gap-2">
                                            @if(!$isVoid)
                                                <a href="{{ route('admin.registrations.payments.edit', [$registration->id, $payment->id]) }}"
                                                   class="px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors text-xs font-semibold">
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('admin.registrations.payments.void', [$registration->id, $payment->id]) }}"
                                                      onsubmit="const reason = prompt('Enter reason for voiding this payment:'); if (!reason) return false; this.querySelector('input[name=void_reason]').value = reason; return confirm('Void this payment entry?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="void_reason" value="">
                                                    <button type="submit"
                                                            class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-xs font-semibold">
                                                        Void
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-500">{{ $payment->voided_at ? 'Voided on ' . $payment->voided_at->format('Y-m-d') : 'Voided' }}</span>
                                            @endif
                                        </div>
                                        @if($isVoid && $payment->void_reason)
                                            <p class="text-xs text-red-600 mt-1" title="{{ $payment->void_reason }}">Reason: {{ $payment->void_reason }}</p>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <p class="text-gray-500 text-lg font-medium">No payment entries yet</p>
                                        <p class="text-gray-400 text-sm mt-1">Add the first payment from the form above.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
@endsection
