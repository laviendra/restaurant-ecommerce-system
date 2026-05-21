<!-- Admin Header -->
<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
        <!-- Mobile menu button -->
        <button id="sidebar-toggle" type="button" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <!-- Page Title -->
        <div class="flex-1 lg:flex-none">
            <h1 class="text-lg font-semibold text-gray-900 lg:hidden">@yield('title', 'Dashboard')</h1>
        </div>

        <!-- Right side actions -->
        <div class="flex items-center space-x-4">
            <!-- View Site Link -->
            <a href="{{ route('home') }}" target="_blank" class="text-gray-500 hover:text-gray-700 flex items-center text-sm">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <span class="hidden sm:inline">View Site</span>
            </a>

            <!-- Notifications (placeholder) -->
            <button type="button" class="text-gray-500 hover:text-gray-700 relative">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @php
                    $pendingCount = \App\Models\Order::where('order_status', 'pending')->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full">{{ $pendingCount > 9 ? '9+' : $pendingCount }}</span>
                @endif
            </button>

            <!-- User Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button type="button" class="flex items-center text-gray-700 hover:text-gray-900 focus:outline-none" onclick="this.nextElementSibling.classList.toggle('hidden')">
                    <div class="w-8 h-8 rounded-full bg-yellow-500 flex items-center justify-center mr-2">
                        <span class="text-gray-900 font-bold text-sm">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</span>
                    </div>
                    <span class="hidden sm:inline text-sm font-medium">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                    <a href="{{ route('account.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        My Profile
                    </a>
                    <div class="border-t border-gray-100"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
