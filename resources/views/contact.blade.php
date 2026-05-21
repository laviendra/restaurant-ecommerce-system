@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-r from-red-600 via-red-700 to-red-800 text-white py-20 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-full h-full" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h1 class="text-5xl md:text-6xl font-bold mb-6">Hubungi Kami</h1>
        <p class="text-xl md:text-2xl text-red-100 max-w-2xl mx-auto">
            Kami siap membantu Anda. Tim customer service kami siap menjawab pertanyaan dan membantu kebutuhan Anda.
        </p>
    </div>
</section>

<!-- Contact Content -->
<section class="py-20 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Information -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Informasi Kontak</h2>
                    <div class="w-20 h-1 bg-red-600 mx-auto"></div>
                </div>
                
                <div class="space-y-6">
                    <div class="flex items-start group hover:bg-red-50 p-4 rounded-lg transition-colors">
                        <div class="bg-gradient-to-br from-red-500 to-red-600 w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0 shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Alamat</h3>
                            <p class="text-gray-600 leading-relaxed">Jl. Sudirman No. 123<br>Jakarta Pusat, 10220<br>Indonesia</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start group hover:bg-red-50 p-4 rounded-lg transition-colors">
                        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0 shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Telepon</h3>
                            <p class="text-gray-600 font-medium">(021) 1234-5678</p>
                            <p class="text-gray-600">14045 (McDelivery)</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start group hover:bg-red-50 p-4 rounded-lg transition-colors">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0 shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Email</h3>
                            <p class="text-gray-600">info@mcd.com</p>
                            <p class="text-gray-600">support@mcd.com</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start group hover:bg-red-50 p-4 rounded-lg transition-colors">
                        <div class="bg-gradient-to-br from-green-500 to-green-600 w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0 shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Jam Operasional</h3>
                            <p class="text-gray-600">Senin - Minggu: 07:00 - 23:00</p>
                            <p class="text-gray-600 font-semibold text-red-600">McDelivery: 24 Jam</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-2xl shadow-xl p-8 text-white">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold mb-2">Kirim Pesan</h2>
                    <div class="w-20 h-1 bg-yellow-400 mx-auto"></div>
                </div>
                
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-semibold mb-2">Nama</label>
                        <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name ?? '') }}" 
                            class="w-full px-4 py-3 border border-red-400 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-gray-900 @error('name') border-red-300 @enderror" 
                            required>
                        @error('name')
                            <p class="mt-1 text-sm text-yellow-200">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-semibold mb-2">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email ?? '') }}" 
                            class="w-full px-4 py-3 border border-red-400 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-gray-900 @error('email') border-red-300 @enderror" 
                            required>
                        @error('email')
                            <p class="mt-1 text-sm text-yellow-200">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="subject" class="block text-sm font-semibold mb-2">Subjek</label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject') }}" 
                            class="w-full px-4 py-3 border border-red-400 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-gray-900 @error('subject') border-red-300 @enderror" 
                            required>
                        @error('subject')
                            <p class="mt-1 text-sm text-yellow-200">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="message" class="block text-sm font-semibold mb-2">Pesan</label>
                        <textarea name="message" id="message" rows="5" 
                            class="w-full px-4 py-3 border border-red-400 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-gray-900 @error('message') border-red-300 @enderror" 
                            required>{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-yellow-200">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button type="submit" class="w-full bg-yellow-500 text-gray-900 py-3 px-6 rounded-lg hover:bg-yellow-400 transition-all font-bold text-lg shadow-lg transform hover:scale-105">
                        Kirim Pesan
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection