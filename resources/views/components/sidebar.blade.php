@auth
<aside class="w-64 bg-gray-900 text-white h-screen overflow-y-auto hidden md:block">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-6">Menu</h3>
        <nav class="space-y-2">
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-md hover:bg-gray-800 {{ request()->routeIs('dashboard') ? 'bg-blue-600' : '' }}">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9M9 21h6"></path>
                </svg>
                Dashboard
            </a>
            <a href="{{ route('projects.index') }}" class="block px-4 py-2 rounded-md hover:bg-gray-800 {{ request()->routeIs('projects.*') ? 'bg-blue-600' : '' }}">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Projects
            </a>
            <a href="{{ route('collaborations.pending') }}" class="block px-4 py-2 rounded-md hover:bg-gray-800 {{ request()->routeIs('collaborations.*') ? 'bg-blue-600' : '' }}">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Collaborations
            </a>
            <a href="{{ route('verification.status') }}" class="block px-4 py-2 rounded-md hover:bg-gray-800 {{ request()->routeIs('verification.*') ? 'bg-blue-600' : '' }}">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Verification
            </a>
        </nav>
    </div>
</aside>
@endauth
