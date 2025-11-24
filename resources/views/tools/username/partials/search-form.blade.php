{{-- Search Form + Controls --}}
<div class="bg-surface border border-border-color p-4 sm:p-6 rounded-lg shadow-lg mb-4 sm:mb-6">
    <label class="block text-xs font-bold text-primary mb-2 uppercase tracking-widest">
        Target Username
    </label>

    <div class="flex flex-col gap-4">
        {{-- Input + Scan Button --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-grow">
                <x-forms.input type="text" id="usernameInput" name="name"
                    class="w-full bg-base border border-border-color text-white pl-10 pr-4 py-3 rounded font-mono focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all placeholder-gray-700 text-sm sm:text-base"
                    placeholder="johndoe" autocomplete="off" onkeypress="if(event.key === 'Enter') startScan()">
                    <x-slot:icon>
                        <i class="fas fa-at text-gray-500 mt-0.5"></i>
                    </x-slot:icon>
                </x-forms.input>
            </div>

            <x-button onclick="startScan()" id="btnScan"
                class="w-full sm:w-auto bg-primary hover:bg-green-500 text-black font-bold py-3 px-6 sm:px-8 rounded transition-all uppercase tracking-wider flex items-center justify-center gap-2 text-sm sm:text-base shadow-[0_0_10px_rgba(46,160,67,0.3)] hover:shadow-[0_0_15px_rgba(46,160,67,0.6)]">
                <i class="fa-solid fa-satellite-dish"></i>
                <span>SCAN TARGET</span>
            </x-button>
        </div>

        {{-- Control Buttons (Responsive Grid) --}}
        {{-- MOBILE: Grid 5 Kolom (2:2:1) | DESKTOP: Flex Row --}}
        <div class="grid grid-cols-5 sm:flex sm:flex-row gap-2">

            {{-- History Button (Mobile: Col Span 2) --}}
            <x-button variant="outline" onclick="toggleHistory()"
                class="col-span-2 sm:flex-none justify-center bg-surface-light border-border-color text-secondary 
                       hover:bg-cyan-600 hover:border-cyan-600 hover:text-black hover:shadow-[0_0_10px_rgba(8,145,178,0.5)]
                       py-2 px-3 rounded text-xs font-bold transition-all uppercase tracking-wide">
                <i class="fa-solid fa-clock-rotate-left mr-1.5"></i> History
            </x-button>

            {{-- Export Button (Mobile: Col Span 2) --}}
            <x-button variant="outline" onclick="exportResults()" id="btnExport"
                class="col-span-2 sm:flex-none justify-center bg-surface-light border-border-color text-secondary 
                       hover:bg-blue-600 hover:border-blue-600 hover:text-black hover:shadow-[0_0_10px_rgba(37,99,235,0.5)]
                       py-2 px-3 rounded text-xs font-bold transition-all uppercase tracking-wide disabled:opacity-30 disabled:cursor-not-allowed"
                disabled>
                <i class="fa-solid fa-download mr-1.5"></i> Export
            </x-button>

            {{-- Clear Button (Mobile: Col Span 1 - Icon Only) --}}
            <x-button variant="outline" onclick="clearHistory()"
                class="col-span-1 sm:flex-none justify-center bg-surface-light border-red-900/50 text-red-800 
                       hover:bg-red-600 hover:border-red-600 hover:text-black hover:shadow-[0_0_10px_rgba(220,38,38,0.5)]
                       py-2 px-3 rounded text-xs transition-all">
                <i class="fa-solid fa-trash"></i>
            </x-button>
        </div>

        {{-- History Dropdown --}}
        @include('tools.username.partials.history-list')
    </div>
</div>
