@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-2">Selamat Datang, {{ auth()->user()->nama_lengkap }}!</h2>
    <p class="text-gray-600">Gunakan menu di sebelah kiri untuk mengelola master data, input barang baru, dan mencatat mutasi aset sekolah.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <div class="bg-blue-500 rounded-xl shadow-sm p-6 text-white flex items-center justify-between transition transform hover:-translate-y-1">
        <div>
            <p class="text-blue-100 text-sm font-medium mb-1">Total Aset Terdaftar</p>
            <h3 class="text-3xl font-bold">{{ $total_aset }}</h3>
        </div>
        <div class="p-3 bg-blue-600 rounded-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        </div>
    </div>

    <div class="bg-emerald-500 rounded-xl shadow-sm p-6 text-white flex items-center justify-between transition transform hover:-translate-y-1">
        <div>
            <p class="text-emerald-100 text-sm font-medium mb-1">Kondisi Baik</p>
            <h3 class="text-3xl font-bold">{{ $aset_baik }}</h3>
        </div>
        <div class="p-3 bg-emerald-600 rounded-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>

    <div class="bg-rose-500 rounded-xl shadow-sm p-6 text-white flex items-center justify-between transition transform hover:-translate-y-1">
        <div>
            <p class="text-rose-100 text-sm font-medium mb-1">Kondisi Rusak</p>
            <h3 class="text-3xl font-bold">{{ $aset_rusak }}</h3>
        </div>
        <div class="p-3 bg-rose-600 rounded-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>

</div>
@endsection