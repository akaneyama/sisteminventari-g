{{-- Shared onboarding card for first-time users (design.md UX). --}}
{{-- Variants: default = Admin setup checklist; 'kepsek' = informational card. --}}

@if(isset($role) && $role === 'kepsek')

<div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col sm:flex-row items-start sm:items-center gap-4">
    <div class="flex-shrink-0 p-3 bg-emerald-100 rounded-xl">
        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
    </div>
    <div class="flex-1">
        <h3 class="text-base font-bold text-gray-800">Data inventaris belum tersedia</h3>
        <p class="text-sm text-gray-500 mt-1">Inventaris aset sekolah masih dalam proses penyusunan oleh Admin. Setelah data masuk, ringkasan dan laporan akan tampil di sini.</p>
    </div>
    <a href="{{ route('laporan.index') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 transition-colors flex-shrink-0">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Lihat Laporan
    </a>
</div>

@else

@php
    $identitasAda = \App\Models\IdentitasSekolah::exists();
    $steps = [
        ['Identitas Sekolah', 'Lengkapi identitas sekolah Anda.', route('identitas.index'), $identitasAda],
        ['Data Kategori', 'Buat kategori untuk mengelompokkan aset.', route('kategori.index'), ($total_kategori ?? 0) > 0],
        ['Data Lokasi', 'Daftarkan ruangan atau lokasi penyimpanan.', route('lokasi.index'), ($total_lokasi ?? 0) > 0],
        ['Sumber Dana & Tahun', 'Atur sumber dana dan tahun anggaran.', route('sumber-dana.index'), ($total_sumberdana ?? 0) > 0],
        ['Data Supplier', 'Catat vendor atau pemasok barang.', route('supplier.index'), ($total_supplier ?? 0) > 0],
        ['Tambah Barang', 'Mulai menginput aset inventaris.', route('barang.create'), ($total_jenis ?? 0) > 0],
    ];
    $selesai = collect($steps)->where(3, true)->count();
    $persen = count($steps) > 0 ? round($selesai / count($steps) * 100) : 0;
@endphp

<div class="mb-6 bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">
    <div class="p-6 sm:p-8 bg-gradient-to-r from-blue-600 to-indigo-700">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-blue-200 text-xs font-semibold uppercase tracking-wider">Mulai di sini</p>
                <h3 class="text-xl font-bold text-white mt-1">Siapkan sistem inventaris Anda</h3>
                <p class="text-sm text-blue-100 mt-1">Lengkapi langkah berikut agar data aset siap dikelola.</p>
            </div>
            <div class="flex-shrink-0 p-3 bg-white/10 rounded-xl">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
        </div>
        <div class="mt-5">
            <div class="flex items-center justify-between text-xs font-semibold text-blue-100 mb-1.5">
                <span>{{ $selesai }} dari {{ count($steps) }} langkah selesai</span>
                <span>{{ $persen }}%</span>
            </div>
            <div class="w-full bg-white/20 rounded-full h-2 overflow-hidden">
                <div class="h-2 bg-white rounded-full transition-all" style="width: {{ $persen }}%"></div>
            </div>
        </div>
    </div>

    <ul class="divide-y divide-gray-100">
        @foreach($steps as $i => $step)
        <li class="px-6 sm:px-8 py-4 flex items-center gap-4 {{ $step[3] ? 'bg-green-50/40' : '' }}">
            @if($step[3])
                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </span>
            @else
                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-bold">{{ $i + 1 }}</span>
            @endif
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-800">{{ $step[0] }}</p>
                <p class="text-xs text-gray-500">{{ $step[1] }}</p>
            </div>
            @if(!$step[3])
                <a href="{{ $step[2] }}" class="flex-shrink-0 inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-100 transition-colors">
                    Lengkapi
                    <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="flex-shrink-0 text-xs font-semibold text-green-700">Selesai</span>
            @endif
        </li>
        @endforeach
    </ul>
</div>

@endif
