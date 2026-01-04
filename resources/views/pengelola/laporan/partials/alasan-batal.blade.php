@forelse($alasanBatal as $alasan)
    @php
        $totalCases = $alasanBatal->sum('total') ?: 1; // Hindari division by zero
    @endphp
    <div>
        <div class="flex justify-between text-sm mb-1">
            <span class="font-medium text-gray-700">"{{ Str::limit($alasan->alasan_batal, 50) }}"</span>
            <span class="font-bold text-red-600">{{ $alasan->total }} Kasus</span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-2">
            <div class="bg-red-400 h-2 rounded-full" style="width: {{ ($alasan->total / $totalCases) * 100 }}%"></div>
        </div>
    </div>
@empty
    <p class="text-center text-gray-400 py-8">Tidak ada data pembatalan. Kinerja bagus!</p>
@endforelse
