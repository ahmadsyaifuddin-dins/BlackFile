<!-- Footer & Progress Bar -->
<div class="bg-surface-light border-t border-border-color px-6 py-4">

    <div x-show="isUploading" x-transition class="mb-4">
        <div class="flex justify-between text-xs text-primary font-mono mb-1">
            <span>UPLOADING PACKETS...</span>
            <span x-text="uploadPercentage + '%'"></span>
        </div>
        <div class="w-full bg-black border border-primary/30 h-3 rounded-full overflow-hidden relative">
            <div class="absolute inset-0 w-full h-full bg-[url('https://media.giphy.com/media/3oEjI6SIIHBdRxXI40/giphy.gif')] opacity-10"></div>
            <div class="bg-primary h-full transition-all duration-200 ease-out"
                 :style="'width: ' + uploadPercentage + '%'"></div>
        </div>
        <p class="text-[10px] text-secondary mt-1 animate-pulse text-center">// DO NOT CLOSE THIS TERMINAL</p>
    </div>

    <div class="text-right">
        <x-button type="submit" x-bind:disabled="isUploading" class="disabled:opacity-50 disabled:cursor-not-allowed">
            <span x-show="!isUploading">{{ $isEdit ? '> UPDATE DATABASE' : '> INITIATE UPLOAD' }}</span>
            <span x-show="isUploading" class="flex items-center gap-2">
                <svg class="animate-spin h-4 w-4 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                TRANSMITTING...
            </span>
        </x-button>
    </div>
</div>