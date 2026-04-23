@extends('layouts.app')

@section('title', 'Projects - WorkTracker')

@section('content')
<div class="p-6 md:p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Projects</h1>
            <p class="text-gray-600 mt-2">Manage all your projects in one place</p>
        </div>
        <a href="{{ route('projects.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            New Project
        </a>
    </div>

    @if($projects->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($projects as $project)
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-bold text-gray-900">{{ $project->name }}</h3>
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($project->status === 'active') bg-green-100 text-green-800
                                @elseif($project->status === 'completed') bg-blue-100 text-blue-800
                                @elseif($project->status === 'on_hold') bg-orange-100 text-orange-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($project->status) }}
                            </span>
                        </div>

                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $project->description }}</p>

                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">Progress</span>
                                <span class="text-sm font-bold text-gray-900">{{ $project->progress_percentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition" style="width: <?php echo $project->progress_percentage ?>%"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4 py-4 border-t border-b border-gray-100">
                            <div>
                                <p class="text-xs text-gray-600 font-medium">Budget</p>
                                <p class="text-lg font-bold text-gray-900">${{ number_format($project->budget, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 font-medium">Paid</p>
                                <p class="text-lg font-bold text-green-600">${{ number_format($project->paid_amount, 2) }}</p>
                            </div>
                        </div>

                        @if($project->deadline)
                            <p class="text-xs text-gray-600 mb-4">
                                <strong>Deadline:</strong> {{ $project->deadline->format('M d, Y') }}
                            </p>
                        @endif

                        <div class="flex gap-2">
                            <a href="{{ route('projects.show', $project) }}" class="flex-1 bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition text-center text-sm font-medium">
                                View Details
                            </a>
                            <a href="{{ route('projects.edit', $project) }}" class="flex-1 bg-gray-200 text-gray-900 py-2 rounded-md hover:bg-gray-300 transition text-center text-sm font-medium">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $projects->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-gray-500 text-lg mb-4">No projects yet</p>
            <a href="{{ route('projects.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition inline-block">
                Create Your First Project
            </a>
        </div>
    @endif
</div>
@endsection
