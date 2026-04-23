@extends('layouts.app')

@section('title', 'Profile - WorkTracker')

@section('content')
<div class="p-6 md:p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">My Profile</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Profile Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full mx-auto mb-4 object-cover">
                    @else
                        <div class="w-24 h-24 rounded-full mx-auto mb-4 bg-blue-100 flex items-center justify-center">
                            <span class="text-3xl text-blue-600">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    <p class="text-sm text-gray-500 mt-2">Account Type: <strong>{{ ucfirst($user->account_type) }}</strong></p>

                    @if($user->is_verified)
                        <div class="mt-4 inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                            ✓ Verified
                        </div>
                    @else
                        <div class="mt-4 inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                            Verification Pending
                        </div>
                    @endif
                </div>
            </div>

            <!-- Stats -->
            <div class="col-span-2 grid grid-cols-2 gap-4">
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-gray-600 text-sm">Total Projects</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $user->projects->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-gray-600 text-sm">Wallet Balance</p>
                    <p class="text-3xl font-bold text-green-600">${{ number_format($user->wallet_balance, 2) }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 col-span-2">
                    <p class="text-gray-600 text-sm">Member Since</p>
                    <p class="text-lg font-bold text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Profile Info & Editing Tabs -->
        <div x-data="{ activeTab: 'info' }" class="bg-white rounded-lg shadow">
            <div class="border-b border-gray-200 px-6">
                <nav class="flex gap-8">
                    <button @click="activeTab = 'info'" :class="activeTab === 'info' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900'" class="py-4 font-medium">
                        Profile Information
                    </button>
                    <button @click="activeTab = 'settings'" :class="activeTab === 'settings' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900'" class="py-4 font-medium">
                        Settings
                    </button>
                </nav>
            </div>

            <div class="p-6">
                <!-- Profile Info Tab -->
                <div x-show="activeTab === 'info'" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Full Name</label>
                            <p class="text-gray-700">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Email</label>
                            <p class="text-gray-700">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Phone</label>
                            <p class="text-gray-700">{{ $user->phone ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Account Type</label>
                            <p class="text-gray-700">{{ ucfirst($user->account_type) }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Bio</label>
                        <p class="text-gray-700">{{ $user->bio ?? 'No bio provided' }}</p>
                    </div>
                </div>

                <!-- Settings Tab -->
                <div x-show="activeTab === 'settings'" class="space-y-6">
                    <!-- Change Password -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h3>
                        <form action="{{ route('profile.change-password') }}" method="POST" class="space-y-4 max-w-md">
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-900 mb-2">Current Password</label>
                                <input type="password" name="current_password" id="current_password" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-900 mb-2">New Password</label>
                                <input type="password" name="password" id="password" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-900 mb-2">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                                Update Password
                            </button>
                        </form>
                    </div>

                    <!-- Verification -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Verification</h3>
                        <p class="text-gray-600 mb-4">Submit documents to get verified and build trust with clients.</p>
                        <a href="{{ route('verification.status') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium inline-block">
                            Manage Verification
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
