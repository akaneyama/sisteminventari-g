@extends('layouts.app')
@section('title', 'Identitas Sekolah')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Identitas Sekolah</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola informasi utama dan logo sekolah untuk keperluan laporan dan label.</p>
    </div>
</div>

@if (session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="font-medium text-sm">{{ session('success') }}</span>
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 sm:p-8">
        <form action="{{ route('identitas.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Kolom Kiri: Logo -->
                <div class="lg:col-span-1 space-y-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Logo Sekolah</label>
                    <div class="flex flex-col items-center p-6 border-2 border-dashed border-gray-300 rounded-2xl bg-gray-50 hover:bg-gray-100 transition-colors">
                        @if(isset($identitas) && $identitas->logo)
                            <img src="{{ Storage::disk('public')->url('logos/' . $identitas->logo) }}" alt="Logo Sekolah" class="w-48 h-48 object-contain mb-4 rounded-lg">
                        @else
                            <div class="w-32 h-32 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center mb-4 text-gray-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        <p class="text-xs text-gray-500 text-center mb-3">Format: JPG, PNG, JPEG. Max: 2MB.</p>
                        <input type="file" name="logo_file" id="logo_file" onchange="validateLogo(this)" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer transition-colors" accept="image/*">
                        @error('logo_file')
                            <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Kolom Kanan: Form Data -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="naungan" class="block text-sm font-semibold text-gray-700 mb-1">Institusi / Naungan <span class="text-gray-400 text-xs font-normal">(Opsional)</span></label>
                            <input type="text" name="naungan" id="naungan" value="{{ old('naungan', $identitas->naungan ?? '') }}" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3 border transition-colors @error('naungan') border-red-300 ring-red-500 @enderror" placeholder="Contoh: PEMERINTAH PROVINSI X / YAYASAN XYZ">
                            @error('naungan')<p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="nama_sekolah" class="block text-sm font-semibold text-gray-700 mb-1">Nama Sekolah <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_sekolah" id="nama_sekolah" value="{{ old('nama_sekolah', $identitas->nama_sekolah ?? '') }}" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3 border transition-colors @error('nama_sekolah') border-red-300 ring-red-500 @enderror" required placeholder="Contoh: SMA Negeri 1 Contoh">
                            @error('nama_sekolah')<p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="alamat" id="alamat" rows="3" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3 border transition-colors @error('alamat') border-red-300 ring-red-500 @enderror" required placeholder="Jalan Raya No. 123, Kelurahan, Kecamatan, Kota...">{{ old('alamat', $identitas->alamat ?? '') }}</textarea>
                            @error('alamat')<p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email Sekolah</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $identitas->email ?? '') }}" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3 border transition-colors" placeholder="info@sekolah.sch.id">
                        </div>

                        <div>
                            <label for="telepon" class="block text-sm font-semibold text-gray-700 mb-1">Telepon</label>
                            <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $identitas->telepon ?? '') }}" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3 border transition-colors" placeholder="(021) 1234567">
                        </div>

                        <div>
                            <label for="website" class="block text-sm font-semibold text-gray-700 mb-1">Website</label>
                            <input type="text" name="website" id="website" value="{{ old('website', $identitas->website ?? '') }}" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3 border transition-colors" placeholder="www.sekolah.sch.id">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100">
                        <h3 class="text-sm font-bold text-gray-800 mb-4">Informasi Kepala Sekolah</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nama_kepala_sekolah" class="block text-sm font-semibold text-gray-700 mb-1">Nama Kepala Sekolah</label>
                                <input type="text" name="nama_kepala_sekolah" id="nama_kepala_sekolah" value="{{ old('nama_kepala_sekolah', $identitas->nama_kepala_sekolah ?? '') }}" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3 border transition-colors" placeholder="Nama Lengkap beserta gelar">
                            </div>

                            <div>
                                <label for="nip_kepala_sekolah" class="block text-sm font-semibold text-gray-700 mb-1">NIP Kepala Sekolah</label>
                                <input type="text" name="nip_kepala_sekolah" id="nip_kepala_sekolah" value="{{ old('nip_kepala_sekolah', $identitas->nip_kepala_sekolah ?? '') }}" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3 border transition-colors" placeholder="NIP (jika ada)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function validateLogo(input) {
        if (input.files && input.files[0]) {
            if (input.files[0].size > 2097152) { // 2MB
                alert('Ukuran foto logo tidak boleh lebih dari 2MB!');
                input.value = '';
            }
        }
    }
</script>
@endsection
