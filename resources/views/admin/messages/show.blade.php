@extends('layouts.admin')

@section('title', 'Message - ' . $message->subject)

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.messages.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Message Details</h1>
                <p class="mt-1 text-sm text-gray-600">View contact message from customer</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Message Content -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <!-- Message Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-medium text-gray-900">{{ $message->subject }}</h2>
                        @if($message->is_read)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Read
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Unread
                            </span>
                        @endif
                    </div>
                    <p class="mt-1 text-sm text-gray-500">
                        Received on {{ $message->created_at->format('d M Y, H:i') }}
                    </p>
                </div>

                <!-- Message Body -->
                <div class="px-6 py-6">
                    <div class="prose max-w-none">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $message->message }}</p>
                    </div>
                </div>
            </div>

            <!-- Reply Section (Info Only) -->
            <div class="mt-6 bg-blue-50 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Reply to this message</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>To reply to this message, please send an email to: <a href="mailto:{{ $message->email }}" class="font-medium underline">{{ $message->email }}</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sender Information -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Sender Information</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <div class="flex-shrink-0 h-16 w-16">
                            <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-600 font-bold text-xl">{{ strtoupper(substr($message->name, 0, 2)) }}</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-bold text-gray-900">{{ $message->name }}</h4>
                            @if($message->user)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Registered User
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Guest
                                </span>
                            @endif
                        </div>
                    </div>

                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="mailto:{{ $message->email }}" class="text-yellow-600 hover:text-yellow-800">
                                    {{ $message->email }}
                                </a>
                            </dd>
                        </div>
                        @if($message->user)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $message->user->phone ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $message->user->created_at->format('d M Y') }}</dd>
                        </div>
                        @endif
                    </dl>

                    @if($message->user)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.users.show', $message->user) }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            View User Profile
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}" 
                       class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        Reply via Email
                    </a>
                    <a href="{{ route('admin.messages.index') }}" 
                       class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Messages
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
