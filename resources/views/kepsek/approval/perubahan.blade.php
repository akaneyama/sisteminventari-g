@extends('layouts.app')
@section('title', 'Persetujuan Perubahan Data Barang')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Persetujuan Perubahan Data Barang</h2>
    <p class="text-sm text-gray-500 mt-1">Tinjau perubahan data barang yang diajukan oleh Admin.</p>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-xl shadow-sm text-sm font-medium">
        {{ session('success') }}
    </div>
@endif

<div class="space-y-5">
    @forelse($perubahans as $item)
    @php
        $barang = $item->barang;
        $labelMap = [
            'nama_barang'       => 'Nama Barang',
            'merk_type'         => 'Merk / Tipe',
            'kondisi'           => 'Kondisi',
            'id_lokasi'         => 'Lokasi (ID)',
            'id_kategori'       => 'Kategori (ID)',
            'jumlah_barang'     => 'Jumlah',
            'tahun_perolehan'   => 'Tahun Perolehan',
            'kode_inventaris'   => 'Kode Inventaris',
        ];
    @endphp

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <div class="flex items-center">
                @if(isset($barang) && $barang->foto_barang)
                    <div class="relative group cursor-pointer mr-4 flex-shrink-0" onclick="openImageModal('{{ asset('storage/' . $barang->foto_barang) }}')" title="Lihat Foto">
                        <img class="h-12 w-12 rounded-lg object-cover border border-gray-200" src="{{ asset('storage/' . $barang->foto_barang) }}" alt="Foto">
                        <div class="absolute inset-0 bg-gray-900/50 hidden group-hover:flex items-center justify-center rounded-lg transition-all duration-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </div>
                    </div>
                @else
                    <div class="h-12 w-12 rounded-lg bg-gray-100 flex items-center justify-center mr-4 border border-gray-200 text-gray-400 text-xs font-medium">No Pic</div>
                @endif
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Kode: {{ $barang->kode_inventaris ?? 'N/A' }}</p>
                    <p class="text-base font-bold text-gray-800">{{ $barang->nama_barang ?? 'Barang Tidak Ditemukan' }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Diajukan: {{ $item->created_at->translatedFormat('d F Y, H:i') }}</p>
                </div>
            </div>
            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Keputusan</span>
        </div>

        <div class="px-6 py-4">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class="text-left pb-3 text-xs font-bold text-gray-400 uppercase tracking-wider w-1/4">Field</th>
                        <th class="text-left pb-3 text-xs font-bold text-red-400 uppercase tracking-wider w-5/12">Data Lama</th>
                        <th class="text-left pb-3 text-xs font-bold text-green-500 uppercase tracking-wider w-5/12">Data Baru (Usulan)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($item->data_baru as $field => $nilBaru)
                    <tr>
                        <td class="py-2.5 pr-4 text-gray-500 font-medium text-xs">{{ $labelMap[$field] ?? $field }}</td>
                        <td class="py-2.5 pr-4">
                            <span class="px-2 py-0.5 bg-red-50 text-red-700 rounded-md font-mono text-xs line-through">{{ $item->data_lama[$field] ?? '-' }}</span>
                        </td>
                        <td class="py-2.5">
                            <span class="px-2 py-0.5 bg-green-50 text-green-700 rounded-md font-mono text-xs font-bold">{{ $nilBaru ?? '-' }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
            <button type="button" onclick="bukaModalTolakPerubahan({{ $item->id_perubahan }}, '{{ addslashes($barang->nama_barang ?? '') }}')"
                class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 hover:bg-red-200 rounded-xl text-sm font-bold transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Tolak
            </button>
            <form action="{{ route('kepsek.approval.perubahan.approve', $item->id_perubahan) }}" method="POST"
                onsubmit="return confirm('Anda yakin menyetujui perubahan data ini?');">
                @csrf @method('PATCH')
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-bold transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Setujui & Terapkan
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl border border-gray-100 px-6 py-16 text-center shadow-sm">
        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        <p class="text-base font-medium text-gray-600">Tidak ada pengajuan perubahan data yang menunggu.</p>
    </div>
    @endforelse
</div>

{{-- Modal Tolak Perubahan --}}
<div id="modalTolakPerubahan" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden">
        <form id="formTolakPerubahan" method="POST">
            @csrf @method('PATCH')
            <div class="px-6 py-4 border-b border-gray-100 bg-red-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-red-800">Tolak Perubahan Data</h3>
                <button type="button" onclick="tutupModalTolakPerubahan()" class="text-red-400 hover:text-red-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600 mb-4">Barang: <span id="namaBarangTolakPerubahan" class="font-bold text-gray-900"></span></p>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea name="alasan_penolakan" rows="3" required placeholder="Tuliskan alasan penolakan..."
                    class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:ring-red-500 focus:border-red-500 transition-colors"></textarea>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
                <button type="button" onclick="tutupModalTolakPerubahan()" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold">Kirim Penolakan</button>
            </div>
        </form>
    </div>
</div>

<script>
function bukaModalTolakPerubahan(id, nama) {
    document.getElementById('modalTolakPerubahan').classList.remove('hidden');
    document.getElementById('namaBarangTolakPerubahan').innerText = nama;
    document.getElementById('formTolakPerubahan').action = `/kepsek/approval/perubahan/${id}/reject`;
}
function tutupModalTolakPerubahan() {
    document.getElementById('modalTolakPerubahan').classList.add('hidden');
}
</script>
@endsection
