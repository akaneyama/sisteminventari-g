@extends('layouts.app')
@section('title', 'Riwayat Mutasi Barang')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Jejak Rekam Pergerakan Aset</h2>
        <p class="text-sm text-gray-500 mt-1">Pantau seluruh riwayat perpindahan, perubahan kondisi, hingga penghapusan barang.</p>
    </div>
    <a href="{{ route('mutasi.create') }}" class="inline-flex items-center justify-center py-2.5 px-5 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 ease-in-out transform hover:-translate-y-0.5 w-full sm:w-auto">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
        Proses Mutasi Baru
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-xl shadow-sm flex items-start">
        <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="font-medium text-sm">{{ session('success') }}</span>
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Tanggal</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Barang</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis Mutasi</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Detail Perubahan</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Admin</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($mutasi as $item)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                        {{ \Carbon\Carbon::parse($item->tanggal_mutasi)->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="font-bold text-blue-600">{{ $item->barang->kode_inventaris ?? 'N/A' }}</div>
                        <div class="text-gray-500 text-xs mt-0.5">{{ $item->barang->nama_barang ?? 'Barang Terhapus' }}</div>
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
                            <span class="text-gray-500">Dari</span> <span class="font-semibold">{{ $item->lokasiAsal->nama_ruangan ?? '-' }}</span> <br> 
                            <span class="text-gray-500">Ke</span> <span class="font-semibold text-blue-600">{{ $item->lokasiTujuan->nama_ruangan ?? '-' }}</span>
                        @elseif($item->jenis_mutasi == 'Ubah Status')
                            <span class="text-gray-500">Dari</span> <span class="font-semibold">{{ $item->kondisi_sebelum }}</span> <br> 
                            <span class="text-gray-500">Menjadi</span> <span class="font-semibold text-yellow-600">{{ $item->kondisi_sesudah }}</span>
                        @else
                            <span class="italic text-gray-500">{{ $item->keterangan }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 flex items-center mt-2">
                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        {{ $item->user->nama_lengkap ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <p class="text-base font-medium text-gray-600">Belum ada riwayat mutasi.</p>
                        <p class="text-sm mt-1">Data perpindahan atau perubahan barang akan muncul di sini.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection