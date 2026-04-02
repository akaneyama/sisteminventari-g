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

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-gray-800 font-semibold mb-4">Grafik Kategori Barang</h3>
        <canvas id="kategoriChart"></canvas>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-gray-800 font-semibold mb-4">Grafik Kondisi Barang</h3>
        <canvas id="kondisiChart"></canvas>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Tunggu sampai window.Chart tersedia jika app.js di-load secara defer
    setTimeout(() => {
        if (!window.Chart) return;
        
        // Pie Chart Kategori
        const kategoriCtx = document.getElementById('kategoriChart').getContext('2d');
        const kategoriData = @json($kategoriData);
        new window.Chart(kategoriCtx, {
            type: 'pie',
            data: {
                labels: kategoriData.map(item => item.nama_kategori),
                datasets: [{
                    label: 'Jumlah Barang per Kategori',
                    data: kategoriData.map(item => item.total),
                    backgroundColor: [
                        '#3b82f6', '#10b981', '#f43f5e', '#f59e0b', '#8b5cf6', '#06b6d4', '#ec4899'
                    ],
                }]
            }
        });

        // Bar Chart Kondisi
        const kondisiCtx = document.getElementById('kondisiChart').getContext('2d');
        const kondisiData = @json($kondisiData);
        new window.Chart(kondisiCtx, {
            type: 'bar',
            data: {
                labels: kondisiData.map(item => item.kondisi),
                datasets: [{
                    label: 'Jumlah Barang per Kondisi',
                    data: kondisiData.map(item => item.total),
                    backgroundColor: [
                        '#10b981', '#f59e0b', '#f43f5e'
                    ],
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }, 500); // 500ms delay to ensure Vite JS load
});
</script>
@endsection