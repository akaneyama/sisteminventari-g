@extends('layouts.app')
@section('title', 'Proses Mutasi Barang')

@section('content')
<div class="mb-6">
    <a href="{{ route('mutasi.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors mb-2">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar Riwayat
    </a>
    <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Proses Mutasi Barang</h2>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 max-w-3xl">
    
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

    <form action="{{ route('mutasi.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mutasi <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_mutasi" value="{{ date('Y-m-d') }}" required 
                    class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Barang <span class="text-red-500">*</span></label>
                <select name="id_barang" id="id_barang" required onchange="updateMaxJumlah()"
                    class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out">
                    <option value="" data-jumlah="0">-- Cari Barang --</option>
                    @foreach($barang as $b)
                        <option value="{{ $b->id_barang }}" data-jumlah="{{ $b->jumlah_barang }}">{{ $b->kode_inventaris }} - {{ $b->nama_barang }} (Lok: {{ $b->lokasi->nama_ruangan ?? '-' }}, Sisa: {{ $b->jumlah_barang }})</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Dimutasi <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah" id="jumlah" min="1" value="1" required 
                    class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out">
                <p class="text-xs text-gray-500 mt-1" id="max_jumlah_info">Pilih barang untuk melihat limit maksimal mutasi.</p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Mutasi <span class="text-red-500">*</span></label>
            <select name="jenis_mutasi" id="jenis_mutasi" required onchange="toggleFields()"
                class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out">
                <option value="">-- Pilih Tindakan --</option>
                <option value="Pindah Lokasi">Pindah Lokasi Ruangan</option>
                <option value="Ubah Status">Perubahan Kondisi Barang</option>
                <option value="Penghapusan">Penghapusan Aset (Rusak Total/Hilang)</option>
            </select>
        </div>

        <div id="field_lokasi" class="hidden bg-blue-50/50 p-5 rounded-xl border border-blue-100 transition-all">
            <label class="block text-sm font-semibold text-blue-900 mb-2">Pindah ke Lokasi Tujuan <span class="text-red-500">*</span></label>
            <select name="lokasi_tujuan" id="lokasi_tujuan" 
                class="block w-full px-4 py-3 border border-blue-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out">
                <option value="">-- Pilih Lokasi Baru --</option>
                @foreach($lokasi as $l)
                    <option value="{{ $l->id_lokasi }}">{{ $l->nama_ruangan }} ({{ $l->gedung }})</option>
                @endforeach
            </select>
        </div>

        <div id="field_status" class="hidden bg-yellow-50/50 p-5 rounded-xl border border-yellow-100 transition-all">
            <label class="block text-sm font-semibold text-yellow-900 mb-2">Kondisi Baru (Sesudah) <span class="text-red-500">*</span></label>
            <select name="kondisi_sesudah" id="kondisi_sesudah" 
                class="block w-full px-4 py-3 border border-yellow-200 rounded-xl focus:ring-yellow-500 focus:border-yellow-500 bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out">
                <option value="">-- Set Kondisi Baru --</option>
                <option value="Baik">Baik</option>
                <option value="Rusak Ringan">Rusak Ringan</option>
                <option value="Rusak Berat">Rusak Berat</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan / Alasan Mutasi <span class="text-red-500">*</span></label>
            <textarea name="keterangan" rows="3" required placeholder="Contoh: Dipindah karena lab direnovasi, atau dihapus karena dicuri." 
                class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white text-gray-900 sm:text-sm transition duration-200 ease-in-out resize-y">{{ old('keterangan') }}</textarea>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-6 border-t border-gray-100">
            <a href="{{ route('mutasi.index') }}" 
                class="w-full sm:w-auto py-2.5 px-5 text-center border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition-all duration-200">
                Batal
            </a>
            <button type="submit" 
                class="w-full sm:w-auto py-2.5 px-6 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 ease-in-out transform hover:-translate-y-0.5">
                Proses Mutasi
            </button>
        </div>
    </form>
</div>

<script>
    function toggleFields() {
        const jenis = document.getElementById('jenis_mutasi').value;
        const fieldLokasi = document.getElementById('field_lokasi');
        const fieldStatus = document.getElementById('field_status');
        
        // Sembunyikan semua dulu
        fieldLokasi.classList.add('hidden');
        fieldStatus.classList.add('hidden');
        
        // Reset required attr
        document.getElementById('lokasi_tujuan').required = false;
        document.getElementById('kondisi_sesudah').required = false;

        // Tampilkan sesuai pilihan
        if (jenis === 'Pindah Lokasi') {
            fieldLokasi.classList.remove('hidden');
            document.getElementById('lokasi_tujuan').required = true;
        } else if (jenis === 'Ubah Status') {
            fieldStatus.classList.remove('hidden');
            document.getElementById('kondisi_sesudah').required = true;
        }
    }
    
    function updateMaxJumlah() {
        const selectBarang = document.getElementById('id_barang');
        const inputJumlah = document.getElementById('jumlah');
        const infoMax = document.getElementById('max_jumlah_info');
        
        if(selectBarang.selectedIndex > 0) {
            const selectedOption = selectBarang.options[selectBarang.selectedIndex];
            const max = parseInt(selectedOption.getAttribute('data-jumlah'));
            
            inputJumlah.max = max;
            if(parseInt(inputJumlah.value) > max) {
                inputJumlah.value = max;
            }
            infoMax.textContent = `Maksimal barang yang bisa dimutasi: ${max} unit`;
        } else {
            inputJumlah.max = "";
            infoMax.textContent = `Pilih barang untuk melihat limit maksimal mutasi.`;
        }
    }
</script>
@endsection