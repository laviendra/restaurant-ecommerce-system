<!-- Mobile sidebar overlay -->
<div id="sidebar-overlay" class="fixed inset-0 bg-gray-600 bg-opacity-75 z-20 lg:hidden hidden"></div>

<!-- Sidebar -->
<aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-0 transition-transform duration-300 ease-in-out">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 bg-gray-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                <span class="text-yellow-500 text-2xl font-bold">McD</span>
                <span class="text-white text-lg ml-2">Admin</span>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-yellow-500 text-gray-900' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <!-- Products -->
            <a href="{{ route('admin.products.index') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-yellow-500 text-gray-900' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Products
            </a>

            <!-- Orders -->
            <a href="{{ route('admin.orders.index') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-yellow-500 text-gray-900' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Orders
                @php
                    $pendingOrdersCount = \App\Models\Order::where('order_status', 'pending')->count();
                @endphp
                @if($pendingOrdersCount > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $pendingOrdersCount }}</span>
                @endif
            </a>

            <div class="border-t border-gray-700 my-4"></div>

            <!-- Users -->
            <a href="{{ route('admin.users.index') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-yellow-500 text-gray-900' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Users
            </a>

            <!-- Messages -->
            <a href="{{ route('admin.messages.index') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.messages.*') ? 'bg-yellow-500 text-gray-900' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Messages
                @php
                    $unreadMessagesCount = \App\Models\ContactMessage::unread()->count();
                @endphp
                @if($unreadMessagesCount > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $unreadMessagesCount }}</span>
                @endif
            </a>

            <!-- Reports -->
            <a href="{{ route('admin.reports.index') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-yellow-500 text-gray-900' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Reports
            </a>
        </nav>

        <!-- User Info -->
        <div class="p-4 border-t border-gray-700">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-yellow-500 flex items-center justify-center">
                    <span class="text-gray-900 font-bold text-sm">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-white">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-gray-400">Administrator</p>
                </div>
            </div>
        </div>
    </div>
</aside>
