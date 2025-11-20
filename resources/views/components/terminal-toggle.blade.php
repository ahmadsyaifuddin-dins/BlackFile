@props(['label', 'key', 'checked' => true, 'url'])

<div x-data="{
    on: {{ $checked ? 'true' : 'false' }},
    loading: false,
    async toggle() {
        this.loading = true;
        // Simulasi delay network (opsional)
        // await new Promise(r => setTimeout(r, 300));

        const newState = !this.on;

        try {
            const response = await fetch('{{ $url }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    key: '{{ $key }}',
                    value: newState
                })
            });

            if (response.ok) {
                this.on = newState;
            } else {
                alert('ACCESS DENIED: Failed to update protocol.');
            }
        } catch (error) {
            console.error('Transmission Error:', error);
        } finally {
            this.loading = false;
        }
    }
}" {{-- [MODIFIED] Classes untuk Responsivitas --}}
    class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 gap-4 sm:gap-0 bg-base border border-border-color rounded-md group overflow-hidden transition-all duration-300"
    :class="on ? 'border-primary/40 shadow-[0_0_15px_rgba(34,197,94,0.05)]' : 'hover:border-primary/30'">

    {{-- Background Scanline Effect --}}
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent -translate-x-full group-hover:animate-[shimmer_2s_infinite]"
        style="pointer-events: none;"></div>

    {{-- Label Section --}}
    <div class="flex flex-col relative z-10 w-full sm:w-auto">
        <span class="text-sm font-bold text-white tracking-wider flex items-center gap-2">
            > {{ $label }}
        </span>

        {{-- BADASS STATUS TEXT --}}
        <div class="flex items-center gap-2 mt-2 transition-all duration-500">
            {{-- Status Light --}}
            <div class="h-1.5 w-1.5 rounded-full transition-all duration-300 shadow-sm"
                :class="on ?
                    'bg-green-500 shadow-[0_0_8px_#22c55e] animate-pulse' :
                    'bg-red-900 shadow-none'">
            </div>

            {{-- Dynamic Text --}}
            <span class="text-[10px] font-mono tracking-[0.2em] font-semibold uppercase transition-colors duration-300"
                :class="on ? 'text-green-400 drop-shadow-[0_0_3px_rgba(74,222,128,0.8)]' :
                    'text-red-800/60 line-through decoration-red-900/50'">
                <span x-text="on ? '// PROTOCOL: ENGAGED' : '// PROTOCOL: TERMINATED'"></span>
            </span>
        </div>
    </div>

    {{-- The Toggle Switch --}}
    {{-- Tambahkan 'self-end' jika ingin toggle di kanan pada mobile, atau biarkan default (kiri) --}}
    <button @click="toggle" :disabled="loading"
        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300 focus:outline-none focus:ring-1 focus:ring-primary focus:ring-offset-1 focus:ring-offset-black z-10 cursor-pointer flex-shrink-0"
        :class="on ? 'bg-primary/20 border border-primary' : 'bg-gray-800 border border-gray-600'">

        {{-- Loading Spinner --}}
        <div x-show="loading" x-transition class="absolute inset-0 flex items-center justify-center z-20">
            <svg class="animate-spin h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
        </div>

        {{-- Switch Knob --}}
        <span class="inline-block h-3 w-3 transform rounded-full transition-all duration-300 shadow-lg ml-1"
            :class="on ? 'translate-x-5 bg-primary shadow-[0_0_10px_rgba(34,197,94,1)]' : 'translate-x-0 bg-gray-500'">
        </span>
    </button>
</div>
