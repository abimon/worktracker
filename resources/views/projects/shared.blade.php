@extends('layouts.app')

@section('title', 'Shared Project - ' . $project->name)

@section('content')
<div class="p-6 md:p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow p-8 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-gray-900">{{ $project->name }}</h1>
                <span class="px-4 py-2 rounded-full
                    @if($project->status === 'active') bg-green-100 text-green-800
                    @elseif($project->status === 'completed') bg-blue-100 text-blue-800
                    @else bg-gray-100 text-gray-800
                    @endif font-medium">
                    {{ ucfirst($project->status) }}
                </span>
            </div>

            <p class="text-gray-600 mb-6">{{ $project->description }}</p>

            <!-- Progress Bar -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="font-medium text-gray-900">Overall Progress</span>
                    <span class="text-2xl font-bold text-blue-600">{{ $project->progress_percentage }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-blue-600 h-3 rounded-full" style="width: <?php echo $project->progress_percentage ?>%"></div>
                </div>
            </div>

            <!-- Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Total Budget</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">${{ number_format($project->budget, 2) }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Amount Paid</p>
                    <p class="text-xl font-bold text-green-600 mt-1">${{ number_format($project->paid_amount, 2) }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Remaining</p>
                    <p class="text-xl font-bold text-orange-600 mt-1">${{ number_format($project->budget - $project->paid_amount, 2) }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Deadline</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">{{ $project->deadline?->format('M d, Y') ?? 'N/A' }}</p>
                </div>
            </div>

            @if($project->preview_url)
                <a href="{{ $project->preview_url }}" target="_blank" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    View Live Project →
                </a>
            @endif
        </div>

        <!-- Tasks -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Tasks</h2>
            @if($project->tasks->count() > 0)
                <div class="space-y-3">
                    @foreach($project->tasks as $task)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-semibold text-gray-900">{{ $task->title }}</h3>
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($task->status === 'completed') bg-green-100 text-green-800
                                    @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ str_replace('_', ' ', ucfirst($task->status)) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">{{ $task->description }}</p>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: <?php echo $task->completion_percentage ?>%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No tasks yet</p>
            @endif
        </div>

        <!-- Feedback Form -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Share Your Feedback</h2>
            <form action="{{ route('projects.feedbacks.store', $project) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-900 mb-2">Your Feedback *</label>
                    <textarea name="message" id="message" required rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Share your thoughts about this project..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-900 mb-2">Feedback Type</label>
                        <select name="type" id="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="general">General</option>
                            <option value="task_specific">Task Specific</option>
                            <option value="payment">Payment Related</option>
                            <option value="bug_report">Bug Report</option>
                        </select>
                    </div>

                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-900 mb-2">Rating (1-5 stars)</label>
                        <select name="rating" id="rating" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Select Rating --</option>
                            <option value="1">1 Star</option>
                            <option value="2">2 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="5">5 Stars</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="attachment" class="block text-sm font-medium text-gray-900 mb-2">Attach File (Optional)</label>
                    <input type="file" name="attachment" id="attachment" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 font-medium">
                    Submit Feedback
                </button>
            </form>
        </div>

        <!-- Feedback List -->
        @if($project->feedbacks->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Previous Feedback</h2>
                <div class="space-y-4">
                    @foreach($project->feedbacks as $feedback)
                        <div class="border-b border-gray-200 pb-4 last:border-b-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $feedback->user->name }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $feedback->message }}</p>
                                    @if($feedback->rating)
                                        <div class="mt-2 flex gap-1">
                                            @for($i = 0; $i < $feedback->rating; $i++)
                                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.381-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            @endfor
                                        </div>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-500">{{ $feedback->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
