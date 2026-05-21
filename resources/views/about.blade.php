@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-r from-red-600 via-red-700 to-red-800 text-white py-20 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-full h-full" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h1 class="text-5xl md:text-6xl font-bold mb-6">Tentang Kami</h1>
        <p class="text-xl md:text-2xl text-red-100 max-w-2xl mx-auto">
            Mengenal lebih dekat McDonald's Indonesia dan tim di balik website ini
        </p>
    </div>
</section>

<!-- About Content -->
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

<!-- Student Information Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Informasi Pembuat Web</h2>
            <div class="w-24 h-1 bg-red-600 mx-auto"></div>
        </div>
        <div class="max-w-4xl mx-auto">
            <div class="bg-gradient-to-br from-red-50 via-yellow-50 to-red-50 rounded-2xl shadow-xl p-10">
                <!-- Photo Section -->
                <div class="flex justify-center mb-8">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-red-500 to-yellow-500 rounded-full blur-lg opacity-50"></div>
                        <img src="{{ asset('storage/photos/profile.jpg') }}" 
                             alt="Foto Pembuat Web" 
                             class="relative w-48 h-48 rounded-full object-cover border-4 border-white shadow-2xl transform hover:scale-105 transition-transform"
                             onerror="this.src='https://ui-avatars.com/api/?name=Rafli+Praditta&size=200&background=dc2626&color=fff&bold=true'">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="flex items-start group hover:bg-white p-4 rounded-lg transition-colors">
                            <div class="bg-gradient-to-br from-red-500 to-red-600 text-white rounded-full p-3 mr-4 shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1 text-lg">Nama</h3>
                                <p class="text-gray-700 text-lg">Rafli Praditta</p>
                            </div>
                        </div>
                        <div class="flex items-start group hover:bg-white p-4 rounded-lg transition-colors">
                            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-full p-3 mr-4 shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1 text-lg">NIM</h3>
                                <p class="text-gray-700 text-lg">241011700604</p>
                            </div>
                        </div>
                        <div class="flex items-start group hover:bg-white p-4 rounded-lg transition-colors">
                            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full p-3 mr-4 shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1 text-lg">Kelas</h3>
                                <p class="text-gray-700 text-lg">03SIFP006</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="flex items-start group hover:bg-white p-4 rounded-lg transition-colors">
                            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-full p-3 mr-4 shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1 text-lg">Semester</h3>
                                <p class="text-gray-700 text-lg">3 (Tiga)</p>
                            </div>
                        </div>
                        <div class="flex items-start group hover:bg-white p-4 rounded-lg transition-colors">
                            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-full p-3 mr-4 shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1 text-lg">Jenis Tugas</h3>
                                <p class="text-gray-700 text-lg">Tugas Akhir dan UAS</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Course Information Section -->
<section class="py-20 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Informasi Mata Kuliah</h2>
            <div class="w-24 h-1 bg-red-600 mx-auto"></div>
        </div>
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-10">
                <div class="space-y-6">
                    <div class="border-l-4 border-red-600 pl-6 py-4 hover:bg-red-50 rounded-r-lg transition-colors">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            Nama Mata Kuliah
                        </h3>
                        <p class="text-gray-700 text-lg">Rekayasa Web</p>
                    </div>
                    <div class="border-l-4 border-yellow-500 pl-6 py-4 hover:bg-yellow-50 rounded-r-lg transition-colors">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            Dosen Pengampu
                        </h3>
                        <p class="text-gray-700 text-lg">Mega Permata Sapani, M.Kom</p>
                    </div>
                    <div class="border-l-4 border-blue-500 pl-6 py-4 hover:bg-blue-50 rounded-r-lg transition-colors">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            Program Studi
                        </h3>
                        <p class="text-gray-700 text-lg">Sistem Informasi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-red-600 via-red-700 to-red-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl md:text-5xl font-bold mb-4">Siap Memesan?</h2>
        <p class="text-xl md:text-2xl text-red-100 mb-10 max-w-2xl mx-auto">
            Nikmati menu favorit Anda sekarang juga! Pesan dengan mudah melalui website kami.
        </p>
        <a href="{{ route('products.index') }}" class="bg-yellow-500 text-gray-900 px-10 py-4 rounded-full font-bold text-lg hover:bg-yellow-400 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl inline-block">
            Lihat Menu
        </a>
    </div>
</section>
@endsection