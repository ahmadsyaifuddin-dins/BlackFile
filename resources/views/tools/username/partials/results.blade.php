<div class="flex-grow grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 min-h-0">
    <div class="hidden lg:flex lg:col-span-1 bg-black border border-border-color rounded p-4 font-mono text-xs overflow-y-auto flex-col" id="terminal-window">
        <div class="text-secondary mb-2 border-b border-gray-800 pb-2">
            <i class="fa-solid fa-terminal mr-2"></i>SYSTEM LOG
        </div>
        <div id="scanLog" class="space-y-1 font-mono">
            <div class="text-green-900">System ready...</div>
            <div class="text-green-900">Waiting for input...</div>
        </div>
    </div>

    <div class="lg:col-span-2 overflow-y-auto">
        <div class="mb-4 hidden" id="progressContainer">
            <div class="flex justify-between text-xs text-secondary mb-1">
                <span class="text-[10px] sm:text-xs">SCANNING PROGRESS</span>
                <span id="progressText" class="text-[10px] sm:text-xs">0%</span>
            </div>
            <div class="w-full bg-gray-800 rounded-full h-1.5 sm:h-2">
                <div id="progressBar" class="bg-primary h-1.5 sm:h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
        </div>

        <div id="resultsGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
            <div class="col-span-full text-center py-10 sm:py-20 text-secondary opacity-50">
                <i class="fa-solid fa-magnifying-glass text-3xl sm:text-4xl mb-4"></i>
                <p class="text-sm sm:text-base">Enter a username to begin OSINT scan.</p>
            </div>
        </div>
    </div>
</div>