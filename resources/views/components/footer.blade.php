<footer class="bg-gray-900 text-white mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-lg font-semibold mb-4">WorkTracker</h3>
                <p class="text-gray-400">Manage projects, track progress, and collaborate with developers efficiently.</p>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                <ul class="text-gray-400 space-y-2">
                    <li><a href="{{ route('dashboard') }}" class="hover:text-white">Dashboard</a></li>
                    <li><a href="{{ route('projects.index') }}" class="hover:text-white">Projects</a></li>
                    @auth
                        <li><a href="{{ route('profile.show') }}" class="hover:text-white">Profile</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="hover:text-white">Login</a></li>
                    @endauth
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Contact</h4>
                <p class="text-gray-400">Email: support@worktracker.com</p>
                <p class="text-gray-400">Phone: +1 (555) 123-4567</p>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; 2026 WorkTracker. All rights reserved.</p>
        </div>
    </div>
</footer>
