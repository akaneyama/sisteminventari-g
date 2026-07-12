@extends('layouts.app')
@section('title', 'Data Barang Inventaris')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Daftar Aset Barang</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola data seluruh aset barang inventaris yang terdaftar.</p>
    </div>
    <div class="grid grid-cols-2 sm:flex sm:flex-row items-center gap-3 w-full sm:w-auto">
        <a href="{{ route('barang.trash') }}" class="col-span-1 inline-flex items-center justify-center py-2.5 px-3 border border-red-200 rounded-xl shadow-sm text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 transition-all duration-200 w-full sm:w-auto">
            <svg class="w-5 h-5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            <span class="hidden sm:inline">Sampah</span>
            <span class="sm:hidden ml-1">Sampah</span>
        </a>
        <a href="{{ route('barang.create') }}" class="col-span-1 inline-flex items-center justify-center py-2.5 px-3 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-all duration-200 w-full sm:w-auto">
            <svg class="w-5 h-5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            <span class="hidden sm:inline">Tambah Barang</span>
            <span class="sm:hidden ml-1">Tambah</span>
        </a>
        <button id="btnBatchLabel" type="button" onclick="submitBatchLabel()" disabled
            class="col-span-2 inline-flex items-center justify-center py-2.5 px-5 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-purple-600 hover:bg-purple-700 disabled:opacity-40 disabled:cursor-not-allowed transition-all duration-200 w-full sm:w-auto">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            Cetak Label (<span id="selectedCount" class="ml-1">0</span>)
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
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Kode</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Barang</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden md:table-cell">Lokasi</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Kondisi</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Jumlah</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Supplier/Sumber</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($barang as $item)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-4 py-4">
                        <input type="checkbox" name="label_ids[]" value="{{ $item->id_barang }}" class="item-check rounded text-blue-600 cursor-pointer w-4 h-4" onchange="updateCount()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-700 hidden sm:table-cell">
                        {{ $item->kode_inventaris }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($item->foto_barang)
                                <div class="relative group cursor-pointer mr-3 flex-shrink-0" onclick="openImageModal('{{ asset('storage/' . $item->foto_barang) }}')" title="Lihat Foto">
                                    <img class="h-10 w-10 rounded-lg object-cover border border-gray-200" src="{{ asset('storage/' . $item->foto_barang) }}" alt="Foto Barang">
                                    <div class="absolute inset-0 bg-gray-900/50 hidden group-hover:flex items-center justify-center rounded-lg transition-all duration-200">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </div>
                                </div>
                            @else
                                <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center mr-3 border border-gray-200 text-gray-400 text-xs font-medium shadow-inner">No Pic</div>
                            @endif
                            <div>
                                <div class="text-sm font-bold text-blue-700 sm:hidden">{{ $item->kode_inventaris }}</div>
                                <div class="text-sm font-bold text-gray-900 leading-tight">{{ $item->nama_barang }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $item->merk_type }} • {{ $item->kategori->nama_kategori ?? '-' }}</div>
                                <div class="text-xs text-blue-600 font-medium md:hidden mt-0.5">📍 {{ $item->lokasi->nama_ruangan ?? '-' }}</div>
                                <div class="flex items-center gap-2 mt-1 sm:hidden">
                                    @if($item->kondisi == 'Baik')
                                        <span class="px-2 py-0.5 inline-flex text-[10px] font-semibold rounded bg-green-100 text-green-800 border border-green-200">Baik</span>
                                    @elseif($item->kondisi == 'Rusak Ringan')
                                        <span class="px-2 py-0.5 inline-flex text-[10px] font-semibold rounded bg-yellow-100 text-yellow-800 border border-yellow-200">Rusak Ringan</span>
                                    @else
                                        <span class="px-2 py-0.5 inline-flex text-[10px] font-semibold rounded bg-red-100 text-red-800 border border-red-200">Rusak Berat</span>
                                    @endif
                                    <span class="text-xs font-bold text-gray-700">{{ $item->jumlah_barang }} Unit</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 hidden md:table-cell">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $item->lokasi->nama_ruangan ?? '-' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                        @if($item->kondisi == 'Baik')
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200 shadow-sm">Baik</span>
                        @elseif($item->kondisi == 'Rusak Ringan')
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200 shadow-sm">Rusak Ringan</span>
                        @else
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200 shadow-sm">Rusak Berat</span>
                        @endif
                        
                        @if($item->status_approval == 'Dalam Perbaikan')
                            <div class="mt-1.5">
                                <span class="px-2 py-0.5 inline-flex text-[10px] font-bold rounded-md bg-blue-100 text-blue-800 border border-blue-200 shadow-sm" title="Barang ini sedang dalam perbaikan (Maintenance)">🛠️ Diservis</span>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 hidden sm:table-cell">
                        @if($item->jumlah_diajukan && $item->jumlah_diajukan != $item->jumlah_barang)
                            <div class="text-xs text-gray-400 line-through font-normal" title="Jumlah awal diajukan">{{ $item->jumlah_diajukan }} Unit</div>
                            <div class="text-green-600" title="Jumlah disetujui/tersedia">{{ $item->jumlah_barang }} Unit</div>
                        @else
                            {{ $item->jumlah_barang }} Unit
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 hidden lg:table-cell">
                        <div class="font-medium text-gray-900">{{ $item->supplier->nama_supplier ?? '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $item->sumberDana->nama_sumber_dana ?? '-' }} ({{ $item->tahun_perolehan }})</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-2">
                            <button type="button" 
                                data-detail="{{ json_encode([
                                    'kode' => $item->kode_inventaris,
                                    'nama' => $item->nama_barang,
                                    'merk' => $item->merk_type,
                                    'kategori' => $item->kategori->nama_kategori ?? '-',
                                    'lokasi' => $item->lokasi->nama_ruangan ?? '-',
                                    'kondisi' => $item->kondisi,
                                    'jumlah' => $item->jumlah_barang,
                                    'supplier' => $item->supplier->nama_supplier ?? '-',
                                    'sumber' => ($item->sumberDana->nama_sumber_dana ?? '-') . ' (' . $item->tahun_perolehan . ')',
                                    'foto' => $item->foto_barang ? asset('storage/' . $item->foto_barang) : null,
                                    'nota' => $item->bukti_nota ? asset('storage/' . $item->bukti_nota) : null
                                ]) }}"
                                onclick="openPreviewDetail(this)"
                                class="p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>

                            @if($item->status_approval == 'Dalam Perbaikan')
                                <span class="text-gray-400 text-xs font-semibold italic bg-gray-100 px-2 py-1 rounded">Disabled</span>
                            @else
                                <a href="{{ route('barang.label', $item->id_barang) }}" target="_blank" class="p-2 text-green-600 hover:text-green-700 hover:bg-green-50 rounded-lg transition-colors" title="Cetak Label QR">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                </a>
                                @if($item->kondisi !== 'Baik')
                                    <a href="{{ route('perbaikan.create', ['id_barang' => $item->id_barang]) }}" class="p-2 text-yellow-600 hover:text-yellow-700 hover:bg-yellow-50 rounded-lg transition-colors" title="Ajukan Perbaikan (Servis)">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </a>
                                @endif
                                <a href="{{ route('barang.edit', $item->id_barang) }}" class="p-2 text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit Data">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('barang.destroy', $item->id_barang) }}" method="POST" onsubmit="return confirm('Yakin ingin membuang barang ini ke recycle bin?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Barang">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            @endif
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
    <div class="p-4 border-t border-gray-100">
        {{ $barang->withQueryString()->links() }}
    </div>
</div>

{{-- Hidden form untuk batch label --}}
<form id="batchLabelForm" action="{{ route('barang.label.batch') }}" method="POST" target="_blank">
    @csrf
    <div id="batchIds"></div>
</form>

{{-- Modal Preview Detail --}}
<div id="modalDetail" class="fixed inset-0 z-[60] hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Preview Detail Barang
            </h3>
            <button type="button" onclick="closeDetailModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="p-6 overflow-y-auto flex-1">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Kolom Foto -->
                <div class="col-span-1 space-y-5">
                    <div>
                        <p class="text-sm font-bold text-gray-700 mb-2">Foto Barang Fisik</p>
                        <div id="detailFoto" class="aspect-square bg-gray-100 rounded-xl overflow-hidden border border-gray-200 flex items-center justify-center shadow-sm"></div>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-700 mb-2">Bukti Penerimaan / Nota</p>
                        <div id="detailNota" class="aspect-square bg-gray-100 rounded-xl overflow-hidden border border-gray-200 flex items-center justify-center shadow-sm"></div>
                    </div>
                </div>
                
                <!-- Kolom Info -->
                <div class="col-span-1 md:col-span-2 bg-gray-50 rounded-xl p-6 border border-gray-200 shadow-sm">
                    <div class="mb-5 border-b border-gray-200 pb-4">
                        <h4 id="detailNama" class="text-3xl font-bold text-gray-900 mb-2 leading-tight"></h4>
                        <div class="flex items-center gap-3">
                            <span id="detailKode" class="text-sm font-mono font-bold text-blue-700 bg-blue-50 border border-blue-200 px-3 py-1 rounded-lg"></span>
                            <span id="detailKondisi" class="text-sm font-bold px-3 py-1 rounded-lg border"></span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-5 gap-x-8 text-sm">
                        <div>
                            <p class="text-gray-500 mb-1 font-semibold uppercase tracking-wider text-[11px]">Merk / Tipe</p>
                            <p id="detailMerk" class="font-bold text-gray-800 text-base"></p>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1 font-semibold uppercase tracking-wider text-[11px]">Kategori Aset</p>
                            <p id="detailKategori" class="font-bold text-gray-800 text-base"></p>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1 font-semibold uppercase tracking-wider text-[11px]">Lokasi Ruangan</p>
                            <div class="flex items-center text-gray-800 font-bold text-base">
                                <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span id="detailLokasi"></span>
                            </div>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1 font-semibold uppercase tracking-wider text-[11px]">Kuantitas / Jumlah</p>
                            <p id="detailJumlah" class="font-bold text-gray-800 text-base"></p>
                        </div>
                        <div class="sm:col-span-2 pt-4 border-t border-gray-200 mt-2">
                            <p class="text-gray-500 mb-1 font-semibold uppercase tracking-wider text-[11px]">Informasi Pengadaan</p>
                            <div class="bg-white p-4 rounded-xl border border-gray-200 mt-2">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-gray-400 text-xs mb-0.5">Supplier / Vendor</p>
                                        <p id="detailSupplier" class="font-bold text-gray-800"></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 text-xs mb-0.5">Sumber Dana</p>
                                        <p id="detailSumber" class="font-bold text-gray-800"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-100 border-t border-gray-200 flex justify-end">
            <button type="button" onclick="closeDetailModal()" class="px-6 py-2.5 bg-gray-800 hover:bg-gray-900 text-white rounded-xl text-sm font-bold shadow-md transition-colors">Tutup Preview</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openPreviewDetail(btn) {
        const data = JSON.parse(btn.getAttribute('data-detail'));
        
        document.getElementById('detailNama').innerText = data.nama;
        document.getElementById('detailKode').innerText = data.kode;
        document.getElementById('detailMerk').innerText = data.merk;
        document.getElementById('detailKategori').innerText = data.kategori;
        document.getElementById('detailLokasi').innerText = data.lokasi;
        
        const kondisiEl = document.getElementById('detailKondisi');
        kondisiEl.innerText = data.kondisi;
        kondisiEl.className = 'text-sm font-bold px-3 py-1 rounded-lg border '; // reset classes
        if(data.kondisi === 'Baik') kondisiEl.className += 'bg-green-100 text-green-800 border-green-200';
        else if(data.kondisi === 'Rusak Ringan') kondisiEl.className += 'bg-yellow-100 text-yellow-800 border-yellow-200';
        else kondisiEl.className += 'bg-red-100 text-red-800 border-red-200';
        
        document.getElementById('detailJumlah').innerText = data.jumlah + ' Unit';
        document.getElementById('detailSumber').innerText = data.sumber;
        document.getElementById('detailSupplier').innerText = data.supplier;
        
        // Foto
        const containerFoto = document.getElementById('detailFoto');
        if(data.foto) {
            containerFoto.innerHTML = `<img src="${data.foto}" class="w-full h-full object-cover cursor-pointer hover:opacity-80 transition-opacity" onclick="openImageModal('${data.foto}')" title="Perbesar">`;
        } else {
            containerFoto.innerHTML = `<div class="text-center"><svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg><span class="text-gray-400 text-xs font-medium">Belum ada foto</span></div>`;
        }
        
        // Nota
        const containerNota = document.getElementById('detailNota');
        if(data.nota) {
            containerNota.innerHTML = `<img src="${data.nota}" class="w-full h-full object-cover cursor-pointer hover:opacity-80 transition-opacity" onclick="openImageModal('${data.nota}')" title="Perbesar">`;
        } else {
            containerNota.innerHTML = `<div class="text-center"><svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg><span class="text-gray-400 text-xs font-medium">Belum ada nota</span></div>`;
        }
        
        document.getElementById('modalDetail').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('modalDetail').classList.add('hidden');
    }

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