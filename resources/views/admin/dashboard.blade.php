@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Orders -->
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-mcd-red">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-mcd-red text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Orders</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-green-500 text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-mcd-yellow">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-mcd-yellow text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Pending Orders</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($pendingOrders) }}</p>
            </div>
        </div>
    </div>

    <!-- Total Users -->
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-500 text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Users</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($totalUsers) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Today's Stats & Orders by Status -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Today's Statistics -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Today's Statistics</h3>
            <div class="w-8 h-8 bg-mcd-red bg-opacity-10 rounded-lg flex items-center justify-center">
                <i class="fas fa-calendar-day text-mcd-red"></i>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-2xl font-bold text-mcd-red">{{ number_format($todayOrders) }}</p>
                <p class="text-sm text-gray-600">Orders Today</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-600">Revenue Today</p>
            </div>
        </div>
    </div>

    <!-- Orders by Status -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Orders by Status</h3>
            <div class="w-8 h-8 bg-mcd-yellow bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-pie text-mcd-yellow"></i>
            </div>
        </div>
        <div class="space-y-3">
            @php
                $statusConfig = [
                    'pending' => ['color' => 'yellow', 'icon' => 'clock'],
                    'confirmed' => ['color' => 'blue', 'icon' => 'check-circle'],
                    'processing' => ['color' => 'purple', 'icon' => 'cog'],
                    'shipped' => ['color' => 'indigo', 'icon' => 'truck'],
                    'delivered' => ['color' => 'teal', 'icon' => 'home'],
                    'completed' => ['color' => 'green', 'icon' => 'check-double'],
                    'cancelled' => ['color' => 'red', 'icon' => 'times-circle']
                ];
            @endphp
            @foreach($statusConfig as $status => $config)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-{{ $config['color'] }}-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-{{ $config['icon'] }} text-{{ $config['color'] }}-600 text-sm"></i>
                        </div>
                        <span class="font-medium text-gray-900 capitalize">{{ $status }}</span>
                    </div>
                    <span class="bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800 px-3 py-1 rounded-full text-sm font-medium">
                        {{ $ordersByStatus[$status] ?? 0 }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="bg-white rounded-xl shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-mcd-red hover:text-mcd-dark-red font-medium text-sm">
                View All Orders →
            </a>
        </div>
    </div>
    
    @if($recentOrders->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentOrders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $order->order_number }}</div>
                                <div class="text-sm text-gray-500">{{ $order->items->count() }} items</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-gray-500 text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                <div class="text-sm text-gray-500">{{ ucfirst($order->payment_method) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-blue-100 text-blue-800', 
                                        'processing' => 'bg-purple-100 text-purple-800',
                                        'shipped' => 'bg-indigo-100 text-indigo-800',
                                        'delivered' => 'bg-teal-100 text-teal-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->order_status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->created_at->format('d M Y') }}
                                <div class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-mcd-red hover:text-mcd-dark-red">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="px-6 py-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shopping-cart text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No orders yet</h3>
            <p class="text-gray-500">Orders will appear here once customers start placing them.</p>
        </div>
    @endif
</div>

<!-- Quick Actions -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
    <a href="{{ route('admin.products.create') }}" class="bg-gradient-to-r from-mcd-red to-mcd-dark-red text-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-plus text-white text-xl"></i>
            </div>
            <div>
                <h4 class="font-semibold">Add New Product</h4>
                <p class="text-sm opacity-90">Create a new menu item</p>
            </div>
        </div>
    </a>
    
    <a href="{{ route('admin.orders.index') }}" class="bg-gradient-to-r from-mcd-yellow to-yellow-400 text-gray-900 p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-white bg-opacity-30 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-list text-gray-900 text-xl"></i>
            </div>
            <div>
                <h4 class="font-semibold">Manage Orders</h4>
                <p class="text-sm opacity-75">Process customer orders</p>
            </div>
        </div>
    </a>
    
    <a href="{{ route('admin.messages.index') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-envelope text-white text-xl"></i>
            </div>
            <div>
                <h4 class="font-semibold">View Messages</h4>
                <p class="text-sm opacity-90">Check customer inquiries</p>
            </div>
        </div>
    </a>
</div>
@endsection