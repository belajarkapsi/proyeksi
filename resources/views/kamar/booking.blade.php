@extends('layout.master')

@section('content')
  <div class="max-w-4xl w-full">
   <h1 class="text-center text-xl font-semibold mb-6">
  Lantai {{ $lantai ?? '—' }} | {{ $namaKamar ?? 'Kamar' }}
</h1>


    <div class="card bg-white p-8 md:p-10">
      <div class="md:flex gap-8">
        {{-- Form kiri --}}
        <div class="md:w-2/3">
          <form method="POST" action="{{ route('booking.store') }}" id="bookingForm">
            @csrf

           <!-- Hidden fields untuk kamar -->
            <input type="hidden" name="kamar_id" value="{{ $kamarId ?? '' }}">
            <input type="hidden" name="harga_per_hari" id="harga_per_hari" value="{{ $hargaInt ?? 0 }}">
            <input type="hidden" name="cabang_id" value="{{ $cabangId ?? '' }}">
            <input type="hidden" name="nama_kamar" value="{{ $namaKamar ?? '' }}">


            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama lengkap</label>
                <input name="nama_lengkap" required class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-200" placeholder="Enter your Name">
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih tanggal</label>
                <div class="flex gap-2">
                  <input type="date" name="tanggal" id="tanggal" required class="w-1/2 border rounded px-3 py-2 text-sm">
                  <select name="durasi" id="durasi" required class="w-1/2 border rounded px-3 py-2 text-sm">
                    <option value="1">Per Hari</option>
                    <option value="2">2 Hari</option>
                    <option value="3">3 Hari</option>
                    <option value="4">4 Hari</option>
                  </select>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                <input name="telepon" required class="w-full border rounded px-3 py-2 text-sm" placeholder="Enter your Number">
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input name="email" type="email" required class="w-full border rounded px-3 py-2 text-sm" placeholder="Enter your email">
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input name="username" class="w-full border rounded px-3 py-2 text-sm" placeholder="Enter your username">
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kota Asal</label>
                <input name="kota_asal" class="w-full border rounded px-3 py-2 text-sm" placeholder="Enter your Origin">
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Kamar</label>
                <input name="jumlah" id="jumlah" type="number" value="1" min="1" class="w-full border rounded px-3 py-2 text-sm">
              </div>

            </div>

            {{-- Error / success messages --}}
            @if(session('success'))
              <div class="mt-4 p-3 bg-green-50 text-green-700 rounded">{{ session('success') }}</div>
            @endif
            @if($errors->any())
              <div class="mt-4 p-3 bg-red-50 text-red-700 rounded">
                <ul class="list-disc pl-5">
                  @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            {{-- small spacer --}}
            <div class="mt-6 md:mt-8"></div>
          </form>
        </div>

        {{-- Ringkasan kanan --}}
        <div class="md:w-1/3">
          <div class="small-card-shadow bg-white p-4 rounded-md">
            <div class="flex items-start gap-3">
              {{-- thumbnail --}}
              <div class="w-24 h-20 overflow-hidden rounded-md bg-gray-100">
                @if(!empty($gambar))
                  <img src="{{ $gambar }}" alt="gambar kamar" class="thumb">
                @else
                  <img src="{{ $gambar ?? 'https://via.placeholder.com/400x300?text=Kamar' }}" alt="gambar kamar" class="thumb">
                @endif
              </div>

              <div class="flex-1 text-sm">
                <div class="font-medium"> {{ $namaKamar ?? 'Kamar 101' }} {{ $lantai ? '| Lantai '.$lantai : '' }} </div>
                <div class="text-gray-500 text-xs mt-1">Fasilitas: AC • Kasur</div>
                <div class="text-gray-600 text-xs mt-2">x <span id="jumlahText">1</span></div>
              </div>
            </div>

            <div class="mt-4 border-t pt-4">
              <div class="flex items-center justify-between text-sm">
                <div>Total</div>
                <div class="font-semibold">Rp <span id="totalText">0</span></div>
              </div>

              <button type="submit" form="bookingForm" class="mt-4 w-full bg-green-600 text-white py-2 rounded-full hover:bg-green-700">
                Ajukan Sewa
              </button>
            </div>

            <div class="mt-4 text-xs text-gray-400">
              Harga ditampilkan per hari.
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- bawah card: tombol submit juga bisa sini (opsional) --}}
  </div>
@endsection
  <script>
    // Ambil elemen & nilai awal dari blade
    const hargaPerHari = parseFloat(document.getElementById('harga_per_hari').value) || 0;
    const durasiEl = document.getElementById('durasi');
    const jumlahEl = document.getElementById('jumlah');
    const totalText = document.getElementById('totalText');
    const jumlahText = document.getElementById('jumlahText');

    function formatRupiah(number){
      // format simple tanpa library: 125000 -> 125.000
      return number.toLocaleString('id-ID', {maximumFractionDigits: 0});
    }

    function hitungTotal(){
      const durasi = parseInt(durasiEl.value) || 1;
      const jumlah = parseInt(jumlahEl.value) || 1;
      const total = hargaPerHari * durasi * jumlah;
      totalText.innerText = formatRupiah(total);
      jumlahText.innerText = jumlah;
    }

    // inisialisasi
    hitungTotal();

    // event listeners
    durasiEl.addEventListener('change', hitungTotal);
    jumlahEl.addEventListener('input', hitungTotal);
  </script>

