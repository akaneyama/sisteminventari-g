<header class="h-20 bg-white/80 backdrop-blur-md flex items-center justify-between px-6 lg:px-10 border-b border-gray-100 z-10">
    <div class="flex items-center min-w-0">
        <button id="mobileMenuBtn" class="lg:hidden mr-4 p-2 rounded-lg text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors" aria-label="Buka menu navigasi">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        {{-- Pencarian Global --}}
        <form action="{{ auth()->user()->role === 'Admin' ? route('barang.index') : route('kepsek.barang.index') }}" method="GET" class="hidden md:flex items-center relative ml-2">
            <svg class="absolute left-3 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode / nama barang..." aria-label="Cari barang"
                class="w-52 xl:w-64 pl-9 pr-3 py-2 border border-gray-200 rounded-xl bg-gray-50 hover:bg-white focus:ring-blue-500 focus:border-blue-500 text-sm transition duration-200">
        </form>
    </div>

    <div class="flex items-center space-x-4">
        <span class="text-sm font-medium text-gray-500 hidden sm:block">
            {{ now()->translatedFormat('l, d F Y') }}
        </span>

        {{-- Notifikasi / Tindakan Menunggu --}}
        <div id="notifMenu" class="relative">
            <button id="notifBell" class="relative p-2 rounded-xl hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors" aria-label="Notifikasi" aria-haspopup="true">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <span id="notifBadge" class="hidden absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 bg-red-500 text-white text-[10px] font-bold rounded-full items-center justify-center shadow-sm">0</span>
            </button>

            <div id="notifDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden z-50">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                    <p class="text-sm font-bold text-gray-800">Notifikasi</p>
                    <p class="text-xs text-gray-500">Hal yang perlu perhatian Anda</p>
                </div>
                <div id="notifList" class="p-2 max-h-80 overflow-y-auto"></div>
            </div>
        </div>

        {{-- User Menu (avatar, nama, role, logout) --}}
        <div id="userMenu" class="relative">
            <button id="userMenuBtn" class="flex items-center p-2 rounded-xl hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors" aria-haspopup="true" aria-expanded="false">
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-blue-600 to-blue-400 flex items-center justify-center text-white font-bold shadow-sm">
                        {{ substr(auth()->user()->nama_lengkap, 0, 1) }}
                    </div>
                </div>
                <div class="ml-3 hidden sm:block text-left overflow-hidden">
                    <p class="text-sm font-bold text-gray-800 truncate max-w-[160px]">{{ auth()->user()->nama_lengkap }}</p>
                    <p class="text-xs font-medium text-gray-500">{{ auth()->user()->role }}</p>
                </div>
                <svg class="w-4 h-4 ml-2 text-gray-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <div id="userMenuDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden z-50">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                    <p class="text-sm font-bold text-gray-800 truncate">{{ auth()->user()->nama_lengkap }}</p>
                    <p class="text-xs font-medium text-gray-500">{{ auth()->user()->role }}</p>
                </div>
                <div class="p-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center py-2.5 px-4 border border-red-100 rounded-xl text-sm font-bold text-red-600 bg-red-50 hover:bg-red-600 hover:text-white transition-all duration-200 ease-in-out transform hover:-translate-y-0.5 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
