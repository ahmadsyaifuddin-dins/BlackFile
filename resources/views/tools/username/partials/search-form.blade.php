{{-- Search Form + Controls --}}
<div class="bg-surface border border-border-color p-4 sm:p-6 rounded-lg shadow-lg mb-4 sm:mb-6">
    <label class="block text-xs font-bold text-primary mb-2 uppercase tracking-widest">
        Target Username
    </label>

    <div class="flex flex-col gap-3">
        {{-- Input + Scan Button --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-grow">
                <x-forms.input type="text" id="usernameInput" name="name"
                    class="w-full bg-base border border-border-color text-white pl-8 pr-4 py-3 rounded font-mono focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all placeholder-gray-700 text-sm sm:text-base"
                    placeholder="johndoe" autocomplete="off" onkeypress="if(event.key === 'Enter') startScan()">
                    <x-slot:icon>
                        <i class="fas fa-at text-gray-500"></i>
                    </x-slot:icon>
                </x-forms.input>

            </div>
            <x-button onclick="startScan()" id="btnScan"
                class="bg-primary hover:bg-green-600 text-black font-bold py-3 px-6 sm:px-8 rounded transition-colors uppercase tracking-wider flex items-center justify-center gap-2 text-sm sm:text-base whitespace-nowrap">
                <i class="fa-solid fa-satellite-dish"></i>
                <span class="hidden sm:inline">Start Scan</span>
                <span class="sm:hidden">Scan</span>
            </x-button>
        </div>

        {{-- Control Buttons --}}
        <div class="flex gap-2">
            <x-button variant="outline" onclick="toggleHistory()"
                class="flex-1 sm:flex-none bg-surface-light border border-border-color hover:border-primary text-secondary hover:text-primary py-2 px-4 rounded text-xs transition-colors">
                <i class="fa-solid fa-clock-rotate-left mr-1"></i> History
            </x-button>
            <x-button variant="outline" onclick="exportResults()" id="btnExport"
                class="flex-1 sm:flex-none bg-surface-light border border-border-color hover:border-blue-500 text-secondary hover:text-blue-400 py-2 px-4 rounded text-xs transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
                disabled>
                <i class="fa-solid fa-download mr-1"></i> Export
            </x-button>
            <x-button variant="outline" onclick="clearHistory()"
                class="sm:flex-none bg-surface-light border border-red-900/50 hover:border-red-500 text-red-800 hover:text-red-500 py-2 px-4 rounded text-xs transition-colors">
                <i class="fa-solid fa-trash"></i>
            </x-button>
        </div>

        {{-- History Dropdown --}}
        @include('tools.username.partials.history-list')
    </div>
</div>