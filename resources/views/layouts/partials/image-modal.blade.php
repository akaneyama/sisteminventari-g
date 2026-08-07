{{-- Global Image Modal --}}
<div id="globalImageModal" class="fixed inset-0 z-[60] hidden bg-gray-900/80 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
    <div class="relative max-w-4xl w-full flex justify-center">
        <button onclick="closeImageModal()" class="absolute -top-12 right-0 text-white hover:text-red-400 focus:outline-none transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <img id="globalModalImage" src="" alt="Preview" class="max-h-[85vh] rounded-xl shadow-2xl object-contain bg-white/5">
    </div>
</div>
