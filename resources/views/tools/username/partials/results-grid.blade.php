{{-- Results Grid Container --}}
<div class="lg:col-span-2 overflow-y-auto">
    {{-- Progress Bar --}}
    <div class="mb-4 hidden" id="progressContainer">
        <div class="flex justify-between text-xs text-secondary mb-1">
            <span class="text-[10px] sm:text-xs">SCANNING PROGRESS</span>
            <span id="progressText" class="text-[10px] sm:text-xs">0%</span>
        </div>
        <div class="w-full bg-gray-800 rounded-full h-1.5 sm:h-2">
            <div id="progressBar" class="bg-primary h-1.5 sm:h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>
    </div>

    {{-- Results Cards Grid --}}
    <div id="resultsGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
        {{-- Initial empty state --}}
        <div class="col-span-full text-center py-10 sm:py-20 text-secondary opacity-50">
            <i class="fa-solid fa-magnifying-glass text-3xl sm:text-4xl mb-4"></i>
            <p class="text-sm sm:text-base">Enter a username to begin OSINT scan.</p>
        </div>
    </div>
</div>

<style>
    /* Mobile smooth scroll */
    @media (max-width: 1023px) {
        #resultsGrid {
            scroll-behavior: smooth;
        }
    }
</style>