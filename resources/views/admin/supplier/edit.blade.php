@extends('layouts.app')
@section('title', 'Edit Supplier')

@section('content')
<div class="mb-6">
    <a href="{{ route('supplier.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors mb-2">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar
    </a>
    <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Edit Supplier</h2>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 max-w-2xl">
    <form action="{{ route('supplier.update', $supplier->id_supplier) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <label for="nama_supplier" class="block text-sm font-semibold text-gray-700 mb-2">Nama Supplier <span class="text-red-500">*</span></label>
            <input type="text" name="nama_supplier" id="nama_supplier" value="{{ $supplier->nama_supplier }}" required 
                class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="kontak_person" class="block text-sm font-semibold text-gray-700 mb-2">Kontak Person <span class="text-gray-400 font-normal">(Opsional)</span></label>
                <input type="text" name="kontak_person" id="kontak_person" value="{{ $supplier->kontak_person }}"
                    class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out">
            </div>
            <div>
                <label for="telepon" class="block text-sm font-semibold text-gray-700 mb-2">Telepon <span class="text-red-500">*</span></label>
                <input type="text" name="telepon" id="telepon" value="{{ $supplier->telepon }}" required
                    class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out">
            </div>
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email <span class="text-gray-400 font-normal">(Opsional)</span></label>
            <input type="email" name="email" id="email" value="{{ $supplier->email }}"
                class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out">
        </div>
        
        <div>
            <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">Alamat <span class="text-red-500">*</span></label>
            <textarea name="alamat" id="alamat" rows="4" required
                class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out resize-y">{{ $supplier->alamat }}</textarea>
        </div>
        
        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-100">
            <a href="{{ route('supplier.index') }}" 
                class="py-2.5 px-5 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition-all duration-200">
                Batal
            </a>
            <button type="submit" 
                class="py-2.5 px-6 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 ease-in-out transform hover:-translate-y-0.5">
                Update Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
