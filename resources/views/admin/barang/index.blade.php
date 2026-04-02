@extends('layouts.app')
@section('title', 'Data Barang Inventaris')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Daftar Aset Barang</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola data seluruh aset barang inventaris yang terdaftar.</p>
    </div>
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('barang.create') }}" class="inline-flex items-center justify-center py-2.5 px-5 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 ease-in-out transform hover:-translate-y-0.5 w-full sm:w-auto">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Tambah Barang Baru
        </a>
        <button id="btnBatchLabel" type="button" onclick="submitBatchLabel()" disabled
            class="inline-flex items-center justify-center py-2.5 px-5 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-purple-600 hover:bg-purple-700 disabled:opacity-40 disabled:cursor-not-allowed transition-all duration-200 w-full sm:w-auto">
            🖨️ <span class="ml-2">Cetak Label Terpilih (<span id="selectedCount">0</span>)</span>
        </button>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-xl shadow-sm flex items-start">
        <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-medium text-sm">{{ session('success') }}</span>
    </div>
@endif

{{-- Search & Filter Bar --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
    <form action="{{ route('barang.index') }}" method="GET" class="flex flex-col sm:flex-row flex-wrap gap-3">
        <div class="relative flex-1 min-w-[200px]">
            <svg class="absolute left-3 top-3 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ $filter['search'] ?? '' }}" placeholder="Cari kode, nama, atau merk barang..."
                class="block w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 hover:bg-white focus:ring-blue-500 focus:border-blue-500 text-sm transition duration-200">
        </div>

        <select name="id_kategori" class="block w-full sm:w-40 px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-700 text-sm">
            <option value="">Semua Kategori</option>
            @foreach($kategori as $k)
                <option value="{{ $k->id_kategori }}" {{ ($filter['id_kategori'] ?? '') == $k->id_kategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
            @endforeach
        </select>

        <select name="id_lokasi" class="block w-full sm:w-40 px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-700 text-sm">
            <option value="">Semua Lokasi</option>
            @foreach($lokasi as $l)
                <option value="{{ $l->id_lokasi }}" {{ ($filter['id_lokasi'] ?? '') == $l->id_lokasi ? 'selected' : '' }}>{{ $l->nama_ruangan }}</option>
            @endforeach
        </select>

        <select name="kondisi" class="block w-full sm:w-36 px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-700 text-sm">
            <option value="">Semua Kondisi</option>
            <option value="Baik" {{ ($filter['kondisi'] ?? '') == 'Baik' ? 'selected' : '' }}>Baik</option>
            <option value="Rusak Ringan" {{ ($filter['kondisi'] ?? '') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
            <option value="Rusak Berat" {{ ($filter['kondisi'] ?? '') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
        </select>

        <div class="flex gap-2">
            <button type="submit" class="inline-flex items-center py-2.5 px-5 rounded-xl text-sm font-semibold text-white bg-gray-800 hover:bg-gray-900 transition-all duration-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filter
            </button>
            @if(request()->hasAny(['search','id_lokasi','id_kategori','kondisi']))
                <a href="{{ route('barang.index') }}" class="inline-flex items-center py-2.5 px-4 rounded-xl text-sm font-medium text-red-600 bg-red-50 border border-red-200 hover:bg-red-100 transition-all duration-200" title="Reset">
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
                    <th scope="col" class="px-4 py-4 w-10">
                        <input type="checkbox" id="checkAll" class="rounded text-blue-600 cursor-pointer w-4 h-4">
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kode</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Barang</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lokasi</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kondisi</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Supplier/Sumber</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($barang as $item)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-4 py-4">
                        <input type="checkbox" name="label_ids[]" value="{{ $item->id_barang }}" class="item-check rounded text-blue-600 cursor-pointer w-4 h-4" onchange="updateCount()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-700">
                        {{ $item->kode_inventaris }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($item->foto_barang)
                                <img class="h-10 w-10 rounded-lg object-cover mr-3 border border-gray-200" src="{{ asset('storage/' . $item->foto_barang) }}" alt="Foto Barang">
                            @else
                                <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center mr-3 border border-gray-200 text-gray-400 text-xs font-medium">No Pic</div>
                            @endif
                            <div>
                                <div class="text-sm font-bold text-gray-900">{{ $item->nama_barang }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $item->merk_type }} • {{ $item->kategori->nama_kategori ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $item->lokasi->nama_ruangan ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($item->kondisi == 'Baik')
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">Baik</span>
                        @elseif($item->kondisi == 'Rusak Ringan')
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">Rusak Ringan</span>
                        @else
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">Rusak Berat</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                        {{ $item->jumlah_barang }} Unit
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <div class="font-medium text-gray-900">{{ $item->supplier->nama_supplier ?? '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $item->sumberDana->nama_sumber_dana ?? '-' }} ({{ $item->tahun_perolehan }})</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('barang.edit', $item->id_barang) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <a href="{{ route('barang.label', $item->id_barang) }}" target="_blank" class="text-green-600 hover:text-green-900">🖨️ Label</a>
                            <form action="{{ route('barang.destroy', $item->id_barang) }}" method="POST" onsubmit="return confirm('Yakin ingin membuang barang ini ke recycle bin?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <p class="text-base font-medium text-gray-600">{{ request()->hasAny(['search','id_lokasi','id_kategori','kondisi']) ? 'Tidak ada data yang sesuai filter.' : 'Belum ada data barang.' }}</p>
                        <p class="text-sm mt-1">{{ request()->hasAny(['search','id_lokasi','id_kategori','kondisi']) ? 'Coba ubah atau reset filter pencarian.' : 'Silakan tambah barang baru untuk memulai.' }}</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Hidden form untuk batch label --}}
<form id="batchLabelForm" action="{{ route('barang.label.batch') }}" method="POST" target="_blank">
    @csrf
    <div id="batchIds"></div>
</form>

@push('scripts')
<script>
    document.getElementById('checkAll').addEventListener('change', function() {
        document.querySelectorAll('.item-check').forEach(cb => cb.checked = this.checked);
        updateCount();
    });

    function updateCount() {
        const checked = document.querySelectorAll('.item-check:checked');
        document.getElementById('selectedCount').textContent = checked.length;
        document.getElementById('btnBatchLabel').disabled = checked.length === 0;
        const all = document.querySelectorAll('.item-check');
        document.getElementById('checkAll').indeterminate = checked.length > 0 && checked.length < all.length;
        document.getElementById('checkAll').checked = checked.length === all.length && all.length > 0;
    }

    function submitBatchLabel() {
        const checked = document.querySelectorAll('.item-check:checked');
        if (checked.length === 0) return;
        const container = document.getElementById('batchIds');
        container.innerHTML = '';
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden'; input.name = 'ids[]'; input.value = cb.value;
            container.appendChild(input);
        });
        document.getElementById('batchLabelForm').submit();
    }
</script>
@endpush
@endsection