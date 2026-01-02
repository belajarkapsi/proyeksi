@forelse($topServices as $svc)
<tr>
    <td class="px-3 py-3 font-medium text-gray-900">{{ $svc->service->name ?? 'Deleted' }}</td>
    <td class="px-3 py-3 text-center">{{ $svc->total_qty }}</td>
    <td class="px-3 py-3 text-right">Rp {{ number_format($svc->total_omzet, 0, ',', '.') }}</td>
</tr>
@empty
<tr><td colspan="3" class="text-center py-4 text-gray-400">Belum ada layanan yang terpesan.</td></tr>
@endforelse
