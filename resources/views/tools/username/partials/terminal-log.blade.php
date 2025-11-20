{{-- Terminal Log (Desktop Only) --}}
<div class="hidden lg:flex lg:col-span-1 bg-black border border-border-color rounded p-4 font-mono text-xs overflow-y-auto flex-col" id="terminal-window">
    <div class="text-secondary mb-2 border-b border-gray-800 pb-2">
        <i class="fa-solid fa-terminal mr-2"></i>SYSTEM LOG
    </div>
    <div id="scanLog" class="space-y-1 font-mono">
        <div class="text-green-900">System ready...</div>
        <div class="text-green-900">Waiting for input...</div>
    </div>
</div>

<style>
    /* Custom scrollbar for terminal */
    #terminal-window::-webkit-scrollbar {
        width: 4px;
    }
    #terminal-window::-webkit-scrollbar-track {
        background: #000;
    }
    #terminal-window::-webkit-scrollbar-thumb {
        background: #333;
        border-radius: 2px;
    }
    #terminal-window::-webkit-scrollbar-thumb:hover {
        background: var(--color-primary);
    }
</style>