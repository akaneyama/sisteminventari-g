@extends('layouts.app')
@section('title', 'Ajukan Perbaikan Aset')

@section('content')
<div class="mb-6">
    <a href="{{ route('barang.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors mb-2">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar Barang
    </a>
    <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Ajukan Perbaikan Aset</h2>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-4">
        @if($barang->foto_barang)
            <img class="h-16 w-16 rounded-lg object-cover border border-gray-200" src="{{ asset('storage/' . $barang->foto_barang) }}" alt="Foto Barang">
        @else
            <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center border border-gray-300 text-gray-400 text-xs font-medium">No Pic</div>
        @endif
        <div>
            <h3 class="text-lg font-bold text-gray-900">{{ $barang->kode_inventaris }} - {{ $barang->nama_barang }}</h3>
            <p class="text-sm text-gray-600">{{ $barang->merk_type }} • Lokasi: {{ $barang->lokasi->nama_ruangan ?? '-' }}</p>
            <p class="text-sm text-red-600 font-semibold mt-1">Kondisi Saat Ini: {{ $barang->kondisi }}</p>
        </div>
    </div>

    <form action="{{ route('perbaikan.store') }}" method="POST">
        @csrf
        <input type="hidden" name="id_barang" value="{{ $barang->id_barang }}">
        
        <div class="grid grid-cols-1 gap-6 mb-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai Perbaikan <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_mulai" value="{{ date('Y-m-d') }}" required class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan / Diagnosa Kerusakan <span class="text-red-500">*</span></label>
                <textarea name="keterangan" rows="4" required placeholder="Jelaskan detail kerusakan atau keluhan..." class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:ring-blue-500 focus:border-blue-500 transition-colors"></textarea>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('barang.index') }}" class="inline-flex items-center justify-center py-2.5 px-6 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-colors">Batal</a>
            <button type="submit" class="inline-flex items-center justify-center py-2.5 px-6 rounded-xl shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-all">Mulai Perbaikan</button>
        </div>
    </form>
</div>
@endsection
