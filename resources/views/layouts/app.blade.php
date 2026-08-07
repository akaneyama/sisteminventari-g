<!DOCTYPE html>
<html lang="id">
<head>
    @include('layouts.partials.meta')
    <title>@yield('title') - SiVentaris</title>
    @include('layouts.partials.head')
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900 flex h-screen overflow-hidden selection:bg-blue-100 selection:text-blue-900">

    @include('layouts.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden relative">

        @include('layouts.partials.topbar')

        <div class="flex-1 overflow-auto p-6 lg:p-10">
            <div class="mx-auto max-w-7xl">
                @yield('content')
            </div>
        </div>

    </main>

    @include('layouts.partials.image-modal')
    @include('layouts.partials.scripts')
    @stack('scripts')
</body>
</html>
