<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-2xl font-bold text-yellow-500 mb-4">McD</h3>
                <p class="text-gray-400">I'm Lovin' It</p>
                <p class="text-gray-400 mt-2">Nikmati kelezatan McDonald's dengan kemudahan pemesanan online.</p>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Menu</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="{{ route('products.index') }}" class="hover:text-white transition-colors">Semua Menu</a></li>
                    <li><a href="{{ route('products.index', ['category' => 1]) }}" class="hover:text-white transition-colors">Burgers</a></li>
                    <li><a href="{{ route('products.index', ['category' => 2]) }}" class="hover:text-white transition-colors">Chicken</a></li>
                    <li><a href="{{ route('products.index', ['category' => 4]) }}" class="hover:text-white transition-colors">Drinks</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Informasi</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">Tentang Kami</a></li>
                    <li><a href="{{ route('contact.index') }}" class="hover:text-white transition-colors">Hubungi Kami</a></li>
                    @auth
                        <li><a href="{{ route('orders.index') }}" class="hover:text-white transition-colors">Pesanan Saya</a></li>
                        <li><a href="{{ route('account.index') }}" class="hover:text-white transition-colors">Akun Saya</a></li>
                    @endauth
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Kontak</h4>
                <ul class="space-y-2 text-gray-400">
                    <li class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        info@mcd.com
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        (021) 1234-5678
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Jl. Sudirman No. 123, Jakarta
                    </li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; {{ date('Y') }} Rafli Praditta - McDonald's.</p>
        </div>
    </div>
</footer>
