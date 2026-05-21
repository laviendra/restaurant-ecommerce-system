@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-r from-red-600 via-red-700 to-red-800 text-white py-24 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-full h-full" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 tracking-tight">
                Welcome to <span class="text-yellow-400">McDonald's</span>
            </h1>
            <p class="text-2xl md:text-3xl mb-4 text-yellow-200 font-semibold">I'm Lovin' It</p>
            <p class="text-lg md:text-xl mb-10 max-w-3xl mx-auto text-red-100 leading-relaxed">
                Nikmati menu favorit Anda dengan kemudahan pemesanan online. 
                Pesan sekarang dan rasakan kelezatan McDonald's yang selalu segar dan lezat!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('products.index') }}" class="bg-yellow-500 text-gray-900 px-10 py-4 rounded-full font-bold text-lg hover:bg-yellow-400 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl inline-block">
                Lihat Menu
            </a>
                <a href="{{ route('about') }}" class="bg-white text-red-600 px-10 py-4 rounded-full font-bold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl inline-block">
                    Tentang Kami
                </a>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Tentang McDonald's</h2>
            <div class="w-24 h-1 bg-red-600 mx-auto"></div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <div class="bg-gradient-to-br from-red-50 to-yellow-50 rounded-2xl p-8 shadow-lg">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">
                        Sejarah McDonald's
                    </h3>
                    <p class="text-gray-700 mb-4 leading-relaxed">
                    McDonald's adalah jaringan restoran cepat saji terbesar di dunia yang didirikan pada tahun 1940 
                    oleh Richard dan Maurice McDonald di San Bernardino, California, Amerika Serikat.
                </p>
                    <p class="text-gray-700 mb-4 leading-relaxed">
                    Di Indonesia, McDonald's pertama kali hadir pada tahun 1991 dan sejak saat itu telah menjadi 
                    salah satu restoran cepat saji favorit masyarakat Indonesia dengan ratusan gerai yang tersebar 
                    di seluruh nusantara.
                </p>
                    <p class="text-gray-700 leading-relaxed">
                        Dengan tagline <strong class="text-red-600">"I'm Lovin' It"</strong>, McDonald's terus berkomitmen untuk menyajikan makanan berkualitas 
                    dengan pelayanan terbaik kepada seluruh pelanggan.
                </p>
                </div>
                <a href="{{ route('about') }}" class="inline-block text-red-600 font-semibold hover:text-red-700 transition-colors">
                    Pelajari lebih lanjut →
                </a>
            </div>
            <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-2xl p-12 shadow-2xl transform hover:scale-105 transition-transform">
                <div class="text-center text-white">
                    <div class="text-8xl font-bold mb-4">McD</div>
                    <p class="text-3xl text-yellow-300 font-semibold">I'm Lovin' It</p>
                </div>
            </div>
        </div>
    </div>
</section> 

<!-- Our Values -->
<section class="py-20 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Nilai-Nilai Kami</h2>
            <div class="w-24 h-1 bg-red-600 mx-auto mb-4"></div>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Komitmen kami untuk memberikan yang terbaik kepada pelanggan
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl shadow-lg p-8 text-center transform hover:scale-105 transition-all hover:shadow-2xl border-t-4 border-red-600">
                <div class="bg-gradient-to-br from-red-100 to-red-200 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Kualitas</h3>
                <p class="text-gray-600 leading-relaxed">
                    Kami berkomitmen untuk menyajikan makanan dengan bahan-bahan berkualitas tinggi 
                    dan standar kebersihan internasional.
                </p>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-8 text-center transform hover:scale-105 transition-all hover:shadow-2xl border-t-4 border-yellow-500">
                <div class="bg-gradient-to-br from-yellow-100 to-yellow-200 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Pelayanan</h3>
                <p class="text-gray-600 leading-relaxed">
                    Pelayanan ramah dan cepat adalah prioritas kami untuk memberikan pengalaman 
                    terbaik kepada setiap pelanggan.
                </p>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-8 text-center transform hover:scale-105 transition-all hover:shadow-2xl border-t-4 border-green-500">
                <div class="bg-gradient-to-br from-green-100 to-green-200 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Keberlanjutan</h3>
                <p class="text-gray-600 leading-relaxed">
                    Kami peduli terhadap lingkungan dan terus berupaya untuk mengurangi dampak 
                    operasional kami terhadap bumi.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
@if($featuredProducts->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Menu Favorit</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Pilihan menu terbaik yang paling disukai pelanggan kami
            </p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                @include('products.partials.product-card', ['product' => $product])
            @endforeach
        </div>
        <div class="text-center mt-10">
            <a href="{{ route('products.index') }}" class="bg-red-600 text-white px-8 py-3 rounded-md font-medium hover:bg-red-700 transition-colors inline-block">
                Lihat Semua Menu
            </a>
        </div>
    </div>
</section>
@endif

<!-- Why Choose Us Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Mengapa Memilih Kami?</h2>
            <div class="w-24 h-1 bg-red-600 mx-auto mb-4"></div>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Keunggulan yang membuat kami menjadi pilihan terbaik Anda
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-gradient-to-br from-red-50 to-yellow-50 rounded-2xl p-8 text-center transform hover:scale-105 transition-all hover:shadow-xl">
                <div class="bg-red-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Cepat & Mudah</h3>
                <p class="text-gray-700 leading-relaxed">Pesan makanan favorit Anda dengan cepat dan mudah melalui website kami. Hanya dalam beberapa klik, pesanan Anda siap!</p>
            </div>
            <div class="bg-gradient-to-br from-yellow-50 to-red-50 rounded-2xl p-8 text-center transform hover:scale-105 transition-all hover:shadow-xl">
                <div class="bg-yellow-500 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Kualitas Terjamin</h3>
                <p class="text-gray-700 leading-relaxed">Bahan-bahan berkualitas tinggi dengan standar kebersihan internasional. Setiap hidangan dibuat dengan cinta dan perhatian.</p>
            </div>
            <div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-2xl p-8 text-center transform hover:scale-105 transition-all hover:shadow-xl">
                <div class="bg-orange-500 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Pengiriman Cepat</h3>
                <p class="text-gray-700 leading-relaxed">Layanan pengiriman cepat ke lokasi Anda dengan kondisi makanan tetap segar dan hangat. Kami menjamin kepuasan Anda!</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-20 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Hubungi Kami</h2>
            <div class="w-24 h-1 bg-red-600 mx-auto mb-4"></div>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Ada pertanyaan atau butuh bantuan? Tim kami siap membantu Anda
            </p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Information -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-8">Informasi Kontak</h3>
                
                <div class="space-y-6">
                    <div class="flex items-start group">
                        <div class="bg-red-100 w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-red-600 transition-colors">
                            <svg class="w-7 h-7 text-red-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="ml-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-1">Alamat</h4>
                            <p class="text-gray-600 leading-relaxed">Jl. Sudirman No. 123<br>Jakarta Pusat, 10220<br>Indonesia</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start group">
                        <div class="bg-red-100 w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-red-600 transition-colors">
                            <svg class="w-7 h-7 text-red-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div class="ml-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-1">Telepon</h4>
                            <p class="text-gray-600">(021) 1234-5678</p>
                            <p class="text-gray-600">14045 (McDelivery)</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start group">
                        <div class="bg-red-100 w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-red-600 transition-colors">
                            <svg class="w-7 h-7 text-red-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="ml-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-1">Email</h4>
                            <p class="text-gray-600">info@mcd.com</p>
                            <p class="text-gray-600">support@mcd.com</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start group">
                        <div class="bg-red-100 w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-red-600 transition-colors">
                            <svg class="w-7 h-7 text-red-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-1">Jam Operasional</h4>
                            <p class="text-gray-600">Senin - Minggu: 07:00 - 23:00</p>
                            <p class="text-gray-600">McDelivery: 24 Jam</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <a href="{{ route('contact.index') }}" class="block w-full bg-red-600 text-white py-3 px-6 rounded-lg hover:bg-red-700 transition-colors font-semibold text-center">
                        Kirim Pesan
                    </a>
                </div>
            </div>

            <!-- Quick Contact Form -->
            <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-2xl shadow-xl p-8 text-white">
                <h3 class="text-2xl font-bold mb-6">Kirim Pesan Cepat</h3>
                
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium mb-2">Nama</label>
                        <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name ?? '') }}" 
                            class="w-full px-4 py-3 border border-red-400 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-gray-900 @error('name') border-red-300 @enderror" 
                            required>
                        @error('name')
                            <p class="mt-1 text-sm text-yellow-200">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium mb-2">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email ?? '') }}" 
                            class="w-full px-4 py-3 border border-red-400 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-gray-900 @error('email') border-red-300 @enderror" 
                            required>
                        @error('email')
                            <p class="mt-1 text-sm text-yellow-200">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="subject" class="block text-sm font-medium mb-2">Subjek</label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject') }}" 
                            class="w-full px-4 py-3 border border-red-400 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-gray-900 @error('subject') border-red-300 @enderror" 
                            required>
                        @error('subject')
                            <p class="mt-1 text-sm text-yellow-200">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="message" class="block text-sm font-medium mb-2">Pesan</label>
                        <textarea name="message" id="message" rows="4" 
                            class="w-full px-4 py-3 border border-red-400 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-gray-900 @error('message') border-red-300 @enderror" 
                            required>{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-yellow-200">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button type="submit" class="w-full bg-yellow-500 text-gray-900 py-3 px-6 rounded-lg hover:bg-yellow-400 transition-colors font-bold text-lg shadow-lg">
                        Kirim Pesan
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add to cart functionality
    document.querySelectorAll('.add-to-cart-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            
            @auth
            fetch(`/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Produk berhasil ditambahkan ke keranjang!');
                } else {
                    alert(data.message || 'Gagal menambahkan produk ke keranjang');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
            @else
            window.location.href = '{{ route("login") }}';
            @endauth
        });
    });
});
</script>
@endpush
@endsection
