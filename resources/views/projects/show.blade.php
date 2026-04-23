@extends('layouts.app')

@section('title', $project->name . ' - WorkTracker')

@section('content')
<div class="p-6 md:p-8">
    <div class="mb-8">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $project->name }}</h1>
                <p class="text-gray-600 mt-2">{{ $project->description }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('projects.edit', $project) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Edit
                </a>
                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <!-- Status Badges -->
        <div class="flex gap-4 mb-6">
            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                @if($project->status === 'active') bg-green-100 text-green-800
                @elseif($project->status === 'completed') bg-blue-100 text-blue-800
                @elseif($project->status === 'on_hold') bg-orange-100 text-orange-800
                @else bg-gray-100 text-gray-800
                @endif">
                Status: {{ ucfirst($project->status) }}
            </span>
        </div>

        <!-- Progress Bar -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-semibold text-gray-900">Overall Progress</h3>
                <span class="text-2xl font-bold text-blue-600">{{ $project->progress_percentage }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-blue-600 h-3 rounded-full transition" style="width: <?php echo $project->progress_percentage ?>%"></div>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-600 text-sm font-medium">Total Budget</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">${{ number_format($project->budget, 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-600 text-sm font-medium">Amount Paid</p>
                <p class="text-2xl font-bold text-green-600 mt-2">${{ number_format($project->paid_amount, 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-600 text-sm font-medium">Remaining</p>
                <p class="text-2xl font-bold text-orange-600 mt-2">${{ number_format($project->budget - $project->paid_amount, 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-600 text-sm font-medium">Deadline</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ $project->deadline ? $project->deadline->format('M d') : 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div x-data="{ activeTab: 'tasks' }" class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200 px-6">
            <nav class="flex gap-8">
                <button @click="activeTab = 'tasks'" :class="activeTab === 'tasks' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900'" class="py-4 font-medium">
                    Tasks ({{ $project->tasks->count() }})
                </button>
                <button @click="activeTab = 'payments'" :class="activeTab === 'payments' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900'" class="py-4 font-medium">
                    Payments ({{ $project->payments->count() }})
                </button>
                <button @click="activeTab = 'collaborators'" :class="activeTab === 'collaborators' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900'" class="py-4 font-medium">
                    Collaborators
                </button>
                <button @click="activeTab = 'feedback'" :class="activeTab === 'feedback' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900'" class="py-4 font-medium">
                    Feedback ({{ $project->feedbacks->count() }})
                </button>
                <button @click="activeTab = 'share'" :class="activeTab === 'share' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900'" class="py-4 font-medium">
                    Share
                </button>
            </nav>
        </div>

        <div class="p-6">
            <!-- Tasks Tab -->
            <div x-show="activeTab === 'tasks'" class="space-y-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Project Tasks</h3>
                    <a href="{{ route('projects.tasks.create', $project) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        + Add Task
                    </a>
                </div>

                @if($project->tasks->count() > 0)
                    <div class="space-y-3">
                        @foreach($project->tasks()->orderBy('order')->get() as $task)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $task->title }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ $task->description }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('projects.tasks.edit', [$project, $task]) }}" class="text-blue-600 hover:text-blue-800 text-sm">Edit</a>
                                        <form action="{{ route('projects.tasks.destroy', [$project, $task]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this task?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                        </form>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 mt-3">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($task->status === 'todo') bg-gray-100 text-gray-800
                                        @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                        @elseif($task->status === 'review') bg-purple-100 text-purple-800
                                        @else bg-green-100 text-green-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($task->priority === 'urgent') bg-red-100 text-red-800
                                        @elseif($task->priority === 'high') bg-orange-100 text-orange-800
                                        @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800
                                        @endif">
                                        {{ ucfirst($task->priority) }} Priority
                                    </span>
                                    @if($task->due_date)
                                        <span class="text-xs text-gray-600">Due: {{ $task->due_date->format('M d, Y') }}</span>
                                    @endif
                                </div>

                                <div class="mt-3">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-medium text-gray-600">Completion</span>
                                        <span class="text-xs font-bold text-gray-900">{{ $task->completion_percentage }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full transition" style="width: <?php echo $task->completion_percentage ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">No tasks yet. Add your first task to get started!</p>
                @endif
            </div>

            <!-- Payments Tab -->
            <div x-show="activeTab === 'payments'" class="space-y-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Project Payments</h3>
                    <a href="{{ route('projects.payments.create', $project) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        + Record Payment
                    </a>
                </div>

                @if($project->payments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-2 px-4 font-semibold text-gray-900">Amount</th>
                                    <th class="text-left py-2 px-4 font-semibold text-gray-900">Method</th>
                                    <th class="text-left py-2 px-4 font-semibold text-gray-900">Status</th>
                                    <th class="text-left py-2 px-4 font-semibold text-gray-900">Date</th>
                                    <th class="text-left py-2 px-4 font-semibold text-gray-900">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($project->payments()->latest()->get() as $payment)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-3 px-4 font-semibold text-gray-900">${{ number_format($payment->amount, 2) }}</td>
                                        <td class="py-3 px-4 text-gray-600 text-sm">{{ ucfirst($payment->payment_method) }}</td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($payment->status === 'completed') bg-green-100 text-green-800
                                                @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($payment->status === 'failed') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-600">{{ $payment->paid_at?->format('M d, Y') ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 text-sm">
                                            <a href="{{ route('projects.payments.edit', [$project, $payment]) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                            <span class="text-gray-300">|</span>
                                            <form action="{{ route('projects.payments.destroy', [$project, $payment]) }}" method="POST" class="inline" onsubmit="return confirm('Delete?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">No payments recorded yet.</p>
                @endif
            </div>

            <!-- Collaborators Tab -->
            <div x-show="activeTab === 'collaborators'" class="space-y-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Team Members</h3>
                    <a href="{{ route('projects.collaborators.create', $project) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        + Invite Developer
                    </a>
                </div>

                <div class="space-y-3">
                    @foreach($project->collaborators()->where('status', 'accepted')->get() as $collaborator)
                        <div class="border border-gray-200 rounded-lg p-4 flex justify-between items-center">
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $collaborator->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $collaborator->email }}</p>
                                <span class="inline-block mt-2 px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst($collaborator->pivot->role) }}
                                </span>
                            </div>
                            <form action="{{ route('projects.collaborators.destroy', [$project, $collaborator->id]) }}" method="POST" onsubmit="return confirm('Remove this collaborator?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Feedback Tab -->
            <div x-show="activeTab === 'feedback'" class="space-y-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Feedback</h3>
                    <a href="{{ route('projects.feedbacks.create', $project) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        + Add Feedback
                    </a>
                </div>

                @if($project->feedbacks->count() > 0)
                    <div class="space-y-4">
                        @foreach($project->feedbacks()->latest()->get() as $feedback)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $feedback->user->name }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ $feedback->message }}</p>
                                        @if($feedback->rating)
                                            <div class="mt-2 flex items-center gap-1">
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
                @else
                    <p class="text-center text-gray-500 py-8">No feedback yet.</p>
                @endif
            </div>

            <!-- Share Tab -->
            <div x-show="activeTab === 'share'" class="space-y-4">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Share Project with Client</h3>
                <p class="text-gray-600 mb-4">Generate a shareable link that allows clients to view project progress, submit feedback, and track payments.</p>

                @if($project->share_token)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-gray-900 font-medium mb-2">Share Link:</p>
                        <div class="flex gap-2">
                            <input type="text" readonly value="{{ route('projects.shared', $project->share_token) }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" id="shareLink">
                            <button type="button" onclick="copyToClipboard('shareLink')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">
                                Copy Link
                            </button>
                        </div>
                    </div>
                @else
                    <form action="{{ route('projects.generate-share-token', $project) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                            Generate Share Link
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    document.execCommand('copy');
    alert('Link copied to clipboard!');
}
</script>
@endsection
