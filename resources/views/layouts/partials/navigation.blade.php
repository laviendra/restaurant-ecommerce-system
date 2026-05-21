<!-- Main Navigation -->
<nav class="bg-red-600 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    <span class="text-white text-2xl font-bold">McD</span>
                </a>
                <div class="hidden md:flex ml-10 space-x-8">
                    <a href="{{ route('home') }}" class="text-white hover:text-yellow-300 font-medium {{ request()->routeIs('home') ? 'text-yellow-300' : '' }}">Home</a>
                    <a href="{{ route('products.index') }}" class="text-white hover:text-yellow-300 font-medium {{ request()->routeIs('products.*') ? 'text-yellow-300' : '' }}">Menu</a>
                    <a href="{{ route('about') }}" class="text-white hover:text-yellow-300 font-medium {{ request()->routeIs('about') ? 'text-yellow-300' : '' }}">About</a>
                    <a href="{{ route('contact.index') }}" class="text-white hover:text-yellow-300 font-medium {{ request()->routeIs('contact.*') ? 'text-yellow-300' : '' }}">Contact</a>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('cart.index') }}" class="text-white hover:text-yellow-300 relative {{ request()->routeIs('cart.*') ? 'text-yellow-300' : '' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </a>
                    <a href="{{ route('orders.index') }}" class="text-white hover:text-yellow-300 font-medium {{ request()->routeIs('orders.*') ? 'text-yellow-300' : '' }}">Orders</a>
                    <a href="{{ route('account.index') }}" class="text-white hover:text-yellow-300 font-medium {{ request()->routeIs('account.*') ? 'text-yellow-300' : '' }}">Account</a>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-yellow-300 hover:text-yellow-100 font-medium">Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-white hover:text-yellow-300 font-medium">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-white hover:text-yellow-300 font-medium">Login</a>
                    <a href="{{ route('register') }}" class="bg-yellow-500 text-gray-900 px-4 py-2 rounded-md font-medium hover:bg-yellow-400">Register</a>
                @endauth
            </div>
            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button type="button" id="mobile-menu-button" class="text-white hover:text-yellow-300 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <!-- Mobile menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-red-700">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 text-white hover:bg-red-600 rounded-md">Home</a>
            <a href="{{ route('products.index') }}" class="block px-3 py-2 text-white hover:bg-red-600 rounded-md">Menu</a>
            <a href="{{ route('about') }}" class="block px-3 py-2 text-white hover:bg-red-600 rounded-md">About</a>
            <a href="{{ route('contact.index') }}" class="block px-3 py-2 text-white hover:bg-red-600 rounded-md">Contact</a>
            @auth
                <a href="{{ route('cart.index') }}" class="block px-3 py-2 text-white hover:bg-red-600 rounded-md">Cart</a>
                <a href="{{ route('orders.index') }}" class="block px-3 py-2 text-white hover:bg-red-600 rounded-md">Orders</a>
                <a href="{{ route('account.index') }}" class="block px-3 py-2 text-white hover:bg-red-600 rounded-md">Account</a>
            @endauth
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
});
</script>
