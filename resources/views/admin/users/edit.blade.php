@extends('layouts.app')
@section('title', 'Edit Pengguna')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <a href="{{ route('users.index') }}" class="p-2 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors text-gray-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Edit Pengguna</h2>
            <p class="text-sm text-gray-500 mt-1">Perbarui data profil atau hak akses akun.</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden max-w-3xl">
    <form action="{{ route('users.update', $user->id_user) }}" method="POST" class="p-6 sm:p-8">
        @csrf
        @method('PUT')
        
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-xl text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500 text-sm transition-colors">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">NIP <span class="text-gray-400 font-normal">(Opsional)</span></label>
                <input type="text" name="nip" value="{{ old('nip', $user->nip) }}" class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500 text-sm transition-colors">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Hak Akses (Role) <span class="text-red-500">*</span></label>
                <select name="role" required class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500 text-sm transition-colors">
                    <option value="Admin" {{ old('role', $user->role) == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Kepsek" {{ old('role', $user->role) == 'Kepsek' ? 'selected' : '' }}>Kepala Sekolah</option>
                </select>
            </div>

            <div class="sm:col-span-2 mt-2">
                <div class="h-px bg-gray-100 w-full"></div>
                <h3 class="text-sm font-bold text-gray-800 mt-4 mb-2">Informasi Akun (Login)</h3>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Username <span class="text-red-500">*</span></label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}" required class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500 text-sm transition-colors">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500 text-sm transition-colors">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password <span class="text-gray-400 font-normal">(Kosongkan jika tidak ingin diubah)</span></label>
                <input type="password" name="password" class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500 text-sm transition-colors" placeholder="Ketik password baru untuk mengubah">
                <p class="text-xs text-gray-500 mt-1">Jika Anda mengisi kolom ini, password lama akan diganti dengan yang baru.</p>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
            <a href="{{ route('users.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-sm transition-all">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
