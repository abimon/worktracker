@extends('layouts.app')

@section('title', 'Dashboard - WorkTracker')

@section('content')
<div class="p-6 md:p-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600 mt-2">Here's an overview of your projects and activities.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Projects</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_projects'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Active Projects</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['active_projects'] }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Budget</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($stats['total_budget'], 2) }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Earned</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($stats['total_earned'], 2) }}</p>
                </div>
                <div class="bg-indigo-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Pending Payments -->
        <div class="lg:col-span-1 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Pending Payments</h3>
            <p class="text-3xl font-bold text-orange-600">${{ number_format($stats['pending_payments'], 2) }}</p>
            <p class="text-sm text-gray-600 mt-2">Awaiting payment confirmation</p>
        </div>

        <!-- Pending Collaborations -->
        <div class="lg:col-span-1 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Pending Collaborations</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['pending_collaborations'] }}</p>
            <a href="{{ url('/collaborations/pending') }}" class="text-blue-600 hover:text-blue-800 text-sm mt-2 inline-block">
                View Invitations →
            </a>
        </div>

        <!-- Completed Projects -->
        <div class="lg:col-span-1 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Completed Projects</h3>
            <p class="text-3xl font-bold text-green-600">{{ $stats['completed_projects'] }}</p>
            <p class="text-sm text-gray-600 mt-2">Successfully delivered projects</p>
        </div>
    </div>

    <!-- Recent Projects -->
    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Recent Projects</h2>
            <a href="{{ route('projects.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                + New Project
            </a>
        </div>

        @if($projects->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-semibold text-gray-900">Project Name</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-900">Status</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-900">Progress</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-900">Budget</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects->take(5) as $project)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 font-medium text-gray-900">{{ $project->name }}</td>
                        <td class="py-3 px-4">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                                        @if($project->status === 'active') bg-green-100 text-green-800
                                        @elseif($project->status === 'completed') bg-blue-100 text-blue-800
                                        @elseif($project->status === 'on_hold') bg-orange-100 text-orange-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                {{ ucfirst($project->status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center">
                                <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width:<?php echo $project->progress_percentage ?>%"></div>
                                </div>
                                <span class="text-sm text-gray-600">{{ $project->progress_percentage }}%</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-900 font-medium">${{ number_format($project->budget, 2) }}</td>
                        <td class="py-3 px-4">
                            <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-gray-500 mb-4">No projects yet</p>
            <a href="{{ route('projects.create') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                Create your first project
            </a>
        </div>
        @endif
    </div>
</div>
@endsection