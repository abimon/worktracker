@extends('layouts.app')

@section('title', 'Collaborators - WorkTracker')

@section('content')
<div class="p-6 md:p-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Collaborators</h1>
        <a href="{{ route('collaborations.pending') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            Pending Invitations
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($collaborators as $collaborator)
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">{{ $collaborator->name }}</h3>
                    <p class="text-gray-600 text-sm">{{ $collaborator->email }}</p>
                    @if($collaborator->phone)
                    <p class="text-gray-600 text-sm">{{ $collaborator->phone }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                            @if($collaborator->account_type === 'developer') bg-blue-100 text-blue-800
                            @else bg-green-100 text-green-800
                            @endif">
                        {{ ucfirst($collaborator->account_type) }}
                    </span>
                </div>
            </div>

            @if($collaborator->bio)
            <p class="text-gray-600 text-sm mt-3 line-clamp-2">{{ $collaborator->bio }}</p>
            @endif

            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="text-sm text-gray-600 mb-3">
                    <strong>Role:</strong>
                    <span class="inline-block ml-2 px-2 py-1 rounded bg-gray-100">
                        {{ ucfirst($collaborator->pivot->role) }}
                    </span>
                </div>

                @if($collaborator->is_verified)
                <div class="text-sm text-green-700 font-medium">
                    ✓ Verified Member
                </div>
                @else
                <div class="text-sm text-gray-600">
                    Verification pending
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @if($collaborators->isEmpty())
    <div class="bg-white rounded-lg shadow p-12 text-center">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        <p class="text-gray-500 text-lg mb-4">No collaborators yet</p>
        <a href="{{ route('projects.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
            Back to Projects
        </a>
    </div>
    @endif
</div>
@endsection