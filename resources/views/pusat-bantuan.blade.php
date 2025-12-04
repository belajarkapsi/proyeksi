@extends('layout.master')
@section('title', 'Pusat Bantuan - ' . config('app.name'))

@section('content')

<!-- Background Decoration (Optional) -->
<div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
    <div class="absolute -top-20 -left-20 w-96 h-96 bg-green-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
    <div class="absolute top-0 -right-20 w-96 h-96 bg-blue-50 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
</div>

<div class="container mx-auto px-4 py-16 max-w-5xl min-h-screen">
    
    <!-- Header Section -->
    <div class="text-center mb-16 relative">
        <span class="text-green-600 font-bold tracking-wider uppercase text-sm mb-2 block">Support Center</span>
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 font-title mb-4 leading-tight">
            Bagaimana kami bisa <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-teal-500">membantu?</span>
        </h1>
        <p class="mt-4 text-lg text-gray-500 font-content max-w-2xl mx-auto">
            Temukan jawaban cepat untuk pertanyaan umum atau hubungi tim support kami jika masalah berlanjut.
        </p>

        <!-- Search Bar -->
        <div class="mt-10 max-w-2xl mx-auto relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-6 w-6 text-gray-400 group-focus-within:text-green-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input
                type="text"
                id="searchInput"
                placeholder="Cari kata kunci (contoh: login, pembayaran)..."
                class="block w-full pl-12 pr-4 py-4 bg-white border-0 rounded-2xl text-gray-900 shadow-[0_8px_30px_rgb(0,0,0,0.08)] ring-1 ring-gray-100 placeholder:text-gray-400 focus:ring-2 focus:ring-green-500 focus:outline-none transition-all duration-300 font-content text-lg"
            >
        </div>
    </div>

    <!-- FAQ Container -->
    <div class="grid gap-6 md:gap-8 max-w-3xl mx-auto" id="faqContainer">
        
        <!-- Masalah Akun -->
        <div class="faq-item group bg-white rounded-2xl shadow-sm hover:shadow-md border border-gray-100 transition-all duration-300 overflow-hidden">
            <button class="faq-toggle w-full text-left p-6 md:p-8 flex justify-between items-center focus:outline-none">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-50 rounded-xl text-green-600 group-hover:bg-green-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <span class="font-bold text-xl text-gray-800 font-title">Masalah Akun</span>
                </div>
                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center transition-transform duration-300 icon-arrow text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </button>
            <div class="faq-content hidden bg-white px-6 pb-6 md:px-8 md:pb-8">
                <div class="pt-2 border-t border-dashed border-gray-100 space-y-2">
                    <!-- Sub Items -->
                    <div class="py-2">
                        <button class="faq-question w-full text-left font-medium text-gray-700 hover:text-green-600 flex items-center justify-between group/sub py-2 transition-colors">
                            <span>Tidak bisa login</span>
                            <span class="text-gray-300 group-hover/sub:text-green-500 text-xl font-light leading-none">+</span>
                        </button>
                        <div class="faq-answer hidden mt-3 p-4 bg-gray-50 rounded-lg text-gray-600 text-sm leading-relaxed border-l-4 border-green-500 animate-fadeIn">
                            Pastikan email dan password benar. Jika lupa password, gunakan fitur "Lupa Password". Jika tetap tidak bisa, hubungi admin.
                        </div>
                    </div>
                    
                    <div class="py-2">
                        <button class="faq-question w-full text-left font-medium text-gray-700 hover:text-green-600 flex items-center justify-between group/sub py-2 transition-colors">
                            <span>Email verifikasi tidak diterima</span>
                            <span class="text-gray-300 group-hover/sub:text-green-500 text-xl font-light leading-none">+</span>
                        </button>
                        <div class="faq-answer hidden mt-3 p-4 bg-gray-50 rounded-lg text-gray-600 text-sm leading-relaxed border-l-4 border-green-500 animate-fadeIn">
                            Cek folder spam atau promosi. Jika tidak ada, coba kirim ulang verifikasi dari halaman profil.
                        </div>
                    </div>

                    <div class="py-2">
                        <button class="faq-question w-full text-left font-medium text-gray-700 hover:text-green-600 flex items-center justify-between group/sub py-2 transition-colors">
                            <span>Role salah, fitur hilang</span>
                            <span class="text-gray-300 group-hover/sub:text-green-500 text-xl font-light leading-none">+</span>
                        </button>
                        <div class="faq-answer hidden mt-3 p-4 bg-gray-50 rounded-lg text-gray-600 text-sm leading-relaxed border-l-4 border-green-500 animate-fadeIn">
                            Hubungi admin untuk verifikasi ulang role akun kamu.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pemesanan -->
        <div class="faq-item group bg-white rounded-2xl shadow-sm hover:shadow-md border border-gray-100 transition-all duration-300 overflow-hidden">
            <button class="faq-toggle w-full text-left p-6 md:p-8 flex justify-between items-center focus:outline-none">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-50 rounded-xl text-blue-600 group-hover:bg-blue-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <span class="font-bold text-xl text-gray-800 font-title">Pemesanan</span>
                </div>
                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center transition-transform duration-300 icon-arrow text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </button>
            <div class="faq-content hidden bg-white px-6 pb-6 md:px-8 md:pb-8">
                <div class="pt-2 border-t border-dashed border-gray-100 space-y-2">
                    <div class="py-2">
                        <button class="faq-question w-full text-left font-medium text-gray-700 hover:text-green-600 flex items-center justify-between group/sub py-2 transition-colors">
                            <span>Double booking terdeteksi</span>
                            <span class="text-gray-300 group-hover/sub:text-green-500 text-xl font-light leading-none">+</span>
                        </button>
                        <div class="faq-answer hidden mt-3 p-4 bg-gray-50 rounded-lg text-gray-600 text-sm leading-relaxed border-l-4 border-blue-500 animate-fadeIn">
                            Sistem otomatis mendeteksi jika kamar sudah dipesan. Coba pilih kamar lain atau hubungi admin jika kamu yakin ini kesalahan.
                        </div>
                    </div>
                    <div class="py-2">
                        <button class="faq-question w-full text-left font-medium text-gray-700 hover:text-green-600 flex items-center justify-between group/sub py-2 transition-colors">
                            <span>Pesanan hilang dari riwayat</span>
                            <span class="text-gray-300 group-hover/sub:text-green-500 text-xl font-light leading-none">+</span>
                        </button>
                        <div class="faq-answer hidden mt-3 p-4 bg-gray-50 rounded-lg text-gray-600 text-sm leading-relaxed border-l-4 border-blue-500 animate-fadeIn">
                            Cek halaman riwayat pesanan. Jika tidak muncul, coba login kembali atau hubungi admin.
                        </div>
                    </div>
                    <div class="py-2">
                        <button class="faq-question w-full text-left font-medium text-gray-700 hover:text-green-600 flex items-center justify-between group/sub py-2 transition-colors">
                            <span>Kesalahan tanggal check-in</span>
                            <span class="text-gray-300 group-hover/sub:text-green-500 text-xl font-light leading-none">+</span>
                        </button>
                        <div class="faq-answer hidden mt-3 p-4 bg-gray-50 rounded-lg text-gray-600 text-sm leading-relaxed border-l-4 border-blue-500 animate-fadeIn">
                            Pastikan tanggal check-in dan check-out benar. Tanggal check-out harus setelah check-in.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pembayaran -->
        <div class="faq-item group bg-white rounded-2xl shadow-sm hover:shadow-md border border-gray-100 transition-all duration-300 overflow-hidden">
            <button class="faq-toggle w-full text-left p-6 md:p-8 flex justify-between items-center focus:outline-none">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-purple-50 rounded-xl text-purple-600 group-hover:bg-purple-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <span class="font-bold text-xl text-gray-800 font-title">Pembayaran</span>
                </div>
                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center transition-transform duration-300 icon-arrow text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </button>
            <div class="faq-content hidden bg-white px-6 pb-6 md:px-8 md:pb-8">
                <div class="pt-2 border-t border-dashed border-gray-100 space-y-2">
                    <div class="py-2">
                        <button class="faq-question w-full text-left font-medium text-gray-700 hover:text-green-600 flex items-center justify-between group/sub py-2 transition-colors">
                            <span>Pembayaran belum terverifikasi</span>
                            <span class="text-gray-300 group-hover/sub:text-green-500 text-xl font-light leading-none">+</span>
                        </button>
                        <div class="faq-answer hidden mt-3 p-4 bg-gray-50 rounded-lg text-gray-600 text-sm leading-relaxed border-l-4 border-purple-500 animate-fadeIn">
                            Proses verifikasi bisa memakan waktu hingga 1x24 jam. Cek kembali bukti pembayaran yang dikirim.
                        </div>
                    </div>
                    <div class="py-2">
                        <button class="faq-question w-full text-left font-medium text-gray-700 hover:text-green-600 flex items-center justify-between group/sub py-2 transition-colors">
                            <span>Gagal upload bukti bayar</span>
                            <span class="text-gray-300 group-hover/sub:text-green-500 text-xl font-light leading-none">+</span>
                        </button>
                        <div class="faq-answer hidden mt-3 p-4 bg-gray-50 rounded-lg text-gray-600 text-sm leading-relaxed border-l-4 border-purple-500 animate-fadeIn">
                            Pastikan ukuran file tidak lebih dari 2MB dan formatnya JPG/PNG. Coba refresh halaman.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kontak Admin Card -->
        <div class="faq-item mt-8 bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl shadow-xl overflow-hidden text-white relative">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white opacity-5"></div>
            
            <button class="faq-toggle w-full text-left p-8 flex justify-between items-center focus:outline-none z-10 relative">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white/10 rounded-xl text-green-400 backdrop-blur-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <span class="font-bold text-xl text-white font-title block">Masih butuh bantuan?</span>
                        <span class="text-sm text-gray-400 font-light">Klik untuk melihat kontak admin</span>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center transition-transform duration-300 icon-arrow text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </button>
            <div class="faq-content hidden p-8 pt-0 bg-transparent border-t border-white/10">
                <p class="mb-6 text-gray-300 leading-relaxed">Tim support kami siap membantu menyelesaikan kendala yang kamu alami. Hubungi kami melalui kanal berikut:</p>
                <div class="grid md:grid-cols-2 gap-4">
                    <a href="tel:+6281234567890" class="flex items-center p-4 rounded-xl bg-white/5 hover:bg-white/10 transition-all border border-white/5 hover:border-green-500/50 group/contact">
                        <div class="p-2 bg-green-500/20 rounded-lg text-green-400 mr-4 group-hover/contact:text-green-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <div>
                            <span class="block text-xs text-gray-400 uppercase tracking-wide">Telepon / WA</span>
                            <span class="font-bold text-white">+62 812-3456-7890</span>
                        </div>
                    </a>
                    <a href="mailto:admin@contoh.com" class="flex items-center p-4 rounded-xl bg-white/5 hover:bg-white/10 transition-all border border-white/5 hover:border-green-500/50 group/contact">
                        <div class="p-2 bg-blue-500/20 rounded-lg text-blue-400 mr-4 group-hover/contact:text-blue-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <span class="block text-xs text-gray-400 uppercase tracking-wide">Email Support</span>
                            <span class="font-bold text-white">admin@contoh.com</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-20 text-center">
        <p class="text-gray-400 text-sm">
            &copy; {{ date('Y') }} <span class="font-bold text-gray-600">{{ config('app.name') }}</span>. All rights reserved.
        </p>
    </div>
</div>

<style>
    /* Custom Animation for Blob Background */
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    /* Simple Fade In for Answers */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out forwards;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Logic Accordion Utama (Category)
    document.querySelectorAll('.faq-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const content = button.nextElementSibling;
            const iconArrow = button.querySelector('.icon-arrow');
            
            // Toggle visibility
            content.classList.toggle('hidden');
            
            // Rotate Arrow Animation
            if (content.classList.contains('hidden')) {
                iconArrow.style.transform = 'rotate(0deg)';
                // Optional: Ubah warna bg icon saat tutup
                iconArrow.classList.remove('bg-green-100', 'text-green-600');
                iconArrow.classList.add('bg-gray-50', 'text-gray-400');
            } else {
                iconArrow.style.transform = 'rotate(180deg)';
                // Optional: Ubah warna bg icon saat buka
                iconArrow.classList.remove('bg-gray-50', 'text-gray-400');
                iconArrow.classList.add('bg-green-100', 'text-green-600');
            }
        });
    });

    // 2. Logic Accordion Pertanyaan Detail (Nested)
    document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', () => {
            const answer = question.nextElementSibling;
            const iconPlus = question.querySelector('span:last-child');
            
            // Logic "Accordion": Tutup yang lain dalam kategori yang sama (Optional, based on original logic)
            // Note: Kode asli menutup SEMUA jawaban di halaman, kita pertahankan logic itu tapi 
            // di sini kita hanya manipulasi class hidden.
            
            const isCurrentlyOpen = !answer.classList.contains('hidden');

            // Reset semua (tutup semua)
            document.querySelectorAll('.faq-answer').forEach(a => a.classList.add('hidden'));
            document.querySelectorAll('.faq-question span:last-child').forEach(s => {
                s.textContent = '+';
                s.parentElement.classList.remove('text-green-600');
            });

            // Jika tadi tertutup, sekarang buka
            if (!isCurrentlyOpen) {
                answer.classList.remove('hidden');
                iconPlus.textContent = '-';
                question.classList.add('text-green-600');
            }
        });
    });

    // 3. Logic Search
    const searchInput = document.getElementById('searchInput');
    const faqItems = document.querySelectorAll('.faq-item');

    searchInput.addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();

        faqItems.forEach(item => {
            // Cari text di dalam item (termasuk judul kategori dan pertanyaan didalamnya)
            const text = item.textContent.toLowerCase();
            
            if (text.includes(searchTerm)) {
                item.style.display = 'block';
                
                // Optional Enhancement: Jika user mencari sesuatu, otomatis buka accordionnya
                if (searchTerm.length > 2) {
                    const content = item.querySelector('.faq-content');
                    const iconArrow = item.querySelector('.icon-arrow');
                    if (content && content.classList.contains('hidden')) {
                        content.classList.remove('hidden');
                        if(iconArrow) iconArrow.style.transform = 'rotate(180deg)';
                    }
                }
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>

@endsection