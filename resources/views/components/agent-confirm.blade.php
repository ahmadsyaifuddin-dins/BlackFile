<div x-data="{
    show: false,
    title: '',
    message: '',
    confirmText: 'PROCEED',
    cancelText: 'ABORT',
    resolvePromise: null, // Ini kunci agar bisa ditunggu (await)

    init() {
        // Expose global function
        window.agentConfirm = (title, message, confirmText = 'PROCEED', cancelText = 'ABORT') => {
            this.title = title;
            this.message = message;
            this.confirmText = confirmText;
            this.cancelText = cancelText;
            this.show = true;

            // Return promise yang akan di-resolve saat tombol diklik
            return new Promise((resolve) => {
                this.resolvePromise = resolve;
            });
        };
    },

    handleResponse(result) {
        this.show = false;
        if (this.resolvePromise) {
            this.resolvePromise(result); // Return true atau false ke pemanggil
            this.resolvePromise = null;
        }
    }
}" x-show="show" x-trap.noscroll="show" {{-- Opsional: butuh plugin Alpine Focus, kalau tidak ada hapus saja x-trap --}} x-cloak
    class="fixed inset-0 z-[60] overflow-y-auto font-mono" style="display: none;">

    <div x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity">
    </div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            @click.outside="handleResponse(false)"
            class="relative transform overflow-hidden bg-base border border-amber-500/50 text-left shadow-[0_0_30px_rgba(245,158,11,0.2)] transition-all sm:my-8 sm:w-full sm:max-w-lg">

            <div class="bg-amber-900/20 border-b border-amber-500/30 px-4 py-2 flex items-center justify-between">
                <span class="text-xs text-amber-500 font-bold tracking-widest uppercase">
                    <i class="fa-solid fa-shield-halved mr-1"></i> AUTH_REQ
                </span>
                <div class="flex gap-1">
                    <div class="w-2 h-2 bg-amber-500/50 rounded-full animate-pulse"></div>
                    <div class="w-2 h-2 bg-amber-500/30 rounded-full"></div>
                </div>
            </div>

            <div class="px-6 py-6">
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-amber-100/5 sm:mx-0 sm:h-10 sm:w-10 border border-amber-500/30">
                        <i class="fa-solid fa-question text-amber-500 text-lg"></i>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg font-bold leading-6 text-amber-500 uppercase tracking-wide" x-text="title">
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-400 font-light" x-text="message"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-black/40 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2 border-t border-gray-800">
                <button type="button" @click="handleResponse(true)"
                    class="cursor-pointer inline-flex w-full justify-center bg-amber-600/20 border border-amber-500 px-3 py-2 text-sm font-semibold text-amber-500 shadow-sm hover:bg-amber-600 hover:text-white sm:ml-3 sm:w-auto transition-all duration-200 uppercase tracking-wider group">
                    <span class="mr-2 group-hover:animate-pulse">►</span> <span x-text="confirmText"></span>
                </button>
                <button type="button" @click="handleResponse(false)"
                    class="cursor-pointer mt-3 inline-flex w-full justify-center bg-transparent border border-gray-600 px-3 py-2 text-sm font-semibold text-gray-400 shadow-sm hover:bg-gray-800 hover:text-white sm:mt-0 sm:w-auto transition-all duration-200 uppercase tracking-wider">
                    <span x-text="cancelText"></span>
                </button>
            </div>

            <div class="absolute top-0 left-0 w-2 h-2 border-t border-l border-amber-500"></div>
            <div class="absolute top-0 right-0 w-2 h-2 border-t border-r border-amber-500"></div>
            <div class="absolute bottom-0 left-0 w-2 h-2 border-b border-l border-amber-500"></div>
            <div class="absolute bottom-0 right-0 w-2 h-2 border-b border-r border-amber-500"></div>
        </div>
    </div>
</div>
