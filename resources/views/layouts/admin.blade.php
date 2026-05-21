<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ config('app.name', 'McD E-Commerce') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'mcd-red': '#DC2626',
                        'mcd-yellow': '#FCD34D',
                        'mcd-dark-red': '#B91C1C',
                    }
                }
            }
        }
    </script>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gradient-to-b from-mcd-red to-mcd-dark-red shadow-xl">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 bg-mcd-dark-red">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-mcd-yellow rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-hamburger text-mcd-red text-lg"></i>
                        </div>
                        <div>
                            <h1 class="text-white text-xl font-bold">McD Admin</h1>
                            <p class="text-mcd-yellow text-xs">Management Panel</p>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.dashboard') ? 'bg-white bg-opacity-20 border-l-4 border-mcd-yellow' : '' }}">
                        <i class="fas fa-tachometer-alt w-5 text-center mr-3"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    
                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.products.*') ? 'bg-white bg-opacity-20 border-l-4 border-mcd-yellow' : '' }}">
                        <i class="fas fa-hamburger w-5 text-center mr-3"></i>
                        <span class="font-medium">Products</span>
                    </a>
                    
                    <a href="{{ route('admin.orders.index') }}" 
                       class="flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.orders.*') ? 'bg-white bg-opacity-20 border-l-4 border-mcd-yellow' : '' }}">
                        <i class="fas fa-shopping-cart w-5 text-center mr-3"></i>
                        <span class="font-medium">Orders</span>
                        @php
                            $pendingOrders = \App\Models\Order::where('order_status', 'pending')->count();
                        @endphp
                        @if($pendingOrders > 0)
                            <span class="ml-auto bg-mcd-yellow text-mcd-red text-xs font-bold px-2 py-1 rounded-full">{{ $pendingOrders }}</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.users.*') ? 'bg-white bg-opacity-20 border-l-4 border-mcd-yellow' : '' }}">
                        <i class="fas fa-users w-5 text-center mr-3"></i>
                        <span class="font-medium">Users</span>
                    </a>
                    
                    <a href="{{ route('admin.messages.index') }}" 
                       class="flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.messages.*') ? 'bg-white bg-opacity-20 border-l-4 border-mcd-yellow' : '' }}">
                        <i class="fas fa-envelope w-5 text-center mr-3"></i>
                        <span class="font-medium">Messages</span>
                        @php
                            $unreadMessages = \App\Models\ContactMessage::where('is_read', false)->count();
                        @endphp
                        @if($unreadMessages > 0)
                            <span class="ml-auto bg-mcd-yellow text-mcd-red text-xs font-bold px-2 py-1 rounded-full">{{ $unreadMessages }}</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.reports.index') }}" 
                       class="flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.reports.*') ? 'bg-white bg-opacity-20 border-l-4 border-mcd-yellow' : '' }}">
                        <i class="fas fa-chart-bar w-5 text-center mr-3"></i>
                        <span class="font-medium">Reports</span>
                    </a>
                    
                    <!-- Divider -->
                    <div class="border-t border-white border-opacity-20 my-4"></div>
                    
                    <a href="{{ route('home') }}" target="_blank"
                       class="flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10">
                        <i class="fas fa-external-link-alt w-5 text-center mr-3"></i>
                        <span class="font-medium">View Website</span>
                    </a>
                </nav>
                
                <!-- User Info & Logout -->
                <div class="p-4 border-t border-white border-opacity-20">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-mcd-yellow rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user text-mcd-red text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-white text-sm font-medium">{{ auth()->user()->name }}</p>
                            <p class="text-mcd-yellow text-xs">Administrator</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-2 text-white rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10">
                            <i class="fas fa-sign-out-alt w-5 text-center mr-3"></i>
                            <span class="font-medium">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">@yield('title', 'Dashboard')</h1>
                        <p class="text-gray-600 text-sm mt-1">{{ now()->format('l, d F Y') }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Quick Stats -->
                        <div class="hidden md:flex items-center space-x-6">
                            @php
                                $todayOrders = \App\Models\Order::whereDate('created_at', today())->count();
                                $todayRevenue = \App\Models\Order::whereDate('created_at', today())->where('payment_status', 'paid')->sum('total_amount');
                            @endphp
                            <div class="text-center">
                                <p class="text-2xl font-bold text-mcd-red">{{ $todayOrders }}</p>
                                <p class="text-xs text-gray-500">Orders Today</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500">Revenue Today</p>
                            </div>
                        </div>
                        
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="p-2 text-gray-400 hover:text-gray-600 relative">
                                <i class="fas fa-bell text-lg"></i>
                                @if($pendingOrders > 0 || $unreadMessages > 0)
                                    <span class="absolute -top-1 -right-1 bg-mcd-red text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ $pendingOrders + $unreadMessages }}
                                    </span>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 p-6 bg-gray-50">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-green-700 font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-red-700 font-medium">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>