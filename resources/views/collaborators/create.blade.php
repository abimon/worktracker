@extends('layouts.app')

@section('title', 'Invite Collaborator - WorkTracker')

@section('content')
<div class="p-6 md:p-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Invite Collaborator</h1>
        <p class="text-gray-600 mb-8">Add a team member to <strong>{{ $project->name }}</strong></p>

        <form action="{{ route('projects.collaborators.store', $project) }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
            @csrf

            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-900 mb-2">Select Developer *</label>
                <select name="user_id" id="user_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Choose a developer --</option>
                    @foreach($users as $developer)
                        <option value="{{ $developer->id }}" {{ old('user_id') == $developer->id ? 'selected' : '' }}>
                            {{ $developer->name }} ({{ $developer->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-900 mb-2">Role *</label>
                <select name="role" id="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Select Role --</option>
                    <option value="viewer" {{ old('role') === 'viewer' ? 'selected' : '' }}>Viewer (View only)</option>
                    <option value="contributor" {{ old('role') === 'contributor' || !old('role') ? 'selected' : '' }}>Contributor (Can modify)</option>
                    <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Manager (Full access)</option>
                </select>
                @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="font-semibold text-blue-900 mb-2">Role Permissions:</h3>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• <strong>Viewer:</strong> View tasks, payments, and project progress</li>
                    <li>• <strong>Contributor:</strong> Create and edit tasks, submit feedback</li>
                    <li>• <strong>Manager:</strong> Full project access including payment management and team control</li>
                </ul>
            </div>

            <div class="flex gap-4 pt-6">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-medium">
                    Send Invitation
                </button>
                <a href="{{ route('projects.show', $project) }}" class="flex-1 bg-gray-200 text-gray-900 py-3 rounded-lg hover:bg-gray-300 transition font-medium text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
