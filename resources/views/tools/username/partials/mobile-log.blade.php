{{-- Mobile Log Toggle Button --}}
<button 
    onclick="toggleMobileLog()" 
    class="lg:hidden fixed bottom-4 right-4 bg-primary text-black p-3 rounded-full shadow-lg z-50 hover:bg-green-600 transition-colors">
    <i class="fa-solid fa-terminal text-lg"></i>
</button>

{{-- Mobile Log Modal --}}
<div id="mobileLogModal" class="lg:hidden fixed inset-0 bg-black/90 z-50 flex items-end">
    <div class="w-full bg-surface border-t-2 border-primary rounded-t-2xl p-4 max-h-[70vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-primary font-bold text-sm uppercase tracking-wider">
                <i class="fa-solid fa-terminal mr-2"></i>System Log
            </h3>
            <button onclick="toggleMobileLog()" class="text-gray-500 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <div id="mobileLogContent" class="font-mono text-xs space-y-1">
            {{-- Log content will be synced from desktop log --}}
        </div>
    </div>
</div>

<style>
    /* Touch feedback for mobile */
    @media (hover: none) {
        button:active {
            transform: scale(0.95);
        }
    }
</style>