{{-- History Dropdown Container --}}
<div id="historyContainer" class="hidden">
    <div class="bg-black/50 border border-gray-800 rounded p-3 max-h-40 overflow-y-auto">
        <div id="historyList" class="space-y-1 text-xs font-mono">
            {{-- History items will be injected by JavaScript --}}
            <div class="text-gray-700 text-center py-2">No search history</div>
        </div>
    </div>
</div>

<style>
    /* Custom scrollbar for history */
    #historyContainer::-webkit-scrollbar {
        width: 4px;
    }
    #historyContainer::-webkit-scrollbar-track {
        background: #000;
    }
    #historyContainer::-webkit-scrollbar-thumb {
        background: #333;
        border-radius: 2px;
    }
    #historyContainer::-webkit-scrollbar-thumb:hover {
        background: var(--color-primary);
    }
</style>