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
                        <div class="text-xs text-gray-500 mt-0.5">{{ $item->merk_type }} &bull; {{ $item->jumlah_barang }} Unit</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        Sumber: <span class="font-medium">{{ $item->sumberDana->nama_sumber_dana ?? '-' }}</span><br>
                        Lokasi: <span class="font-medium">{{ $item->lokasi->nama_ruangan ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($item->status_approval === 'Menunggu Pengadaan')
                            <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Persetujuan</span>
                        @elseif($item->status_approval === 'Pengadaan Ditolak')
                            <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">Ditolak Kepsek</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($item->status_approval === 'Pengadaan Ditolak')
                            <div class="bg-red-50 text-red-700 p-3 rounded-xl border border-red-100 shadow-sm inline-block w-full">
                                <p class="font-bold text-xs mb-1">Alasan Penolakan:</p>
                                <p class="text-xs">{{ $item->alasan_penolakan ?? 'Tidak ada alasan yang diberikan' }}</p>
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
@endsection
