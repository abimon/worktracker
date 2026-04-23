@extends('layouts.app')

@section('title', 'Pending Collaborations - WorkTracker')

@section('content')
<div class="p-6 md:p-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Pending Collaboration Invitations</h1>

    @if($invitations->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($invitations as $invitation)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $invitation->project->name }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ $invitation->project->description }}</p>

                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-600 mb-1">Role: <strong>{{ ucfirst($invitation->role) }}</strong></p>
                        <p class="text-sm text-gray-600">Invited: {{ $invitation->created_at->diffForHumans() }}</p>
                    </div>

                    <div class="flex gap-2">
                        <form action="{{ route('collaborations.accept', $invitation->invite_token) }}" method="GET" class="flex-1">
                            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition font-medium text-sm">
                                Accept Invitation
                            </button>
                        </form>
                        <form action="{{ route('collaborations.decline', $invitation->invite_token) }}" method="GET" class="flex-1">
                            <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition font-medium text-sm">
                                Decline
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $invitations->links() }}
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <p class="text-gray-500 text-lg mb-4">No pending invitations</p>
            <a href="{{ route('projects.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                Back to Projects
            </a>
        </div>
    @endif
</div>
@endsection
