@extends('layouts.app')
@section('title', 'Data Perbaikan Aset')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Perbaikan Aset (Maintenance)</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola barang yang sedang dalam masa perbaikan (servis).</p>
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
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Barang</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Mulai</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
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
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" title="{{ $p->keterangan }}">
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
                            <button onclick="bukaModalSelesai({{ $p->id_perbaikan }}, '{{ $p->barang->nama_barang }}')" class="text-white bg-green-600 hover:bg-green-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors">Selesaikan</button>
                        @else
                            <span class="text-gray-400 text-xs">Selesai pada {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}<br>Rp {{ number_format($p->biaya, 0, ',', '.') }}</span>
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
</div>

{{-- Modal Selesai Servis --}}
<div id="modalSelesai" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden">
        <form id="formSelesai" method="POST">
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
