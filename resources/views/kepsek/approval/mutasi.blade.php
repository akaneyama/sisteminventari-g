@extends('layouts.app')
@section('title', 'Persetujuan Mutasi Barang')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Persetujuan Mutasi Barang</h2>
    <p class="text-sm text-gray-500 mt-1">Tinjau pengajuan perpindahan atau perubahan kondisi barang dari Admin.</p>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-xl shadow-sm text-sm font-medium">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Barang</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis Mutasi</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Detail Perubahan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan Admin</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($mutasis as $mutasi)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-start">
                            @if($mutasi->barang->foto_barang)
                                <div class="relative group cursor-pointer mr-3 mt-1 flex-shrink-0" onclick="openImageModal('{{ asset('storage/' . $mutasi->barang->foto_barang) }}')" title="Lihat Foto">
                                    <img class="h-12 w-12 rounded-lg object-cover border border-gray-200" src="{{ asset('storage/' . $mutasi->barang->foto_barang) }}" alt="Foto">
                                    <div class="absolute inset-0 bg-gray-900/50 hidden group-hover:flex items-center justify-center rounded-lg transition-all duration-200">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </div>
                                </div>
                            @else
                                <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center mr-3 border border-gray-200 text-gray-400 text-xs font-medium">No Pic</div>
                            @endif
                            <div>
                                <div class="text-sm font-bold text-blue-700">{{ $mutasi->barang->kode_inventaris ?? '-' }}</div>
                                <div class="text-sm text-gray-900">{{ $mutasi->barang->nama_barang ?? 'Barang tidak ditemukan' }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $mutasi->jumlah }} Unit &bull; {{ $mutasi->tanggal_mutasi }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $jenisColor = match($mutasi->jenis_mutasi) {
                                'Pindah Lokasi' => 'bg-blue-100 text-blue-800',
                                'Ubah Status' => 'bg-orange-100 text-orange-800',
                                'Penghapusan' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-700'
                            };
                        @endphp
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $jenisColor }}">{{ $mutasi->jenis_mutasi }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        @if($mutasi->jenis_mutasi === 'Pindah Lokasi')
                            <div class="flex items-center gap-2">
                                <span class="text-xs bg-red-50 text-red-600 px-2 py-1 rounded-md">{{ $mutasi->lokasiAsal->nama_ruangan ?? 'Lokasi lama' }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                <span class="text-xs bg-green-50 text-green-600 px-2 py-1 rounded-md font-bold">{{ $mutasi->lokasiTujuan->nama_ruangan ?? 'Lokasi tujuan' }}</span>
                            </div>
                        @elseif($mutasi->jenis_mutasi === 'Ubah Status')
                            <div class="flex items-center gap-2">
                                <span class="text-xs bg-red-50 text-red-600 px-2 py-1 rounded-md">{{ $mutasi->kondisi_sebelum }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                <span class="text-xs bg-green-50 text-green-600 px-2 py-1 rounded-md font-bold">{{ $mutasi->kondisi_sesudah }}</span>
                            </div>
                        @else
                            <span class="text-xs text-gray-500">Barang akan dihapuskan dari sistem</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                        <p class="text-xs italic text-gray-500">{{ $mutasi->keterangan ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end gap-2">
                            <form action="{{ route('kepsek.approval.mutasi.approve', $mutasi->id_mutasi) }}" method="POST"
                                onsubmit="return confirm('Setujui mutasi ini? Perubahan akan langsung diterapkan ke inventaris.');">
                                @csrf @method('PATCH')
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 hover:bg-green-200 rounded-lg text-xs font-bold transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Setujui
                                </button>
                            </form>
                            <button type="button" onclick="bukaModalTolakMutasi({{ $mutasi->id_mutasi }}, '{{ addslashes($mutasi->barang->nama_barang ?? '') }}')"
                                class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg text-xs font-bold transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Tolak
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        <p class="text-base font-medium text-gray-600">Tidak ada pengajuan mutasi yang menunggu persetujuan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tolak Mutasi --}}
<div id="modalTolakMutasi" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden">
        <form id="formTolakMutasi" method="POST">
            @csrf @method('PATCH')
            <div class="px-6 py-4 border-b border-gray-100 bg-red-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-red-800">Tolak Mutasi Barang</h3>
                <button type="button" onclick="tutupModalTolakMutasi()" class="text-red-400 hover:text-red-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600 mb-4">Barang: <span id="namaBarangTolakMutasi" class="font-bold text-gray-900"></span></p>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea name="alasan_penolakan" rows="3" required placeholder="Tuliskan alasan penolakan mutasi..."
                    class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:ring-red-500 focus:border-red-500 transition-colors"></textarea>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
                <button type="button" onclick="tutupModalTolakMutasi()" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold">Kirim Penolakan</button>
            </div>
        </form>
    </div>
</div>

<script>
function bukaModalTolakMutasi(id, nama) {
    document.getElementById('modalTolakMutasi').classList.remove('hidden');
    document.getElementById('namaBarangTolakMutasi').innerText = nama;
    document.getElementById('formTolakMutasi').action = `/kepsek/approval/mutasi/${id}/reject`;
}
function tutupModalTolakMutasi() {
    document.getElementById('modalTolakMutasi').classList.add('hidden');
}
</script>
@endsection
