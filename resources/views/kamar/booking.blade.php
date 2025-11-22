@extends('layout.master')
@section('title', 'Konfirmasi Pesanan')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
  <div class="max-w-6xl mx-auto">
    
    <div class="mb-8 text-center sm:text-left">
      <h1 class="text-3xl font-bold text-gray-900">Konfirmasi Pesanan</h1>
      <p class="mt-2 text-sm text-gray-600">Atur durasi sewa spesifik untuk setiap kamar.</p>
    </div>

    <div class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start">
      
      {{-- KOLOM KIRI: FORM DATA DIRI --}}
      <div class="lg:col-span-7">
        <form id="bookingForm" action="{{ route('booking.store') }}" method="POST" class="bg-white shadow-xl rounded-2xl overflow-hidden">
          @csrf
          
          {{-- Input Hidden ID Kamar --}}
          @foreach($rooms as $room)
             <input type="hidden" name="kamar_ids[]" value="{{ $room->id }}">
          @endforeach
          
          <div class="p-6 sm:p-8 space-y-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900 border-b pb-2">Informasi Penyewa</h3>

            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
              {{-- Nama --}}
              <div class="sm:col-span-6">
                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm border p-2 focus:ring-green-500 focus:border-green-500" placeholder="Sesuai KTP">
              </div>

              {{-- Telepon --}}
              <div class="sm:col-span-3">
                <label class="block text-sm font-medium text-gray-700">No. WhatsApp</label>
                <input type="number" name="telepon" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm border p-2 focus:ring-green-500 focus:border-green-500">
              </div>

              {{-- Email --}}
              <div class="sm:col-span-3">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm border p-2 focus:ring-green-500 focus:border-green-500">
              </div>

              {{-- Tanggal Mulai --}}
              <div class="sm:col-span-6">
                 <label class="block text-sm font-medium text-gray-700">Tanggal Mulai Check-in</label>
                 <input type="date" name="tanggal" id="tanggal" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm border p-2 focus:ring-green-500 focus:border-green-500">
              </div>
            </div>
          </div>
          
          {{-- Tombol Mobile --}}
          <div class="bg-gray-50 px-6 py-4 lg:hidden">
            <button type="submit" class="w-full rounded-xl bg-green-600 px-6 py-3 text-white font-bold shadow hover:bg-green-700">
              Bayar Sekarang
            </button>
          </div>
        </form>
      </div>

      {{-- KOLOM KANAN: DURASI DINAMIS --}}
      <div class="lg:col-span-5 mt-8 lg:mt-0">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden sticky top-8 border border-gray-100">
          
          <div class="p-6 bg-green-50 border-b border-green-100">
            <h2 class="text-lg font-semibold text-green-900 flex items-center gap-2">
              <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" /><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" /></svg>
              Rincian & Durasi Sewa
            </h2>
          </div>

          {{-- CONTAINER LIST KAMAR --}}
          <div class="p-6 max-h-[500px] overflow-y-auto custom-scrollbar space-y-6">
            
            @foreach($rooms as $room)
            {{-- Wrapper per Item (Penting untuk Scoping JS) --}}
            <div class="room-item flex flex-col gap-3 pb-4 border-b border-gray-100 last:border-0" 
                 data-price="{{ $room->harga }}">
               
               {{-- Info Kamar --}}
               <div class="flex gap-4 items-center">
                   <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-lg border border-gray-200">
                     <img src="{{ asset($room->image ?? 'images/kamar.jpg') }}" class="h-full w-full object-cover">
                   </div>
                   <div class="flex-1">
                     <p class="text-sm font-bold text-gray-900">Kamar No. {{ $room->no_kamar }}</p>
                     <p class="text-xs text-green-600 font-semibold">
                        Rp{{ number_format($room->harga, 0, ',', '.') }} /malam
                     </p>
                   </div>
               </div>

               {{-- AREA KONTROL DURASI --}}
               <div class="bg-gray-50 p-3 rounded-lg">
                  <div class="flex items-center justify-between mb-2">
                      <label class="text-xs font-medium text-gray-600">Pilih Satuan:</label>
                      {{-- Selector Satuan (Hari/Minggu/dll) --}}
                      <select class="unit-select text-xs border-gray-300 rounded shadow-sm focus:ring-green-500 focus:border-green-500 py-1">
                          <option value="1">Hari</option>
                          <option value="7">Minggu</option>
                          <option value="30">Bulan</option>
                          <option value="365">Tahun</option>
                      </select>
                  </div>

                  <div class="flex items-center justify-between bg-white border border-gray-200 rounded-md p-1">
                      {{-- Tombol Kurang --}}
                      <button type="button" class="btn-minus w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-red-100 text-gray-600 hover:text-red-600 rounded transition-colors font-bold">
                          -
                      </button>

                      {{-- Tampilan Angka (Hanya Visual) --}}
                      <span class="display-amount font-bold text-gray-800 text-sm">1</span>

                      {{-- Tombol Tambah --}}
                      <button type="button" class="btn-plus w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-green-100 text-gray-600 hover:text-green-600 rounded transition-colors font-bold">
                          +
                      </button>
                  </div>

                  {{-- INPUT HIDDEN YANG DIKIRIM KE BACKEND (Total Hari) --}}
                  {{-- Logic JS akan mengisi value ini: (Satuan * Jumlah) --}}
                  <input type="hidden" name="durasi[{{ $room->id }}]" form="bookingForm" class="final-days-input" value="1">
               </div>

               {{-- Subtotal Per Kamar --}}
               <div class="flex justify-between items-center text-xs pt-1">
                  <span class="text-gray-500 detail-text">1 Hari</span>
                  <span class="font-bold text-gray-800 subtotal-display">Rp{{ number_format($room->harga, 0, ',', '.') }}</span>
               </div>

            </div>
            @endforeach

          </div>

          {{-- Total Bayar --}}
          <div class="bg-gray-100 p-6 border-t border-gray-200">
             <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span>Total Kamar</span>
                <span class="font-medium">{{ $rooms->count() }} Unit</span>
             </div>
             
             <div class="flex justify-between items-end pt-2 border-t border-gray-300">
                <span class="text-base font-bold text-gray-900">Total Bayar</span>
                <span class="text-2xl font-bold text-green-700" id="grand_total">Rp0</span>
             </div>

             <button type="submit" form="bookingForm" class="w-full mt-6 hidden lg:flex justify-center items-center rounded-xl bg-green-600 px-6 py-4 text-base font-bold text-white shadow-lg hover:bg-green-700 transition-all transform hover:-translate-y-0.5">
                Konfirmasi & Bayar
             </button>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

{{-- JavaScript Vanilla --}}
<script>
  document.addEventListener('DOMContentLoaded', function() {
      
      // Helper: Format Rupiah
      const formatRupiah = (num) => {
          return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);
      };

      // Fungsi Hitung Grand Total (Semua Kamar)
      const updateGrandTotal = () => {
          let total = 0;
          document.querySelectorAll('.room-item').forEach(item => {
              const price = parseFloat(item.getAttribute('data-price'));
              const days = parseInt(item.querySelector('.final-days-input').value);
              total += price * days;
          });
          document.getElementById('grand_total').innerText = formatRupiah(total);
      };

      // Fungsi Update Per Kamar (Saat tombol +/- atau ganti satuan diklik)
      const updateRoom = (roomElement) => {
          // Ambil elemen-elemen dalam kartu ini
          const unitVal = parseInt(roomElement.querySelector('.unit-select').value); // 1, 7, 30, 365
          const unitText = roomElement.querySelector('.unit-select').options[roomElement.querySelector('.unit-select').selectedIndex].text;
          
          // Ambil jumlah visual
          let amount = parseInt(roomElement.querySelector('.display-amount').innerText);

          // Hitung Total Hari
          const totalDays = amount * unitVal;
          
          // Masukkan ke Hidden Input (untuk backend)
          roomElement.querySelector('.final-days-input').value = totalDays;

          // Update Tampilan Subtotal
          const price = parseFloat(roomElement.getAttribute('data-price'));
          const subtotal = price * totalDays;
          roomElement.querySelector('.subtotal-display').innerText = formatRupiah(subtotal);

          // Update Teks Detail (Contoh: "2 Minggu")
          roomElement.querySelector('.detail-text').innerText = `${amount} ${unitText}`;

          // Recalculate Grand Total
          updateGrandTotal();
      };

      // Setup Event Listeners untuk setiap kartu kamar
      document.querySelectorAll('.room-item').forEach(room => {
          const btnMinus = room.querySelector('.btn-minus');
          const btnPlus = room.querySelector('.btn-plus');
          const display = room.querySelector('.display-amount');
          const unitSelect = room.querySelector('.unit-select');

          // Event: Klik Tombol Minus
          btnMinus.addEventListener('click', () => {
              let current = parseInt(display.innerText);
              if (current > 1) {
                  display.innerText = current - 1;
                  updateRoom(room);
              }
          });

          // Event: Klik Tombol Plus
          btnPlus.addEventListener('click', () => {
              let current = parseInt(display.innerText);
              display.innerText = current + 1;
              updateRoom(room);
          });

          // Event: Ganti Satuan (Hari -> Minggu -> Bulan)
          unitSelect.addEventListener('change', () => {
              // Reset jumlah ke 1 saat ganti satuan agar tidak bingung
              // (Opsional, bisa dihapus jika ingin angka tetap bertahan)
              display.innerText = 1; 
              updateRoom(room);
          });
          
          // Inisialisasi pertama kali
          updateRoom(room);
      });

  });
</script>

<style>
  .custom-scrollbar::-webkit-scrollbar { width: 6px; }
  .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
  .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
</style>
@endsection