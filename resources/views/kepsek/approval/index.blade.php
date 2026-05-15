@extends('layouts.app')
@section('title', 'Persetujuan Penghapusan Aset')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Persetujuan Penghapusan</h2>
        <p class="text-sm text-gray-500 mt-1">Daftar aset yang diajukan untuk dihapuskan (misal karena rusak berat).</p>
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
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kode</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Barang</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lokasi</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kondisi</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($pendingBarang as $item)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-700">{{ $item->kode_inventaris }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($item->foto_barang)
                                <img class="h-10 w-10 rounded-lg object-cover mr-3 border border-gray-200" src="{{ asset('storage/' . $item->foto_barang) }}" alt="">
                            @else
                                <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center mr-3 border border-gray-200 text-gray-400 text-xs">No Pic</div>
                            @endif
                            <div>
                                <div class="text-sm font-bold text-gray-900">{{ $item->nama_barang }}</div>
                                <div class="text-xs text-gray-500">{{ $item->merk_type }} • {{ $item->kategori->nama_kategori ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->lokasi->nama_ruangan ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">{{ $item->kondisi }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            <form action="{{ route('kepsek.approval.approve', $item->id_barang) }}" method="POST" onsubmit="return confirm('Yakin menyetujui penghapusan aset ini?');">
                                @csrf @method('PATCH')
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-semibold transition-colors">Setujui</button>
                            </form>
                            <form action="{{ route('kepsek.approval.reject', $item->id_barang) }}" method="POST" onsubmit="return confirm('Tolak penghapusan aset ini?');">
                                @csrf @method('PATCH')
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-semibold transition-colors">Tolak</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-base font-medium text-gray-600">Belum ada pengajuan penghapusan aset.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
