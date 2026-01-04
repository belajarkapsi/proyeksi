@forelse($topKamar as $kamar)
<tr>
    <td class="px-3 py-3 font-medium text-gray-900">
        {{ $kamar->no_kamar }} <span class="text-gray-400 font-normal">({{ $kamar->tipe_kamar }})</span>
    </td>
    <td class="px-3 py-3 text-center">{{ $kamar->total_book }}x</td>
    <td class="px-3 py-3 text-right">Rp {{ number_format($kamar->omzet, 0, ',', '.') }}</td>
</tr>
@empty
<tr><td colspan="3" class="text-center py-4 text-gray-400">Belum ada kamar yang terpesan.</td></tr>
@endforelse
