@extends('layouts.app')
@section('title', 'Dashboard Kepala Sekolah')

@section('content')

{{-- Header --}}
<div class="relative bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl shadow-lg p-6 mb-6 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 400" class="w-full h-full"><circle cx="700" cy="-50" r="300" fill="white"/><circle cx="50" cy="400" r="200" fill="white"/></svg>
    </div>
    <div class="relative">
        <p class="text-emerald-100 text-sm font-medium">Selamat datang,</p>
        <h1 class="text-2xl font-bold text-white mt-0.5">{{ auth()->user()->nama_lengkap }} 👋</h1>
        <p class="text-emerald-200 text-sm mt-1">{{ now()->translatedFormat('l, d F Y') }} &mdash; Ringkasan Inventaris Aset Sekolah</p>
    </div>
</div>

{{-- Kartu Statistik --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-blue-100 p-5 flex items-center gap-4">
        <div class="p-3 bg-blue-100 rounded-xl flex-shrink-0">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Total Jenis</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($total_jenis) }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-indigo-100 p-5 flex items-center gap-4">
        <div class="p-3 bg-indigo-100 rounded-xl flex-shrink-0">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Total Unit</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($total_unit) }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-green-100 p-5 flex items-center gap-4">
        <div class="p-3 bg-green-100 rounded-xl flex-shrink-0">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Kondisi Baik</p>
            <p class="text-2xl font-bold text-green-700">{{ number_format($aset_baik) }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-5 flex items-center gap-4">
        <div class="p-3 bg-red-100 rounded-xl flex-shrink-0">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Kondisi Rusak</p>
            <p class="text-2xl font-bold text-red-600">{{ number_format($aset_rusak) }}</p>
        </div>
    </div>
</div>

{{-- Chart Kategori + Kondisi --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-base font-bold text-gray-800 mb-1">Sebaran Kategori Aset</h3>
        <p class="text-xs text-gray-500 mb-4">Jumlah unit per kategori barang</p>
        <canvas id="kategoriChart" height="220"></canvas>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-base font-bold text-gray-800 mb-1">Kondisi Aset</h3>
        <p class="text-xs text-gray-500 mb-4">Persentase kondisi seluruh aset</p>
        <canvas id="kondisiChart" height="220"></canvas>
    </div>
</div>

{{-- Persentase kondisi aset --}}
@php
    $pct_baik = $total_unit > 0 ? round($aset_baik / $total_unit * 100) : 0;
    $pct_rusak = 100 - $pct_baik;
@endphp
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h3 class="text-base font-bold text-gray-800 mb-4">Tingkat Kesehatan Aset</h3>
    <div class="flex items-center gap-4 mb-3">
        <span class="text-sm font-semibold text-gray-700 w-28">Kondisi Baik</span>
        <div class="flex-1 bg-gray-100 rounded-full h-3">
            <div class="h-3 rounded-full bg-green-500 transition-all" style="width: {{ $pct_baik }}%"></div>
        </div>
        <span class="text-sm font-bold text-green-700 w-12 text-right">{{ $pct_baik }}%</span>
    </div>
    <div class="flex items-center gap-4">
        <span class="text-sm font-semibold text-gray-700 w-28">Kondisi Rusak</span>
        <div class="flex-1 bg-gray-100 rounded-full h-3">
            <div class="h-3 rounded-full bg-red-400 transition-all" style="width: {{ $pct_rusak }}%"></div>
        </div>
        <span class="text-sm font-bold text-red-600 w-12 text-right">{{ $pct_rusak }}%</span>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const waitForChart = setInterval(() => {
        if (!window.Chart) return;
        clearInterval(waitForChart);

        const palette = ['#3b82f6','#6366f1','#8b5cf6','#a855f7','#ec4899','#f43f5e','#f59e0b','#10b981'];

        // Donut Kategori
        const kData = @json($kategoriData);
        new Chart(document.getElementById('kategoriChart'), {
            type: 'doughnut',
            data: {
                labels: kData.map(d => d.nama_kategori),
                datasets: [{ data: kData.map(d => d.total), backgroundColor: palette, borderWidth: 0, hoverOffset: 6 }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 12, font: { size: 11 } } } },
                cutout: '60%'
            }
        });

        // Bar Kondisi
        const kdData = @json($kondisiData);
        const colorMap = { 'Baik': '#22c55e', 'Rusak Ringan': '#f59e0b', 'Rusak Berat': '#f43f5e' };
        new Chart(document.getElementById('kondisiChart'), {
            type: 'bar',
            data: {
                labels: kdData.map(d => d.kondisi),
                datasets: [{
                    data: kdData.map(d => d.total),
                    backgroundColor: kdData.map(d => colorMap[d.kondisi] ?? '#94a3b8'),
                    borderRadius: 8, borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }, 200);
});
</script>
@endpush

@endsection