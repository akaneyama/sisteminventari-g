{{-- Shared flash & validation banners (design.md §3.2) --}}
{{-- Renders success, error, and validation-error banners with the app's standard styling. --}}

@if (session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-xl shadow-sm flex items-start">
        <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-medium text-sm">{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-xl shadow-sm flex items-start">
        <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <span class="font-medium text-sm">{{ session('error') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-xl shadow-sm text-sm">
        <div class="flex items-start">
            <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
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
