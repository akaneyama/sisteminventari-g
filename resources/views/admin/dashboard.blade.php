@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')

{{-- Header Selamat Datang --}}
<div class="relative bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-lg p-6 mb-6 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 400" class="w-full h-full"><circle cx="700" cy="-50" r="300" fill="white"/><circle cx="50" cy="400" r="200" fill="white"/></svg>
    </div>
    <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <p class="text-blue-100 text-sm font-medium">Selamat datang kembali,</p>
            <h1 class="text-2xl font-bold text-white mt-0.5">{{ auth()->user()->nama_lengkap }} 👋</h1>
            <p class="text-blue-200 text-sm mt-1">{{ now()->translatedFormat('l, d F Y') }} &mdash; Panel Manajemen Inventaris Aset</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('barang.create') }}" class="inline-flex items-center py-2 px-4 rounded-xl text-sm font-semibold text-blue-700 bg-white hover:bg-blue-50 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tambah Barang
            </a>
            <a href="{{ route('laporan.index') }}" class="inline-flex items-center py-2 px-4 rounded-xl text-sm font-semibold text-white bg-white/20 hover:bg-white/30 border border-white/30 transition-all">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Lihat Laporan
            </a>
        </div>
    </div>
</div>

{{-- Kartu Statistik Utama --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="p-3 bg-blue-100 rounded-xl flex-shrink-0">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Total Jenis</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($total_jenis) }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-indigo-100 p-5 flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="p-3 bg-indigo-100 rounded-xl flex-shrink-0">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Total Unit</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($total_unit) }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-green-100 p-5 flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="p-3 bg-green-100 rounded-xl flex-shrink-0">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Kondisi Baik</p>
            <p class="text-2xl font-bold text-green-700">{{ number_format($aset_baik) }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-yellow-100 p-5 flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="p-3 bg-yellow-100 rounded-xl flex-shrink-0">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Rusak Ringan</p>
            <p class="text-2xl font-bold text-yellow-700">{{ number_format($rusak_ringan) }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-5 flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="p-3 bg-red-100 rounded-xl flex-shrink-0">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Rusak Berat</p>
            <p class="text-2xl font-bold text-red-700">{{ number_format($rusak_berat) }}</p>
        </div>
    </div>
</div>

{{-- Row Kedua: Chart + Top Lokasi --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

    {{-- Chart: Trend Mutasi 6 Bulan --}}
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-bold text-gray-800">Tren Mutasi Aset</h3>
                <p class="text-xs text-gray-500 mt-0.5">6 bulan terakhir</p>
            </div>
            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">{{ $mutasi_bulan_ini }} mutasi bulan ini</span>
        </div>
        <canvas id="mutasiChart" height="100"></canvas>
    </div>

    {{-- Top 5 Lokasi --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-bold text-gray-800">Top Lokasi Terpadat</h3>
                <p class="text-xs text-gray-500 mt-0.5">Berdasarkan jumlah unit</p>
            </div>
            <a href="{{ route('lokasi.index') }}" class="text-xs text-blue-600 hover:underline">Lihat semua</a>
        </div>
        @php $maxLokasi = $topLokasi->max('total_unit') ?: 1; @endphp
        <div class="space-y-4">
            @forelse($topLokasi as $i => $lok)
            <div>
                <div class="flex justify-between text-sm mb-1.5">
                    <span class="font-medium text-gray-700 truncate max-w-[160px]">{{ $lok->nama_ruangan }}</span>
                    <span class="font-bold text-gray-900 ml-2">{{ $lok->total_unit }} unit</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="h-2 rounded-full {{ ['bg-blue-500','bg-indigo-500','bg-violet-500','bg-purple-500','bg-sky-500'][$i % 5] }}" style="width: {{ round($lok->total_unit / $maxLokasi * 100) }}%"></div>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">Belum ada data lokasi.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Row Ketiga: Chart Kategori + Chart Kondisi + Mutasi Terbaru --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
    {{-- Pie Kategori --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-base font-bold text-gray-800 mb-1">Sebaran Kategori</h3>
        <p class="text-xs text-gray-500 mb-4">Distribusi unit per kategori</p>
        <canvas id="kategoriChart" height="220"></canvas>
    </div>

    {{-- Bar Kondisi --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-base font-bold text-gray-800 mb-1">Kondisi Aset</h3>
        <p class="text-xs text-gray-500 mb-4">Jumlah unit per kondisi</p>
        <canvas id="kondisiChart" height="220"></canvas>
    </div>

    {{-- Mutasi Terbaru --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold text-gray-800">Mutasi Terbaru</h3>
                <p class="text-xs text-gray-500 mt-0.5">5 pergerakan aset terakhir</p>
            </div>
            <a href="{{ route('mutasi.index') }}" class="text-xs text-blue-600 hover:underline">Lihat semua</a>
        </div>
        <div class="space-y-3 flex-1">
            @forelse($mutasi_terbaru as $m)
            <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                <div class="mt-0.5 flex-shrink-0">
                    @if($m->jenis_mutasi == 'Pindah Lokasi')
                        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center"><svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg></div>
                    @elseif($m->jenis_mutasi == 'Ubah Status')
                        <div class="w-8 h-8 rounded-lg bg-yellow-100 flex items-center justify-center"><svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></div>
                    @else
                        <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center"><svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></div>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $m->barang->nama_barang ?? 'Barang Terhapus' }}</p>
                    <p class="text-xs text-gray-500">{{ $m->jenis_mutasi }} &bull; {{ $m->jumlah }} unit</p>
                </div>
                <span class="text-xs text-gray-400 whitespace-nowrap mt-0.5">{{ \Carbon\Carbon::parse($m->tanggal_mutasi)->format('d/m') }}</span>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center flex-1 py-8 text-center text-gray-400">
                <svg class="w-10 h-10 mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p class="text-sm">Belum ada riwayat mutasi.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Row Bawah: Kartu Ringkasan Master Data --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <a href="{{ route('kategori.index') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4 hover:border-blue-300 hover:shadow-md transition-all group">
        <div class="p-3 bg-slate-100 group-hover:bg-blue-100 rounded-xl transition-colors">
            <svg class="w-5 h-5 text-slate-500 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500">Kategori</p>
            <p class="text-xl font-bold text-gray-900">{{ $total_kategori }}</p>
        </div>
    </a>
    <a href="{{ route('lokasi.index') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4 hover:border-blue-300 hover:shadow-md transition-all group">
        <div class="p-3 bg-slate-100 group-hover:bg-blue-100 rounded-xl transition-colors">
            <svg class="w-5 h-5 text-slate-500 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500">Lokasi</p>
            <p class="text-xl font-bold text-gray-900">{{ $total_lokasi }}</p>
        </div>
    </a>
    <a href="{{ route('supplier.index') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4 hover:border-blue-300 hover:shadow-md transition-all group">
        <div class="p-3 bg-slate-100 group-hover:bg-blue-100 rounded-xl transition-colors">
            <svg class="w-5 h-5 text-slate-500 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500">Supplier</p>
            <p class="text-xl font-bold text-gray-900">{{ $total_supplier }}</p>
        </div>
    </a>
    <a href="{{ route('sumber-dana.index') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4 hover:border-blue-300 hover:shadow-md transition-all group">
        <div class="p-3 bg-slate-100 group-hover:bg-blue-100 rounded-xl transition-colors">
            <svg class="w-5 h-5 text-slate-500 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500">Sumber Dana</p>
            <p class="text-xl font-bold text-gray-900">{{ $total_sumberdana }}</p>
        </div>
    </a>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const waitForChart = setInterval(() => {
        if (!window.Chart) return;
        clearInterval(waitForChart);

        const palette = ['#3b82f6','#6366f1','#8b5cf6','#a855f7','#ec4899','#f43f5e','#f59e0b','#10b981'];

        // ── Tren Mutasi (Line) ──────────────────────────────────────────────
        new window.Chart(document.getElementById('mutasiChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($mutasiLabels),
                datasets: [{
                    label: 'Jumlah Mutasi',
                    data: @json($mutasiValues),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.08)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#3b82f6',
                    pointRadius: 5,
                    fill: true,
                    tension: 0.4,
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

        // ── Pie Kategori ────────────────────────────────────────────────────
        const kData = @json($kategoriData);
        new window.Chart(document.getElementById('kategoriChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: kData.map(d => d.nama_kategori),
                datasets: [{ data: kData.map(d => d.total), backgroundColor: palette, borderWidth: 0, hoverOffset: 6 }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 10, padding: 12, font: { size: 11 } } }
                },
                cutout: '60%'
            }
        });

        // ── Bar Kondisi ─────────────────────────────────────────────────────
        const kdData = @json($kondisiData);
        const colorMap = { 'Baik': '#22c55e', 'Rusak Ringan': '#f59e0b', 'Rusak Berat': '#f43f5e' };
        new window.Chart(document.getElementById('kondisiChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: kdData.map(d => d.kondisi),
                datasets: [{
                    data: kdData.map(d => d.total),
                    backgroundColor: kdData.map(d => colorMap[d.kondisi] ?? '#94a3b8'),
                    borderRadius: 8,
                    borderSkipped: false,
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