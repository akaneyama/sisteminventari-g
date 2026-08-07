<div id="sidebarBackdrop" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-20 hidden transition-opacity lg:hidden"></div>

<aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-100 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static flex flex-col justify-between h-full shadow-[4px_0_24px_rgba(0,0,0,0.02)]">

    <div class="overflow-y-auto flex-1 flex flex-col">
        <div class="h-20 flex items-center px-6 border-b border-gray-100">
            <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-blue-100 text-blue-600 mr-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <span class="text-xl font-extrabold text-gray-800 tracking-tight">Si<span class="text-blue-600">Ventaris</span></span>
        </div>

        <nav class="flex-1 px-4 py-6 overflow-y-auto">
            @php
                $activeClass = 'group flex items-center px-4 py-3 text-sm font-semibold rounded-xl text-blue-700 bg-blue-50 transition-colors border-l-4 border-blue-600';
                $inactiveClass = 'group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors border-l-4 border-transparent';
                $activeIcon = 'w-5 h-5 mr-3 text-blue-600';
                $inactiveIcon = 'w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-600';
            @endphp

            <ul class="space-y-1">
                <li>
                    <a href="{{ auth()->user()->role === 'Admin' ? url('/admin/dashboard') : url('/kepsek/dashboard') }}"
                       class="{{ request()->is('admin/dashboard') || request()->is('kepsek/dashboard') ? $activeClass : $inactiveClass }}">
                        <svg class="{{ request()->is('admin/dashboard') || request()->is('kepsek/dashboard') ? $activeIcon : $inactiveIcon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                </li>

                @if(auth()->user()->role === 'Admin')
                    <li>
                        <button type="button" data-menu-toggle aria-expanded="false" class="w-full flex items-center justify-between px-4 pt-6 pb-2 text-xs font-bold text-gray-400 uppercase tracking-wider hover:text-gray-600 transition-colors">
                            <span>Master Data</span>
                            <svg data-menu-chevron class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <ul data-menu-body class="hidden space-y-1 ml-3">
                            <li>
                                <a href="{{ route('kategori.index') }}" class="{{ request()->routeIs('kategori.*') ? $activeClass : $inactiveClass }}">
                                    <svg class="{{ request()->routeIs('kategori.*') ? $activeIcon : $inactiveIcon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    Data Kategori
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('lokasi.index') }}" class="{{ request()->routeIs('lokasi.*') ? $activeClass : $inactiveClass }}">
                                    <svg class="{{ request()->routeIs('lokasi.*') ? $activeIcon : $inactiveIcon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Data Lokasi
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('sumber-dana.index') }}" class="{{ request()->routeIs('sumber-dana.*') ? $activeClass : $inactiveClass }}">
                                    <svg class="{{ request()->routeIs('sumber-dana.*') ? $activeIcon : $inactiveIcon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Sumber Dana & Tahun
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('supplier.index') }}" class="{{ request()->routeIs('supplier.*') ? $activeClass : $inactiveClass }}">
                                    <svg class="{{ request()->routeIs('supplier.*') ? $activeIcon : $inactiveIcon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Data Supplier
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('identitas.index') }}" class="{{ request()->routeIs('identitas.*') ? $activeClass : $inactiveClass }}">
                                    <svg class="{{ request()->routeIs('identitas.*') ? $activeIcon : $inactiveIcon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    Identitas Sekolah
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? $activeClass : $inactiveClass }}">
                                    <svg class="{{ request()->routeIs('users.*') ? $activeIcon : $inactiveIcon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Manajemen Pengguna
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <button type="button" data-menu-toggle aria-expanded="false" class="w-full flex items-center justify-between px-4 pt-6 pb-2 text-xs font-bold text-gray-400 uppercase tracking-wider hover:text-gray-600 transition-colors">
                            <span>Inventaris</span>
                            <svg data-menu-chevron class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <ul data-menu-body class="hidden space-y-1 ml-3">
                            <li>
                                <a href="{{ route('barang.index') }}" class="{{ request()->routeIs('barang.*') ? $activeClass : $inactiveClass }}">
                                    <svg class="{{ request()->routeIs('barang.*') ? $activeIcon : $inactiveIcon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                    Data Barang
                                    <span id="badge-admin-barang" class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full hidden shadow-sm animate-pulse">0</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.pengajuan.index') }}" class="{{ request()->routeIs('admin.pengajuan.*') ? $activeClass : $inactiveClass }}">
                                    <svg class="{{ request()->routeIs('admin.pengajuan.*') ? $activeIcon : $inactiveIcon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Status Pengajuan
                                    <span id="badge-admin-pengajuan" class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full hidden shadow-sm animate-pulse">0</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('perbaikan.index') }}" class="{{ request()->routeIs('perbaikan.*') ? $activeClass : $inactiveClass }}">
                                    <svg class="{{ request()->routeIs('perbaikan.*') ? $activeIcon : $inactiveIcon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Perbaikan Aset
                                    <span id="badge-admin-perbaikan" class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full hidden shadow-sm animate-pulse">0</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('mutasi.index') }}" class="{{ request()->routeIs('mutasi.*') ? $activeClass : $inactiveClass }}">
                                    <svg class="{{ request()->routeIs('mutasi.*') ? $activeIcon : $inactiveIcon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                    Mutasi Barang
                                    <span id="badge-admin-mutasi" class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full hidden shadow-sm animate-pulse">0</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @else
                    <li>
                        <button type="button" data-menu-toggle aria-expanded="false" class="w-full flex items-center justify-between px-4 pt-6 pb-2 text-xs font-bold text-gray-400 uppercase tracking-wider hover:text-gray-600 transition-colors">
                            <span>Inventaris</span>
                            <svg data-menu-chevron class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <ul data-menu-body class="hidden space-y-1 ml-3">
                            <li>
                                <a href="{{ route('kepsek.barang.index') }}" class="{{ request()->routeIs('kepsek.barang.*') ? $activeClass : $inactiveClass }}">
                                    <svg class="{{ request()->routeIs('kepsek.barang.*') ? $activeIcon : $inactiveIcon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                    Data Barang
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('kepsek.mutasi.index') }}" class="{{ request()->routeIs('kepsek.mutasi.*') ? $activeClass : $inactiveClass }}">
                                    <svg class="{{ request()->routeIs('kepsek.mutasi.*') ? $activeIcon : $inactiveIcon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                    Riwayat Mutasi
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <button type="button" data-menu-toggle aria-expanded="false" class="w-full flex items-center justify-between px-4 pt-6 pb-2 text-xs font-bold text-gray-400 uppercase tracking-wider hover:text-gray-600 transition-colors">
                            <span>Persetujuan</span>
                            <svg data-menu-chevron class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <ul data-menu-body class="hidden space-y-1 ml-3">
                            <li>
                                <a href="{{ route('kepsek.approval.pengadaan') }}" class="{{ request()->routeIs('kepsek.approval.pengadaan*') ? $activeClass : $inactiveClass }}">
                                    <svg class="{{ request()->routeIs('kepsek.approval.pengadaan*') ? $activeIcon : $inactiveIcon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Persetujuan Pengadaan
                                    <span id="badge-pengadaan" class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full hidden shadow-sm animate-pulse">0</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('kepsek.approval.perubahan') }}" class="{{ request()->routeIs('kepsek.approval.perubahan*') ? $activeClass : $inactiveClass }}">
                                    <svg class="{{ request()->routeIs('kepsek.approval.perubahan*') ? $activeIcon : $inactiveIcon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Persetujuan Perubahan Data
                                    <span id="badge-perubahan" class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full hidden shadow-sm animate-pulse">0</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('kepsek.approval.mutasi') }}" class="{{ request()->routeIs('kepsek.approval.mutasi*') ? $activeClass : $inactiveClass }}">
                                    <svg class="{{ request()->routeIs('kepsek.approval.mutasi*') ? $activeIcon : $inactiveIcon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                    Persetujuan Mutasi
                                    <span id="badge-mutasi" class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full hidden shadow-sm animate-pulse">0</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('kepsek.approval.index') }}" class="{{ request()->routeIs('kepsek.approval.index') ? $activeClass : $inactiveClass }}">
                                    <svg class="{{ request()->routeIs('kepsek.approval.index') ? $activeIcon : $inactiveIcon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Persetujuan Penghapusan
                                    <span id="badge-penghapusan" class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full hidden shadow-sm animate-pulse">0</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                <li>
                    <button type="button" data-menu-toggle aria-expanded="false" class="w-full flex items-center justify-between px-4 pt-6 pb-2 text-xs font-bold text-gray-400 uppercase tracking-wider hover:text-gray-600 transition-colors">
                        <span>Laporan</span>
                        <svg data-menu-chevron class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <ul data-menu-body class="hidden space-y-1 ml-3">
                        <li>
                            <a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.index') ? $activeClass : $inactiveClass }}">
                                <svg class="{{ request()->routeIs('laporan.index') ? $activeIcon : $inactiveIcon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Laporan Inventaris
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('laporan.evaluasi.index') }}" class="{{ request()->routeIs('laporan.evaluasi.index') ? $activeClass : $inactiveClass }}">
                                <svg class="{{ request()->routeIs('laporan.evaluasi.index') ? $activeIcon : $inactiveIcon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Tabel Evaluasi
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
