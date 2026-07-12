@extends('layouts.app')
@section('title', 'Data Perbaikan Aset')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex-1">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Perbaikan Aset (Maintenance)</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola barang yang sedang dalam masa perbaikan (servis).</p>
    </div>
</div>

<div class="mb-5 flex flex-wrap gap-1 p-1.5 bg-gray-100 rounded-xl w-fit border border-gray-200">
    @php $status = request('status', ''); @endphp
    <a href="{{ route('perbaikan.index') }}" 
       class="px-5 py-2 text-sm font-semibold rounded-lg transition-all {{ $status === '' ? 'bg-white text-gray-800 shadow-sm border border-gray-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-200' }}">
        Semua Status
    </a>
    <a href="{{ route('perbaikan.index', ['status' => 'Proses']) }}" 
       class="px-5 py-2 text-sm font-semibold rounded-lg transition-all {{ $status === 'Proses' ? 'bg-white text-blue-700 shadow-sm border border-gray-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-200' }}">
        Dalam Perbaikan
    </a>
    <a href="{{ route('perbaikan.index', ['status' => 'Selesai Berhasil']) }}" 
       class="px-5 py-2 text-sm font-semibold rounded-lg transition-all {{ $status === 'Selesai Berhasil' ? 'bg-white text-green-700 shadow-sm border border-gray-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-200' }}">
        Selesai Berhasil
    </a>
    <a href="{{ route('perbaikan.index', ['status' => 'Selesai Gagal']) }}" 
       class="px-5 py-2 text-sm font-semibold rounded-lg transition-all {{ $status === 'Selesai Gagal' ? 'bg-white text-red-700 shadow-sm border border-gray-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-200' }}">
        Selesai Gagal
    </a>
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
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Tanggal Mulai</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Keterangan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($perbaikans as $p)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-blue-700">{{ $p->barang->kode_inventaris ?? 'Terhapus' }}</div>
                        <div class="text-sm text-gray-900">{{ $p->barang->nama_barang ?? '-' }}</div>
                        <div class="text-xs text-gray-500 mt-1 sm:hidden">Mulai: {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }}</div>
                        <div class="text-xs text-gray-500 lg:hidden mt-1 line-clamp-2">{{ $p->keterangan }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 hidden sm:table-cell">
                        {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate hidden lg:table-cell" title="{{ $p->keterangan }}">
                        {{ $p->keterangan }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($p->status_perbaikan == 'Proses')
                            <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Sedang Diservis</span>
                        @elseif($p->status_perbaikan == 'Selesai Berhasil')
                            <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">Selesai (Berhasil)</span>
                        @else
                            <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800">Gagal Diperbaiki</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        @if($p->status_perbaikan == 'Proses')
                            <button onclick="bukaModalSelesai({{ $p->id_perbaikan }}, '{{ addslashes($p->barang->nama_barang) }}')" class="text-white bg-green-600 hover:bg-green-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors">Selesaikan</button>
                        @else
                            <span class="text-gray-400 text-xs">Selesai pada {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}<br>Rp {{ number_format($p->biaya, 0, ',', '.') }}</span>
                            <div class="flex justify-end mt-1 gap-2">
                                @if($p->nota_perbaikan)
                                    <a href="{{ asset('storage/' . $p->nota_perbaikan) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs font-bold inline-flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Nota Asli
                                    </a>
                                @endif
                                <a href="{{ route('perbaikan.cetak', $p->id_perbaikan) }}" target="_blank" class="text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 px-2 py-1 rounded-md text-xs font-semibold inline-flex items-center transition-colors">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Cetak Bukti
                                </a>
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <p class="text-base font-medium text-gray-600">Belum ada data perbaikan aset.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">
        {{ $perbaikans->withQueryString()->links() }}
    </div>
</div>

{{-- Modal Selesai Servis --}}
<div id="modalSelesai" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden">
        <form id="formSelesai" method="POST" enctype="multipart/form-data">
            @csrf @method('PATCH')
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Selesaikan Perbaikan</h3>
                <button type="button" onclick="tutupModal()" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="p-6">
                <p class="text-sm font-medium text-gray-600 mb-4">Barang: <span id="namaBarangModal" class="font-bold text-gray-900"></span></p>
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" value="{{ date('Y-m-d') }}" required class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Hasil Perbaikan</label>
                    <select name="hasil" id="hasilSelect" required class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50" onchange="toggleKondisiAkhir()">
                        <option value="Berhasil">Berhasil Diperbaiki</option>
                        <option value="Gagal">Gagal (Rusak Total)</option>
                    </select>
                </div>
                <div class="mb-4" id="divKondisiAkhir">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kondisi Akhir</label>
                    <select name="kondisi_akhir" id="kondisiAkhirSelect" class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50">
                        <option value="Baik">Baik</option>
                        <option value="Rusak Ringan">Rusak Ringan (Masih bisa dipakai)</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Biaya Servis (Rp)</label>
                    <input type="number" name="biaya" value="0" min="0" required class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Nota/Bukti <span class="text-gray-400 font-normal">(Opsional, Max 2MB)</span></label>
                    <input type="file" name="nota_perbaikan" accept="image/*,.pdf" 
                        class="block w-full text-sm text-gray-500 border border-gray-200 rounded-xl bg-gray-50 hover:bg-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-200 ease-in-out file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                </div>
                
                <div id="pesanGagal" class="hidden p-3 bg-red-50 text-red-600 text-xs rounded-lg mt-2 font-medium">
                    Jika gagal, barang akan otomatis diajukan untuk **Dihapus** dan menunggu persetujuan Kepala Sekolah.
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
                <button type="button" onclick="tutupModal()" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function bukaModalSelesai(id, nama) {
        document.getElementById('modalSelesai').classList.remove('hidden');
        document.getElementById('namaBarangModal').innerText = nama;
        document.getElementById('formSelesai').action = `/admin/perbaikan/${id}/selesai`;
        toggleKondisiAkhir();
    }
    function tutupModal() {
        document.getElementById('modalSelesai').classList.add('hidden');
    }
    function toggleKondisiAkhir() {
        const hasil = document.getElementById('hasilSelect').value;
        const divKondisi = document.getElementById('divKondisiAkhir');
        const selectKondisi = document.getElementById('kondisiAkhirSelect');
        const pesanGagal = document.getElementById('pesanGagal');
        
        if (hasil === 'Berhasil') {
            divKondisi.classList.remove('hidden');
            selectKondisi.required = true;
            pesanGagal.classList.add('hidden');
        } else {
            divKondisi.classList.add('hidden');
            selectKondisi.required = false;
            pesanGagal.classList.remove('hidden');
        }
    }
</script>
@endsection
