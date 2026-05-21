@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="mt-1 text-sm text-gray-600">Overview of McD business performance</p>
    </div>

    <!-- Main Metrics Cards -->
    <div class="mb-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-full p-3">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Orders</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($metrics['total_orders']) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($metrics['total_revenue'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-orange-100 rounded-full p-3">
                    <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pending Orders</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($metrics['pending_orders']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Statistics Section -->
    <div class="mb-8 grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-lg shadow p-6 text-white">
            <h3 class="text-lg font-semibold mb-2">Today's Orders</h3>
            <p class="text-4xl font-bold">{{ number_format($todayStats['orders']) }}</p>
            <p class="text-sm opacity-80 mt-1">Orders placed today</p>
        </div>
        <div class="bg-gradient-to-r from-green-400 to-green-500 rounded-lg shadow p-6 text-white">
            <h3 class="text-lg font-semibold mb-2">Today's Revenue</h3>
            <p class="text-4xl font-bold">Rp {{ number_format($todayStats['revenue'], 0, ',', '.') }}</p>
            <p class="text-sm opacity-80 mt-1">Revenue from completed orders today</p>
        </div>
    </div>

    <!-- Orders by Status Summary -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Orders by Status</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-400">
                <div class="text-sm font-medium text-gray-500">Pending</div>
                <div class="mt-1 text-2xl font-semibold text-yellow-600">{{ $ordersByStatus['pending'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-400">
                <div class="text-sm font-medium text-gray-500">Confirmed</div>
                <div class="mt-1 text-2xl font-semibold text-blue-600">{{ $ordersByStatus['confirmed'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-400">
                <div class="text-sm font-medium text-gray-500">Processing</div>
                <div class="mt-1 text-2xl font-semibold text-purple-600">{{ $ordersByStatus['processing'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-400">
                <div class="text-sm font-medium text-gray-500">Completed</div>
                <div class="mt-1 text-2xl font-semibold text-green-600">{{ $ordersByStatus['completed'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-400">
                <div class="text-sm font-medium text-gray-500">Cancelled</div>
                <div class="mt-1 text-2xl font-semibold text-red-600">{{ $ordersByStatus['cancelled'] ?? 0 }}</div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Recent Orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-yellow-600 hover:text-yellow-700">
                    View all orders →
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->order_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $order->user->email ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-blue-100 text-blue-800',
                                    'processing' => 'bg-purple-100 text-purple-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->order_status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.orders.show', $order) }}" 
                               class="text-yellow-600 hover:text-yellow-900" title="View Details">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="mt-2">No orders yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
