<div x-data="{
    show: false,
    type: 'info',
    title: '',
    message: '',
    timeout: null,
    position: '{{ Auth::user()->settings['alert_position'] ?? 'bottom-right' }}',

    init() {
        window.addEventListener('agent-alert', (event) => {
            this.trigger(event.detail.type, event.detail.title, event.detail.message);
        });

        window.agentAlert = (type, title, message) => {
            this.trigger(type, title, message);
        };
    },

    trigger(type, title, message) {
        this.type = type;
        this.title = title;
        this.message = message;
        this.show = true;

        if (this.timeout) clearTimeout(this.timeout);
        if (type !== 'error') {
            this.timeout = setTimeout(() => { this.show = false }, 3500);
        }
    },

    get colors() {
        switch (this.type) {
            case 'error':
                return 'border-red-600 text-red-400 bg-red-900/40 shadow-[0_0_15px_rgba(220,38,38,0.3)]';
            case 'success':
                return 'border-green-600 text-green-400 bg-green-900/40 shadow-[0_0_15px_rgba(22,163,74,0.3)]';
            case 'warning':
                return 'border-yellow-600 text-yellow-400 bg-yellow-900/40 shadow-[0_0_15px_rgba(202,138,4,0.3)]';
            default:
                return 'border-blue-600 text-blue-400 bg-blue-900/40 shadow-[0_0_15px_rgba(37,99,235,0.3)]';
        }
    },

    get icon() {
        switch (this.type) {
            case 'error':
                return 'fa-triangle-exclamation';
            case 'success':
                return 'fa-check-circle';
            case 'warning':
                return 'fa-circle-exclamation';
            default:
                return 'fa-circle-info';
        }
    },

    // UPDATE: Menggunakan Responsive Spacing (Mobile: 4, Desktop: 6)
    get positionClasses() {
        switch (this.position) {
            case 'top-right':
                return 'top-4 right-4 sm:top-6 sm:right-6';
            case 'top-left':
                return 'top-4 left-4 sm:top-6 sm:left-6';
            case 'center':
                return 'top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2';
                // Default bottom-right
            default:
                return 'bottom-4 right-4 sm:bottom-6 sm:right-6';
        }
    }
}" x-show="show" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90" {{-- UPDATE: w-[calc(100vw-2rem)] agar pas di layar HP --}}
    :class="['fixed z-50 max-w-sm w-[calc(100vw-2rem)] sm:w-full font-mono transition-all duration-300', positionClasses]"
    style="display: none;">

    <div :class="colors" class="border-l-4 border backdrop-blur-sm p-4 relative overflow-hidden group shadow-lg">

        <div
            class="absolute inset-0 bg-[linear-gradient(rgba(18,16,16,0)_50%,rgba(0,0,0,0.25)_50%),linear-gradient(90deg,rgba(255,0,0,0.06),rgba(0,255,0,0.02),rgba(0,0,255,0.06))] z-0 pointer-events-none bg-[length:100%_2px,3px_100%]">
        </div>

        <div class="relative z-10 flex items-start space-x-3">
            <div class="flex-shrink-0 mt-1">
                <i :class="['fa-solid', icon, 'text-xl animate-pulse drop-shadow-md']"></i>
            </div>
            <div class="flex-1 w-0">
                <p class="text-xs font-bold uppercase tracking-widest border-b border-dashed border-current pb-1 mb-1 opacity-90"
                    x-text="title"></p>
                <p class="text-sm font-medium opacity-100 leading-tight text-shadow break-words" x-text="message"></p>
            </div>
            <div class="flex-shrink-0 ml-2">
                <button @click="show = false"
                    class="hover:opacity-100 opacity-50 focus:outline-none transition-opacity">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <div class="absolute bottom-0 right-0 w-2 h-2 border-b border-r border-current opacity-50"></div>
    </div>
</div>
