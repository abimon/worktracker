@extends('layouts.app')

@section('title', 'Verification Status - WorkTracker')

@section('content')
<div class="p-6 md:p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Account Verification</h1>

        <!-- Verification Status -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Verification Status</h2>
                    @if($is_verified)
                        <p class="text-green-600 font-medium mt-2">✓ Your account is verified</p>
                    @else
                        <p class="text-orange-600 font-medium mt-2">⚠ Your account is pending verification</p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-gray-600 text-sm">Total Documents: <strong>{{ $total_documents }}</strong></p>
                    <p class="text-green-600 text-sm mt-1">Approved: <strong>{{ $approved_documents }}</strong></p>
                    <p class="text-orange-600 text-sm">Pending: <strong>{{ $pending_documents }}</strong></p>
                </div>
            </div>
        </div>

        <!-- Submit New Document -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Submit Document</h3>
            <form action="{{ route('verification.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label for="document_type" class="block text-sm font-medium text-gray-900 mb-2">Document Type *</label>
                    <select name="document_type" id="document_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select Type --</option>
                        <option value="id">Government ID</option>
                        <option value="passport">Passport</option>
                        <option value="certificate">Professional Certificate</option>
                        <option value="portfolio">Portfolio</option>
                        <option value="other">Other</option>
                    </select>
                    @error('document_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="document" class="block text-sm font-medium text-gray-900 mb-2">Upload Document *</label>
                    <input type="file" name="document" id="document" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-600 mt-2">Max file size: 5MB (PDF, JPG, PNG)</p>
                    @error('document') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                    Upload Document
                </button>
            </form>
        </div>

        <!-- Documents List -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">My Documents</h3>

            @if($documents->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 font-semibold text-gray-900">Type</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-900">Status</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-900">Submitted</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $doc)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 px-4 font-medium text-gray-900">{{ ucfirst($doc->document_type) }}</td>
                                    <td class="py-3 px-4">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($doc->status === 'approved') bg-green-100 text-green-800
                                            @elseif($doc->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($doc->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-600">{{ $doc->created_at->format('M d, Y') }}</td>
                                    <td class="py-3 px-4">
                                        <a href="{{ asset('storage/' . $doc->document_url) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @if($doc->status === 'rejected')
                                    <tr class="bg-red-50">
                                        <td colspan="4" class="py-3 px-4 text-sm text-red-700">
                                            <strong>Rejection Reason:</strong> {{ $doc->rejection_reason }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No documents submitted yet</p>
            @endif
        </div>
    </div>
</div>
@endsection
