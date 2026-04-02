@extends('layouts.app')
@section('title', 'Riwayat Mutasi — Kepala Sekolah')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Jejak Rekam Pergerakan Aset</h2>
        <p class="text-sm text-gray-500 mt-1">Pantau seluruh riwayat perpindahan dan perubahan kondisi barang. Mode tampilan saja.</p>
    </div>
</div>

{{-- Search & Filter --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
    <form action="{{ route('kepsek.mutasi.index') }}" method="GET" class="flex flex-col sm:flex-row flex-wrap gap-3">
        <div class="relative flex-1 min-w-[200px]">
            <svg class="absolute left-3 top-3 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ $filter['search'] ?? '' }}" placeholder="Cari kode atau nama barang..."
                class="block w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 hover:bg-white focus:ring-blue-500 focus:border-blue-500 text-sm transition duration-200">
        </div>
        <select name="jenis_mutasi" class="block w-full sm:w-44 px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-700 text-sm">
            <option value="">Semua Jenis Mutasi</option>
            <option value="Pindah Lokasi" {{ ($filter['jenis_mutasi'] ?? '') == 'Pindah Lokasi' ? 'selected' : '' }}>Pindah Lokasi</option>
            <option value="Ubah Status" {{ ($filter['jenis_mutasi'] ?? '') == 'Ubah Status' ? 'selected' : '' }}>Ubah Status</option>
            <option value="Penghapusan" {{ ($filter['jenis_mutasi'] ?? '') == 'Penghapusan' ? 'selected' : '' }}>Penghapusan</option>
        </select>
        <div class="flex items-center gap-2">
            <label class="text-xs text-gray-500 whitespace-nowrap">Dari:</label>
            <input type="date" name="tanggal_dari" value="{{ $filter['tanggal_dari'] ?? '' }}"
                class="px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-700 text-sm">
        </div>
        <div class="flex items-center gap-2">
            <label class="text-xs text-gray-500 whitespace-nowrap">Sampai:</label>
            <input type="date" name="tanggal_sampai" value="{{ $filter['tanggal_sampai'] ?? '' }}"
                class="px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-700 text-sm">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="inline-flex items-center py-2.5 px-5 rounded-xl text-sm font-semibold text-white bg-gray-800 hover:bg-gray-900 transition-all">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filter
            </button>
            @if(request()->hasAny(['search','jenis_mutasi','tanggal_dari','tanggal_sampai']))
                <a href="{{ route('kepsek.mutasi.index') }}" class="inline-flex items-center py-2.5 px-4 rounded-xl text-sm font-medium text-red-600 bg-red-50 border border-red-200 hover:bg-red-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Tanggal</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Barang</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Jumlah</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Detail</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Admin</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($mutasi as $item)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($item->tanggal_mutasi)->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="font-bold text-blue-600">{{ $item->barang->kode_inventaris ?? 'N/A' }}</div>
                        <div class="text-gray-500 text-xs">{{ $item->barang->nama_barang ?? 'Barang Terhapus' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">
                        {{ $item->jumlah }} Unit
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($item->jenis_mutasi == 'Pindah Lokasi')
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">{{ $item->jenis_mutasi }}</span>
                        @elseif($item->jenis_mutasi == 'Ubah Status')
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">{{ $item->jenis_mutasi }}</span>
                        @else
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">{{ $item->jenis_mutasi }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700 leading-relaxed">
                        @if($item->jenis_mutasi == 'Pindah Lokasi')
                            <span class="text-gray-400">Dari</span> <b>{{ $item->lokasiAsal->nama_ruangan ?? '-' }}</b><br>
                            <span class="text-gray-400">Ke</span> <b class="text-blue-600">{{ $item->lokasiTujuan->nama_ruangan ?? '-' }}</b>
                        @elseif($item->jenis_mutasi == 'Ubah Status')
                            <span class="text-gray-400">Dari</span> <b>{{ $item->kondisi_sebelum }}</b><br>
                            <span class="text-gray-400">Jadi</span> <b class="text-yellow-600">{{ $item->kondisi_sesudah }}</b>
                        @else
                            <span class="italic text-gray-500">Dihapus dari sistem</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $item->keterangan }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->user->nama_lengkap ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <p class="text-base font-medium text-gray-600">Belum ada riwayat mutasi.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
