@extends('layout.master')
@section('title', 'Detail Villa - ' . $cabang->nama_cabang)

@section('content')
<!-- Load Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400&family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

<style>
    .font-roboto { font-family: 'Roboto', sans-serif; }
    .font-noto { font-family: 'Noto Serif', serif; }
</style>

<div class="bg-gray-50 min-h-screen pb-20">
    
    <!-- SIMPLE HEADER -->
    <div class="bg-green-600 py-8 text-center shadow-md">
        <div class="container mx-auto px-4">
            <h1 class="font-roboto text-3xl md:text-4xl font-black text-white tracking-wide uppercase mb-2">
                The Green Villa Parepare
            </h1>
            
            <div class="flex flex-wrap justify-center items-center gap-4 text-green-100 font-roboto text-sm md:text-base">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    {{ $cabang->lokasi }}
                </div>
                <span class="opacity-50">|</span>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    {{ $cabang->kategori_cabang }}
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT GRID -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- KOLOM KIRI (Daftar Unit & Kolam Renang) -->
            <div class="lg:col-span-2 space-y-10">
                
                <!-- SECTION: PILIHAN UNIT VILLA -->
                <div>
                    <h2 class="font-roboto text-2xl font-bold text-gray-800 mb-6 border-l-8 border-green-600 pl-4">
                        Pilihan Unit Villa
                    </h2>
                    
                    <div class="space-y-8">
                        <!-- UNIT 1: JASMINE -->
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                            <!-- Galeri -->
                            <div class="grid grid-cols-3 gap-1 bg-gray-100">
                                <img src="https://placehold.co/800x600/e6fffa/047857?text=Jasmine" class="col-span-2 w-full h-48 object-cover">
                                <div class="grid grid-rows-2 gap-1">
                                    <img src="https://placehold.co/400x300/e6fffa/047857?text=Kamar" class="w-full h-full object-cover">
                                    <img src="https://placehold.co/400x300/e6fffa/047857?text=Dapur" class="w-full h-full object-cover">
                                </div>
                            </div>
                            
                            <!-- Detail -->
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="font-roboto text-xl font-bold text-gray-900">Villa Jasmine</h3>
                                    <span class="bg-green-100 text-green-800 text-sm font-bold font-roboto px-3 py-1 rounded-full">Rp 400.000</span>
                                </div>
                                <p class="font-noto text-gray-600 text-sm leading-relaxed mb-4">
                                    Fasilitas lengkap (2 kamar tidur, AC, kipas angin, 2 WC, ruang tamu, ruang makan, dapur lengkap, kulkas, alat solat).
                                </p>
                                <div class="border-t border-gray-100 pt-3">
                                    <span class="text-xs font-bold text-green-600 uppercase tracking-wider">Fasilitas Unggulan:</span>
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">Privasi Terjaga</span>
                                        <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">Homey</span>
                                        <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">Full Furnished</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- UNIT 2: ANGGREK -->
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                            <!-- Galeri -->
                            <div class="grid grid-cols-3 gap-1 bg-gray-100">
                                <img src="https://placehold.co/800x600/e6fffa/047857?text=Anggrek" class="col-span-2 w-full h-48 object-cover">
                                <div class="grid grid-rows-2 gap-1">
                                    <img src="https://placehold.co/400x300/e6fffa/047857?text=Tengah" class="w-full h-full object-cover">
                                    <img src="https://placehold.co/400x300/e6fffa/047857?text=Karpet" class="w-full h-full object-cover">
                                </div>
                            </div>

                            <!-- Detail -->
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="font-roboto text-xl font-bold text-gray-900">Villa Anggrek</h3>
                                    <span class="bg-green-100 text-green-800 text-sm font-bold font-roboto px-3 py-1 rounded-full">Rp 400.000</span>
                                </div>
                                <p class="font-noto text-gray-600 text-sm leading-relaxed mb-4">
                                    Fasilitas lengkap (2 kamar tidur, 2 WC, ruangan leluasa, AC, kipas, karpet jumbo, dapur lengkap, kulkas, alat solat).
                                </p>
                                <div class="border-t border-gray-100 pt-3">
                                    <span class="text-xs font-bold text-green-600 uppercase tracking-wider">Fasilitas Unggulan:</span>
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">Ruang Luas</span>
                                        <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">Karpet Jumbo</span>
                                        <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">Rombongan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION: FASILITAS UTAMA (KOLAM RENANG) -->
                <div>
                    <h2 class="font-roboto text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <span class="bg-green-600 w-1.5 h-6 mr-3 rounded-full"></span>
                        Fasilitas Utama
                    </h2>
                    <div class="bg-white rounded-xl shadow-md overflow-hidden relative group">
                        <img src="https://placehold.co/1000x400/0ea5e9/e0f2fe?text=Kolam+Renang+Outdoor" class="w-full h-64 object-cover">
                        <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-gray-900 to-transparent p-4 pt-10">
                            <h3 class="font-roboto text-xl text-white font-bold">Kolam Renang</h3>
                            <p class="font-noto text-sm text-gray-200">Area berenang yang bersih dan segar untuk dewasa maupun anak-anak.</p>
                        </div>
                    </div>
                </div>

            </div>
            
            <!-- KOLOM KANAN (PANEL LAYANAN TAMBAHAN & BUTTON) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-6 lg:sticky lg:top-8">
                    
                    <!-- BUTTON PESAN SEKARANG (MOBILE TOP / DESKTOP) -->
                    <div class="mb-6">
                        <button class="w-full bg-green-600 hover:bg-green-700 text-white font-roboto font-bold py-4 rounded-lg shadow-lg transform transition hover:-translate-y-1 text-lg flex justify-center items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            PESAN SEKARANG
                        </button>
                        <p class="text-xs text-center text-gray-400 mt-2 font-noto">Reservasi mudah & cepat</p>
                    </div>

                    <h2 class="font-roboto text-xl font-bold text-gray-800 mb-4 border-b pb-2 border-gray-200">
                        Layanan Tambahan
                    </h2>
                    
                    <div class="space-y-4 font-noto">
                        
                        <!-- Item 1: Gazebo -->
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-100 last:border-0">
                            <img src="https://placehold.co/100x100/10b981/ffffff?text=Gazebo" class="w-16 h-16 rounded-md object-cover flex-shrink-0">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800 text-sm">Sewa Gazebo</h4>
                                <p class="text-xs text-gray-500 mb-1">Max 4 orang (4 Unit)</p>
                                <div class="text-green-600 font-bold text-sm">Rp 50.000</div>
                            </div>
                        </div>

                        <!-- Item 2: Karaoke -->
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-100 last:border-0">
                            <img src="https://placehold.co/100x100/059669/ffffff?text=Mic" class="w-16 h-16 rounded-md object-cover flex-shrink-0">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800 text-sm">Karaoke Music</h4>
                                <p class="text-xs text-gray-500 mb-1">Sewa seharian</p>
                                <div class="text-green-600 font-bold text-sm">Rp 150.000</div>
                            </div>
                        </div>

                        <!-- Item 3: Karcis -->
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-100 last:border-0">
                            <div class="w-16 h-16 rounded-md bg-green-50 flex items-center justify-center flex-shrink-0 text-green-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800 text-sm">Karcis Masuk</h4>
                                <p class="text-xs text-gray-500 mb-1">Dewasa & Anak</p>
                                <div class="text-green-600 font-bold text-sm">Rp 5.000 <span class="text-xs text-gray-400 font-normal">/org</span></div>
                            </div>
                        </div>

                        <!-- Item 4: Pelampung -->
                        <div class="flex items-start gap-3 last:border-0">
                            <img src="https://placehold.co/100x100/34d399/ffffff?text=Ban" class="w-16 h-16 rounded-md object-cover flex-shrink-0">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800 text-sm">Sewa Pelampung</h4>
                                <p class="text-xs text-gray-500 mb-1">Tersedia di lokasi</p>
                                <div class="text-blue-500 text-xs font-medium cursor-pointer hover:underline">Hubungi Petugas</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection