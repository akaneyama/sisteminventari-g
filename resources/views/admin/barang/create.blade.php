@extends('layouts.app')
@section('title', 'Buat Pengajuan Barang Baru')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.pengajuan.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors mb-2">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Status Pengajuan
    </a>
    <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Buat Pengajuan Barang Baru</h2>
    <p class="text-sm text-gray-500 mt-1">Isi detail barang yang ingin diajukan. Pengajuan ini akan dikirim ke Kepala Sekolah untuk disetujui sebelum masuk ke inventaris.</p>
</div>

<div class="mb-6 bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-3">
    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    <div>
        <p class="text-sm font-bold text-amber-800">Perlu Persetujuan Kepala Sekolah</p>
        <p class="text-xs text-amber-700 mt-0.5">Barang yang Anda ajukan <strong>tidak akan langsung masuk</strong> ke daftar inventaris. Kepala Sekolah perlu menyetujui pengajuan ini terlebih dahulu.</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
    
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-xl shadow-sm text-sm">
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div>
                    <span class="font-bold">Terdapat beberapa kesalahan:</span>
                    <ul class="list-disc pl-5 mt-1 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Inventaris <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_inventaris" value="{{ old('kode_inventaris') }}" required autofocus
                        class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Barang <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" required 
                        class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Merk / Tipe <span class="text-red-500">*</span></label>
                    <input type="text" name="merk_type" value="{{ old('merk_type') }}" required 
                        class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select name="id_kategori" required 
                        class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id_kategori }}" {{ old('id_kategori') == $k->id_kategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Lokasi Ruangan <span class="text-red-500">*</span></label>
                    <select name="id_lokasi" required 
                        class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out">
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach($lokasi as $l)
                            <option value="{{ $l->id_lokasi }}" {{ old('id_lokasi') == $l->id_lokasi ? 'selected' : '' }}>{{ $l->nama_ruangan }} ({{ $l->gedung }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Perolehan <span class="text-red-500">*</span></label>
                        <input type="number" name="tahun_perolehan" value="{{ old('tahun_perolehan', date('Y')) }}" required 
                            class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kondisi <span class="text-red-500">*</span></label>
                        <select name="kondisi" required 
                            class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out">
                            <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Rusak Berat" {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sumber Dana & Tahun <span class="text-red-500">*</span></label>
                    <select name="id_sumber_dana_new" required 
                        class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out">
                        <option value="">-- Pilih Sumber Dana --</option>
                        @foreach($sumber_dana as $s)
                            <option value="{{ $s->id_sumber_dana }}" {{ old('id_sumber_dana_new') == $s->id_sumber_dana ? 'selected' : '' }}>{{ $s->nama_sumber_dana }} {{ $s->tahun ? '('.$s->tahun.')' : '' }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Supplier <span class="text-red-500">*</span></label>
                        <select name="id_supplier" required 
                            class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($supplier as $sup)
                                <option value="{{ $sup->id_supplier }}" {{ old('id_supplier') == $sup->id_supplier ? 'selected' : '' }}>{{ $sup->nama_supplier }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Barang <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_barang" value="{{ old('jumlah_barang', 1) }}" min="1" required 
                            class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Barang / Contoh Gambar <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <p class="text-xs text-gray-500 mb-3 bg-blue-50 p-2.5 rounded-lg border border-blue-100 text-blue-800">
                        <svg class="w-4 h-4 inline mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <strong>Catatan:</strong> Karena ini pengajuan barang baru, Anda dapat mengunggah <strong>gambar referensi</strong> atau <strong>contoh foto barang</strong> dari internet agar Kepala Sekolah lebih mudah memahaminya. <strong>(Maksimal 2MB)</strong>
                    </p>
                    <input type="file" name="foto_barang" accept="image/*" onchange="previewImage(event)"
                        class="block w-full text-sm text-gray-500 border border-gray-200 rounded-xl bg-gray-50 hover:bg-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-200 ease-in-out file:mr-4 file:py-3 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    <div class="mt-4 hidden" id="preview-container">
                        <p class="text-xs text-gray-500 mb-2">Preview Foto:</p>
                        <img id="image-preview" src="#" alt="Preview" class="h-32 w-32 object-cover rounded-xl border border-gray-200 shadow-sm">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-8 border-t border-gray-100 mt-8">
            <a href="{{ route('admin.pengajuan.index') }}" 
                class="w-full sm:w-auto py-2.5 px-5 text-center border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition-all duration-200">
                Batal
            </a>
            <button type="submit" 
                class="w-full sm:w-auto py-2.5 px-6 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 ease-in-out transform hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-1.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Kirim Pengajuan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewImage(event) {
        var input = event.target;
        var previewContainer = document.getElementById('preview-container');
        var previewImage = document.getElementById('image-preview');
        
        if (input.files && input.files[0]) {
            if (input.files[0].size > 2097152) { // 2MB
                alert('Ukuran foto tidak boleh lebih dari 2MB!');
                input.value = '';
                previewContainer.classList.add('hidden');
                return;
            }

            var reader = new FileReader();
            
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            previewContainer.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection