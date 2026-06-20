@extends('layouts.app')
@section('title', 'Tabel Evaluasi Laporan')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Tabel Evaluasi Laporan</h2>
    <p class="text-sm text-gray-500 mt-1">Daftar evaluasi dan instruksi yang telah diberikan oleh Kepala Sekolah.</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    {{-- Filter & Export Bar --}}
    <div class="p-5 sm:p-6 border-b border-gray-100 bg-gray-50/50">
        <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-5">
            <form action="{{ route('laporan.evaluasi.index') }}" method="GET" class="flex flex-col sm:flex-row flex-wrap gap-3 w-full xl:w-auto">
                <select name="tahun" class="block w-full sm:w-32 px-4 py-2.5 border border-gray-200 rounded-xl bg-white text-gray-700 sm:text-sm transition duration-200">
                    <option value="">-- Tahun --</option>
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>

                <select name="semester" class="block w-full sm:w-40 px-4 py-2.5 border border-gray-200 rounded-xl bg-white text-gray-700 sm:text-sm transition duration-200">
                    <option value="">-- Semester --</option>
                    <option value="Semester Genap" {{ request('semester') == 'Semester Genap' ? 'selected' : '' }}>Semester Genap</option>
                    <option value="Semester Ganjil" {{ request('semester') == 'Semester Ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                </select>
                
                <select name="status" class="block w-full sm:w-auto px-4 py-2.5 border border-gray-200 rounded-xl bg-white text-gray-700 sm:text-sm transition duration-200">
                    <option value="">-- Semua Status --</option>
                    <option value="Belum Dibaca" {{ request('status') == 'Belum Dibaca' ? 'selected' : '' }}>Belum Dibaca</option>
                    <option value="Sudah Dibaca" {{ request('status') == 'Sudah Dibaca' ? 'selected' : '' }}>Sudah Dibaca</option>
                </select>

                <div class="flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center py-2.5 px-5 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-gray-800 hover:bg-gray-900 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    @if(request()->hasAny(['tahun', 'semester', 'status']))
                        <a href="{{ route('laporan.evaluasi.index') }}" class="inline-flex items-center py-2.5 px-4 text-sm font-medium text-gray-500 hover:text-red-600 bg-white border border-gray-200 hover:border-red-200 hover:bg-red-50 rounded-xl transition-all duration-200" title="Reset Filter">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>
            </form>

            <div class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto pt-4 xl:pt-0 border-t border-gray-200 xl:border-t-0">
                <a href="{{ route('laporan.evaluasi.pdf', request()->query()) }}" target="_blank" class="inline-flex items-center justify-center py-2.5 px-5 rounded-xl shadow-sm text-sm font-semibold text-white bg-red-500 hover:bg-red-600 transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                    Export PDF
                </a>
                <a href="{{ route('laporan.evaluasi.excel', request()->query()) }}" class="inline-flex items-center justify-center py-2.5 px-5 rounded-xl shadow-sm text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    Export Excel
                </a>
            </div>
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-white">
                <tr>
                    <th scope="col" class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-8">No</th>
                    <th scope="col" class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Periode</th>
                    <th scope="col" class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Catatan/Instruksi</th>
                    <th scope="col" class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Dikirim</th>
                    @if(auth()->user()->role === 'Admin')
                    <th scope="col" class="px-5 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($evaluasis as $item)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-4 whitespace-nowrap text-xs text-gray-400">{{ $loop->iteration }}</td>
                    <td class="px-5 py-4 whitespace-nowrap text-sm font-bold text-gray-800">{{ $item->periode }}</td>
                    <td class="px-5 py-4 text-sm text-gray-600 whitespace-pre-wrap">{{ $item->catatan }}</td>
                    <td class="px-5 py-4 whitespace-nowrap text-sm">
                        @if($item->status == 'Sudah Dibaca')
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">Sudah Dibaca</span>
                        @else
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">Belum Dibaca</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    @if(auth()->user()->role === 'Admin')
                    <td class="px-5 py-4 whitespace-nowrap text-right text-sm font-medium">
                        @if($item->status == 'Belum Dibaca')
                        <form action="{{ route('admin.evaluasi.read', $item->id_evaluasi) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded hover:bg-blue-100">Tandai Dibaca</button>
                        </form>
                        @endif
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->role === 'Admin' ? '6' : '5' }}" class="px-6 py-16 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-base font-medium text-gray-600">Belum ada data evaluasi.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
