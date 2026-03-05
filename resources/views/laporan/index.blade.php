@extends('layouts.app')
@section('title', 'Laporan Rekapitulasi Inventaris')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 border-b border-gray-100 pb-6">
        <form action="{{ route('laporan.index') }}" method="GET" class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
            <select name="id_lokasi" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Semua Lokasi --</option>
                @foreach($lokasi as $l)
                    <option value="{{ $l->id_lokasi }}" {{ request('id_lokasi') == $l->id_lokasi ? 'selected' : '' }}>{{ $l->nama_ruangan }}</option>
                @endforeach
            </select>

            <select name="kondisi" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Semua Kondisi --</option>
                <option value="Baik" {{ request('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                <option value="Rusak Ringan" {{ request('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="Rusak Berat" {{ request('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
            </select>

            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-900">Filter Data</button>
            @if(request()->has('id_lokasi') || request()->has('kondisi'))
                <a href="{{ route('laporan.index') }}" class="text-gray-500 text-sm px-2 py-2 hover:text-gray-800">Reset</a>
            @endif
        </form>

        <div class="flex gap-2">
            <a href="{{ route('laporan.pdf', request()->query()) }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path></svg>
                Export PDF
            </a>
            <a href="{{ route('laporan.excel', request()->query()) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                Export Excel
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Kode</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Nama Barang</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Ruangan</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Kondisi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($barang as $item)
                <tr>
                    <td class="px-4 py-3 font-semibold">{{ $item->kode_inventaris }}</td>
                    <td class="px-4 py-3">{{ $item->nama_barang }} <br><span class="text-xs text-gray-400">{{ $item->merk_type }}</span></td>
                    <td class="px-4 py-3">{{ $item->lokasi->nama_ruangan ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $item->kondisi }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-4 text-gray-500">Data tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection