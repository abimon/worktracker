@extends('layouts.app')

@section('title', 'Add Feedback - WorkTracker')

@section('content')
<div class="p-6 md:p-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Add Feedback</h1>
        <p class="text-gray-600 mb-8">Share your feedback for <strong>{{ $project->name }}</strong></p>

        <form action="{{ route('projects.feedbacks.store', $project) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6 space-y-6">
            @csrf

            <div>
                <label for="message" class="block text-sm font-medium text-gray-900 mb-2">Your Feedback *</label>
                <textarea name="message" id="message" required rows="5"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Share your thoughts...">{{ old('message') }}</textarea>
                @error('message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-900 mb-2">Feedback Type</label>
                    <select name="type" id="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="general" {{ old('type') === 'general' ? 'selected' : '' }}>General</option>
                        <option value="task_specific" {{ old('type') === 'task_specific' ? 'selected' : '' }}>Task Specific</option>
                        <option value="payment" {{ old('type') === 'payment' ? 'selected' : '' }}>Payment Related</option>
                        <option value="bug_report" {{ old('type') === 'bug_report' ? 'selected' : '' }}>Bug Report</option>
                    </select>
                </div>

                <div>
                    <label for="rating" class="block text-sm font-medium text-gray-900 mb-2">Rating (1-5 stars)</label>
                    <select name="rating" id="rating" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- No Rating --</option>
                        <option value="1" {{ old('rating') === '1' ? 'selected' : '' }}>⭐ 1 Star</option>
                        <option value="2" {{ old('rating') === '2' ? 'selected' : '' }}>⭐⭐ 2 Stars</option>
                        <option value="3" {{ old('rating') === '3' ? 'selected' : '' }}>⭐⭐⭐ 3 Stars</option>
                        <option value="4" {{ old('rating') === '4' ? 'selected' : '' }}>⭐⭐⭐⭐ 4 Stars</option>
                        <option value="5" {{ old('rating') === '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5 Stars</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="attachment" class="block text-sm font-medium text-gray-900 mb-2">Attachment (Optional)</label>
                <input type="file" name="attachment" id="attachment"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-600 mt-2">Max file size: 5MB</p>
            </div>

            <div class="flex gap-4 pt-6">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-medium">
                    Submit Feedback
                </button>
                <a href="{{ route('projects.show', $project) }}" class="flex-1 bg-gray-200 text-gray-900 py-3 rounded-lg hover:bg-gray-300 transition font-medium text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
