@extends('layouts.app')
@section('title', 'Laporan Rekapitulasi Inventaris')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Laporan Rekapitulasi Inventaris</h2>
    <p class="text-sm text-gray-500 mt-1">Filter, pantau, dan unduh data keseluruhan aset inventaris Anda.</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    
    <div class="p-5 sm:p-6 border-b border-gray-100 bg-gray-50/50">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-5">
            
            <form action="{{ route('laporan.index') }}" method="GET" class="flex flex-col sm:flex-row flex-wrap gap-3 w-full lg:w-auto">
                <select name="id_lokasi" class="block w-full sm:w-auto px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-700 sm:text-sm transition duration-200">
                    <option value="">-- Semua Lokasi --</option>
                    @foreach($lokasi as $l)
                        <option value="{{ $l->id_lokasi }}" {{ request('id_lokasi') == $l->id_lokasi ? 'selected' : '' }}>
                            {{ $l->nama_ruangan }}
                        </option>
                    @endforeach
                </select>

                <select name="kondisi" class="block w-full sm:w-auto px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-700 sm:text-sm transition duration-200">
                    <option value="">-- Semua Kondisi --</option>
                    <option value="Baik" {{ request('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Rusak Ringan" {{ request('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="Rusak Berat" {{ request('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>

                <div class="flex items-center gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                    <button type="submit" class="flex-1 sm:flex-none inline-flex justify-center items-center py-2.5 px-5 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filter
                    </button>
                    
                    @if(request()->has('id_lokasi') || request()->has('kondisi'))
                        <a href="{{ route('laporan.index') }}" class="inline-flex justify-center items-center py-2.5 px-4 text-sm font-medium text-gray-500 hover:text-red-600 bg-white border border-gray-200 hover:border-red-200 hover:bg-red-50 rounded-xl transition-all duration-200" title="Reset Filter">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </div>
            </form>

            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto pt-4 lg:pt-0 border-t border-gray-200 lg:border-t-0">
                <a href="{{ route('laporan.pdf', request()->query()) }}" target="_blank" class="inline-flex items-center justify-center py-2.5 px-5 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                    Export PDF
                </a>
                <a href="{{ route('laporan.excel', request()->query()) }}" class="inline-flex items-center justify-center py-2.5 px-5 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    Export Excel
                </a>
            </div>
            
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-white">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Kode</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Barang</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Ruangan</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kondisi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($barang as $item)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                        {{ $item->kode_inventaris }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-semibold text-gray-900">{{ $item->nama_barang }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $item->merk_type }}</div>
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
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-16 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <p class="text-base font-medium text-gray-600">Data tidak ditemukan.</p>
                        <p class="text-sm mt-1">Coba sesuaikan filter pencarian Anda.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection