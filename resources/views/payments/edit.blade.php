@extends('layouts.app')

@section('title', 'Edit Payment - WorkTracker')

@section('content')
<div class="p-6 md:p-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Payment</h1>
        <p class="text-gray-600 mb-8">Update payment record for <strong>{{ $project->name }}</strong></p>

        <form action="{{ route('projects.payments.update', [$project, $payment]) }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-900 mb-2">Amount (USD) *</label>
                    <input type="number" name="amount" id="amount" required step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('amount', $payment->amount) }}">
                    @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-900 mb-2">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="mpesa" {{ old('payment_method', $payment->payment_method) === 'mpesa' ? 'selected' : '' }}>M-Pesa</option>
                        <option value="card" {{ old('payment_method', $payment->payment_method) === 'card' ? 'selected' : '' }}>Credit Card</option>
                        <option value="bank_transfer" {{ old('payment_method', $payment->payment_method) === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="manual" {{ old('payment_method', $payment->payment_method) === 'manual' ? 'selected' : '' }}>Manual</option>
                        <option value="other" {{ old('payment_method', $payment->payment_method) === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-900 mb-2">Status</label>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pending" {{ old('status', $payment->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ old('status', $payment->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed" {{ old('status', $payment->status) === 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ old('status', $payment->status) === 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>

                <div>
                    <label for="reference_number" class="block text-sm font-medium text-gray-900 mb-2">Reference Number</label>
                    <input type="text" name="reference_number" id="reference_number"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('reference_number', $payment->reference_number) }}">
                </div>
            </div>

            <div>
                <label for="paid_at" class="block text-sm font-medium text-gray-900 mb-2">Payment Date</label>
                <input type="date" name="paid_at" id="paid_at"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('paid_at', $payment->paid_at) }}">
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-gray-900 mb-2">Notes</label>
                <textarea name="notes" id="notes" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes', $payment->notes) }}</textarea>
            </div>

            <div class="flex gap-4 pt-6">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-medium">
                    Update Payment
                </button>
                <a href="{{ route('projects.show', $project) }}" class="flex-1 bg-gray-200 text-gray-900 py-3 rounded-lg hover:bg-gray-300 transition font-medium text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
