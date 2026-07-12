@extends('layouts.app')
@section('title', 'Persetujuan Pengadaan Aset')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Persetujuan Pengadaan Aset</h2>
        <p class="text-sm text-gray-500 mt-1">Tinjau usulan pengadaan barang baru dari Admin.</p>
    </div>
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
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Data Barang</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Sumber/Tahun</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Supplier</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi Keputusan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($pengadaans as $item)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-start">
                            @if($item->foto_barang)
                                <div class="relative group cursor-pointer mr-3 mt-1 flex-shrink-0" onclick="openImageModal('{{ asset('storage/' . $item->foto_barang) }}')" title="Lihat Foto">
                                    <img class="h-12 w-12 rounded-lg object-cover border border-gray-200" src="{{ asset('storage/' . $item->foto_barang) }}" alt="Foto">
                                    <div class="absolute inset-0 bg-gray-900/50 hidden group-hover:flex items-center justify-center rounded-lg transition-all duration-200">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </div>
                                </div>
                            @else
                                <div class="h-12 w-12 rounded-lg bg-gray-100 flex items-center justify-center mr-3 border border-gray-200 text-gray-400 text-xs font-medium mt-1">No Pic</div>
                            @endif
                            <div>
                                <div class="text-sm font-bold text-blue-700">{{ $item->kode_inventaris }}</div>
                                <div class="text-sm text-gray-900">{{ $item->nama_barang }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $item->merk_type }} &bull; {{ $item->jumlah_barang }} Unit</div>
                                <div class="text-xs text-gray-400 mt-1">Rencana Lokasi: {{ $item->lokasi->nama_ruangan ?? '-' }}</div>
                                <div class="text-xs text-gray-400 sm:hidden">Supplier: {{ $item->supplier->nama_supplier ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        <span class="font-medium">{{ $item->sumberDana->nama_sumber_dana ?? '-' }}</span><br>
                        Tahun: {{ $item->tahun_perolehan }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700 hidden sm:table-cell">
                        {{ $item->supplier->nama_supplier ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            <button type="button" onclick="bukaModalSetujui({{ $item->id_barang }}, '{{ $item->nama_barang }}', {{ $item->jumlah_barang }})" class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 hover:bg-green-200 rounded-lg text-xs font-bold transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Setujui
                            </button>
                            <button type="button" onclick="bukaModalTolak({{ $item->id_barang }}, '{{ $item->nama_barang }}')" class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg text-xs font-bold transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Tolak
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <p class="text-base font-medium text-gray-600">Belum ada usulan pengadaan barang baru.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">
        {{ $pengadaans->withQueryString()->links() }}
    </div>
</div>

{{-- Modal Tolak Pengadaan --}}
<div id="modalTolak" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden">
        <form id="formTolak" method="POST">
            @csrf @method('PATCH')
            <div class="px-6 py-4 border-b border-gray-100 bg-red-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-red-800">Tolak Pengajuan Barang</h3>
                <button type="button" onclick="tutupModalTolak()" class="text-red-400 hover:text-red-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="p-6">
                <p class="text-sm font-medium text-gray-600 mb-4">Anda akan menolak pengadaan: <br><span id="namaBarangTolak" class="font-bold text-gray-900 text-lg"></span></p>
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                    <textarea name="alasan_penolakan" rows="3" required placeholder="Tuliskan alasan penolakan agar admin mengetahuinya..." class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:ring-red-500 focus:border-red-500 transition-colors"></textarea>
                </div>
                
                <div class="p-3 bg-red-50 text-red-600 text-xs rounded-lg mt-2 font-medium border border-red-100">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Barang yang ditolak tidak akan dimasukkan ke dalam inventaris dan statusnya akan dikembalikan ke Admin.
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
                <button type="button" onclick="tutupModalTolak()" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold">Kirim Penolakan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Setujui Pengadaan --}}
<div id="modalSetujui" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden">
        <form id="formSetujui" method="POST">
            @csrf @method('PATCH')
            <div class="px-6 py-4 border-b border-gray-100 bg-green-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-green-800">Setujui Pengajuan Barang</h3>
                <button type="button" onclick="tutupModalSetujui()" class="text-green-400 hover:text-green-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="p-6">
                <p class="text-sm font-medium text-gray-600 mb-4">Anda akan menyetujui pengadaan: <br><span id="namaBarangSetujui" class="font-bold text-gray-900 text-lg"></span></p>
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Disetujui <span class="text-green-500">*</span></label>
                    <input type="number" name="jumlah_disetujui" id="jumlahDisetujuiInput" min="1" required class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:ring-green-500 focus:border-green-500 transition-colors">
                    <p class="text-xs text-gray-500 mt-1">Ubah jumlah jika disetujui sebagian.</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan/Keterangan <span class="text-gray-400 text-xs font-normal">(Opsional)</span></label>
                    <textarea name="alasan_penolakan" rows="2" placeholder="Tuliskan keterangan (misal: disetujui sebagian karena anggaran)..." class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:ring-green-500 focus:border-green-500 transition-colors"></textarea>
                </div>
                
                <div class="p-3 bg-green-50 text-green-700 text-xs rounded-lg mt-2 font-medium border border-green-100">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Barang akan langsung masuk ke dalam inventaris dengan jumlah yang disetujui.
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
                <button type="button" onclick="tutupModalSetujui()" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-semibold">Setujui Pengadaan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function bukaModalTolak(id, nama) {
        document.getElementById('modalTolak').classList.remove('hidden');
        document.getElementById('namaBarangTolak').innerText = nama;
        document.getElementById('formTolak').action = `/kepsek/approval/pengadaan/${id}/reject`;
    }
    function tutupModalTolak() {
        document.getElementById('modalTolak').classList.add('hidden');
    }

    function bukaModalSetujui(id, nama, jumlah) {
        document.getElementById('modalSetujui').classList.remove('hidden');
        document.getElementById('namaBarangSetujui').innerText = nama;
        document.getElementById('jumlahDisetujuiInput').value = jumlah;
        document.getElementById('jumlahDisetujuiInput').max = jumlah;
        document.getElementById('formSetujui').action = `/kepsek/approval/pengadaan/${id}/approve`;
    }
    function tutupModalSetujui() {
        document.getElementById('modalSetujui').classList.add('hidden');
    }
</script>
@endsection
