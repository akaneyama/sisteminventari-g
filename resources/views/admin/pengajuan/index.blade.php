@extends('layouts.app')
@section('title', 'Status Pengajuan Barang')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Status Pengajuan Barang</h2>
        <p class="text-sm text-gray-500 mt-1">Pantau status usulan barang baru yang sedang menunggu persetujuan Kepala Sekolah.</p>
    </div>
    <div>
        <a href="{{ route('barang.create') }}" class="inline-flex items-center justify-center py-2.5 px-5 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Buat Pengajuan Baru
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-xl shadow-sm text-sm font-medium">
        {{ session('success') }}
    </div>
@endif

<div class="mb-5 flex space-x-1 p-1.5 bg-gray-100 rounded-xl w-fit border border-gray-200">
    <a href="{{ route('admin.pengajuan.index', ['tab' => 'aktif']) }}" 
       class="px-5 py-2 text-sm font-semibold rounded-lg transition-all {{ $tab === 'aktif' ? 'bg-white text-blue-700 shadow-sm border border-gray-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-200' }}">
        Sedang Diproses
    </a>
    <a href="{{ route('admin.pengajuan.index', ['tab' => 'ditolak']) }}" 
       class="px-5 py-2 text-sm font-semibold rounded-lg transition-all {{ $tab === 'ditolak' ? 'bg-white text-red-700 shadow-sm border border-gray-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-200' }}">
        Ditolak
    </a>
    <a href="{{ route('admin.pengajuan.index', ['tab' => 'semua']) }}" 
       class="px-5 py-2 text-sm font-semibold rounded-lg transition-all {{ $tab === 'semua' ? 'bg-white text-gray-800 shadow-sm border border-gray-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-200' }}">
        Semua Riwayat
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Data Barang</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Detail</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/3">Keterangan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($pengajuans as $item)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-blue-700">{{ $item->kode_inventaris }}</div>
                        <div class="text-sm text-gray-900">{{ $item->nama_barang }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $item->merk_type }} &bull; Diajukan: {{ $item->jumlah_diajukan ?? $item->jumlah_barang }} Unit</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        Sumber: <span class="font-medium">{{ $item->sumberDana->nama_sumber_dana ?? '-' }}</span><br>
                        Lokasi: <span class="font-medium">{{ $item->lokasi->nama_ruangan ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($item->status_approval === 'Menunggu Pengadaan')
                            <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Persetujuan</span>
                        @elseif($item->status_approval === 'Pengadaan Disetujui')
                            <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">Disetujui Kepsek</span>
                        @elseif($item->status_approval === 'Pengadaan Ditolak')
                            <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">Ditolak Kepsek</span>
                        @elseif($item->status_approval === 'Tersedia' || $item->status_approval === 'Dalam Perbaikan')
                            <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">Selesai / Menjadi Aset</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($item->status_approval === 'Pengadaan Ditolak')
                            <div class="bg-red-50 text-red-700 p-3 rounded-xl border border-red-100 shadow-sm inline-block w-full">
                                <p class="font-bold text-xs mb-1">Alasan Penolakan:</p>
                                <p class="text-xs">{{ $item->alasan_penolakan ?? 'Tidak ada alasan yang diberikan' }}</p>
                            </div>
                        @elseif($item->status_approval === 'Tersedia' || $item->status_approval === 'Dalam Perbaikan')
                            <div class="bg-blue-50 text-blue-700 p-3 rounded-xl border border-blue-100 shadow-sm inline-block w-full">
                                <p class="font-bold text-xs mb-1">Pengadaan Selesai</p>
                                <p class="text-xs">Barang sudah diterima dan tercatat di Data Barang Inventaris.</p>
                            </div>
                        @elseif($item->status_approval === 'Pengadaan Disetujui')
                            <div class="bg-green-50 text-green-700 p-3 rounded-xl border border-green-100 shadow-sm flex flex-col xl:flex-row items-start xl:items-center justify-between gap-3 w-full">
                                <div>
                                    <p class="font-bold text-xs mb-1">Proses Pembelian</p>
                                    <p class="text-xs opacity-90">Barang dalam proses dibeli/dikirim.</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.pengajuan.cetak_po', $item->id_barang) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-gray-600 text-white hover:bg-gray-700 rounded-lg text-xs font-bold transition-colors shadow-sm">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        Cetak PO
                                    </a>
                                    <button type="button" onclick="bukaModalTerima({{ $item->id_barang }}, '{{ $item->nama_barang }}')" class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white hover:bg-green-700 rounded-lg text-xs font-bold transition-colors shadow-sm">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Diterima
                                    </button>
                                </div>
                            </div>
                        @else
                            <span class="text-gray-400 italic text-xs">Sedang ditinjau oleh Kepala Sekolah...</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <p class="text-base font-medium text-gray-600">Belum ada pengajuan barang baru.</p>
                        <p class="text-sm mt-1 text-gray-400">Silakan klik "Buat Pengajuan Baru" untuk mengajukan pengadaan aset ke Kepala Sekolah.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Konfirmasi Terima Barang --}}
<div id="modalTerima" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden">
        <form id="formTerima" method="POST" enctype="multipart/form-data">
            @csrf @method('PATCH')
            <div class="px-6 py-4 border-b border-gray-100 bg-green-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-green-800">Konfirmasi Barang Diterima</h3>
                <button type="button" onclick="tutupModalTerima()" class="text-green-400 hover:text-green-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="p-6">
                <p class="text-sm font-medium text-gray-600 mb-4">Anda akan mengonfirmasi penerimaan barang: <br><span id="namaBarangTerima" class="font-bold text-gray-900 text-lg"></span></p>
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Bukti Nota/Foto Barang <span class="text-gray-400 text-xs font-normal">(Opsional tapi disarankan)</span></label>
                    <input type="file" name="bukti_nota" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-gray-200 rounded-xl transition-all cursor-pointer bg-gray-50 focus:outline-none">
                    <p class="text-xs text-gray-500 mt-1">Format gambar (jpg, png). Maksimal 2MB.</p>
                </div>
                
                <div class="p-3 bg-green-50 text-green-700 text-xs rounded-lg mt-2 font-medium border border-green-100">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Setelah dikonfirmasi, barang akan secara resmi masuk ke dalam daftar inventaris aktif.
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
                <button type="button" onclick="tutupModalTerima()" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-semibold shadow-sm">Konfirmasi Diterima</button>
            </div>
        </form>
    </div>
</div>

<script>
    function bukaModalTerima(id, nama) {
        document.getElementById('modalTerima').classList.remove('hidden');
        document.getElementById('namaBarangTerima').innerText = nama;
        document.getElementById('formTerima').action = `/admin/pengajuan/${id}/terima`;
    }
    function tutupModalTerima() {
        document.getElementById('modalTerima').classList.add('hidden');
    }
</script>
@endsection
