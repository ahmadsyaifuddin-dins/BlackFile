<div x-data="{ 
    isOpen: false, 
    actionUrl: '', 
    title: 'CONFIRM TERMINATION', 
    message: 'Are you sure you want to proceed? This action cannot be undone.',
    targetName: ''
}"
@open-delete-modal.window="
    isOpen = true; 
    actionUrl = $event.detail.url; 
    title = $event.detail.title || 'CONFIRM TERMINATION';
    message = $event.detail.message || 'Are you sure you want to proceed? This action cannot be undone.';
    targetName = $event.detail.target || '';
"
x-show="isOpen"
@keydown.escape.window="isOpen = false"
style="display: none;"
class="fixed inset-0 z-[999] flex items-center justify-center px-4 font-mono"
x-cloak>

{{-- Backdrop Blur & Darken --}}
<div x-show="isOpen" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black/80 backdrop-blur-sm" 
    @click="isOpen = false">
</div>

{{-- Modal Content --}}
<div x-show="isOpen"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95 translate-y-4"
    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
    x-transition:leave-end="opacity-0 scale-95 translate-y-4"
    class="relative w-full max-w-md bg-gray-900 border-2 border-red-600 shadow-[0_0_20px_rgba(220,38,38,0.3)] rounded-lg overflow-hidden z-50">
    
    {{-- Header: Warning Stripe --}}
    <div class="bg-red-600/10 border-b border-red-600/50 p-4 flex items-center gap-3">
        <div class="bg-red-600 text-black p-1 rounded font-bold text-xs animate-pulse">
            [WARNING]
        </div>
        <h3 class="text-lg font-bold text-red-500 tracking-widest" x-text="title"></h3>
    </div>

    {{-- Body --}}
    <div class="p-6">
        <div class="flex items-start gap-4">
            <div class="hidden sm:block p-3 bg-red-900/20 rounded-full border border-red-500/30">
                <svg class="w-8 h-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-gray-300 text-sm leading-relaxed" x-text="message"></p>
                
                {{-- Target Name (Optional) --}}
                <template x-if="targetName">
                    <div class="mt-3 p-2 border border-red-800/50 bg-black rounded text-red-400 text-xs font-bold text-center">
                        TARGET: <span x-text="targetName"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- Footer: Action Buttons --}}
    <div class="bg-black/50 p-4 border-t border-red-600/30 flex flex-col sm:flex-row justify-end gap-3">
        <button type="button" @click="isOpen = false"
            class="cursor-pointer w-full sm:w-auto px-4 py-2 border border-gray-600 text-gray-400 hover:text-white hover:border-gray-400 rounded transition-colors text-xs font-bold tracking-wider">
            [ ABORT ]
        </button>

        {{-- Form Actual Delete --}}
        <form :action="actionUrl" method="POST" class="w-full sm:w-auto">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="cursor-pointer w-full sm:w-auto px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded shadow-lg shadow-red-900/20 transition-all text-xs font-bold tracking-wider flex items-center justify-center gap-2 group">
                <span>CONFIRM DELETION</span>
                <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
            </button>
        </form>
    </div>
</div>
</div>