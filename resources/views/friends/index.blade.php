<x-app-layout>
    <x-slot:title>
        {{ $pageTitle ?? 'Network Analysis' }}
    </x-slot:title>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
        <h2 class="text-2xl font-bold text-primary">
            > [ {{ $pageTitle ?? 'NETWORK ANALYSIS TERMINAL' }} ]
        </h2>
        <a href="{{ route('friends.create') }}"
            class="mt-2 sm:mt-0 px-4 py-2 bg-primary text-base hover:bg-primary-hover transition-colors font-bold tracking-widest rounded-md text-sm">
            [ + ESTABLISH CONNECTION ]
        </a>
    </div>

    <div>
        <div id="graph-controls" class="mb-4 p-4 bg-surface border border-border-color rounded-lg">
            <div class="flex flex-col md:flex-row gap-4 md:items-center">
                <div class="w-full md:w-1/3">
                    <label for="search-input" class="block text-primary text-sm mb-1">> SEARCH ASSET (CODENAME)</label>
                    <input type="text" id="search-input" placeholder="E.g., EAGLE-01"
                        class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary placeholder-secondary/50 p-2 rounded">
                </div>

                <div class="w-full md:w-2/3">
                    <label class="block text-primary text-sm mb-1">> FILTER VISIBILITY</label>
                    <div class="flex flex-wrap gap-x-6 gap-y-2">
                        <label for="filter-user"
                            class="flex items-center space-x-2 text-secondary cursor-pointer hover:text-white">
                            <input type="checkbox" id="filter-user" value="{{ auth()->user()->role->alias }}"
                                class="filter-role form-checkbox bg-base border-border-color text-primary focus:ring-primary">
                            <span>My Role ({{ auth()->user()->role->alias }})</span>
                        </label>
                        <label for="filter-friend"
                            class="flex items-center space-x-2 text-secondary cursor-pointer hover:text-white">
                            <input type="checkbox" id="filter-friend" value="Asset"
                                class="filter-role form-checkbox bg-base border-border-color text-primary focus:ring-primary">
                            {{-- Diperbaiki menjadi "Asset" agar konsisten dengan controller --}}
                            <span>Asset</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div id="cy" class="w-full h-[70vh] bg-base border-2 border-border-color rounded-lg" data-nodes='@json($nodes)'
            data-edges='@json($edges)' data-rootnodeid='{{ $rootNodeId }}'></div>
    </div>

    <div x-data="{ 
        isModalOpen: false, 
        selectedNodeData: null,
        showSubAssetForm: false
    }" x-show="isModalOpen" @open-node-modal.window="
        isModalOpen = true; 
        selectedNodeData = $event.detail;
        showSubAssetForm = false;
    " @keydown.escape.window="isModalOpen = false" class="fixed inset-0 z-30 flex items-center justify-center p-4"
        style="display: none;">
        <div x-show="isModalOpen" x-transition.opacity class="absolute inset-0 bg-black/75"></div>

        <div x-show="isModalOpen" x-transition @click.outside="isModalOpen = false"
            class="relative w-full max-w-lg bg-surface border-2 border-border-color rounded-lg shadow-lg flex flex-col">

            <div class="flex items-start justify-between p-4 border-b border-border-color">
                <div class="min-w-0"> 
                    <h3 class="text-2xl font-bold text-primary break-words" x-text="selectedNodeData?.label || 'Loading...'"></h3>
                    <p class="text-secondary" x-text="selectedNodeData?.role || '...'"></p>
                </div>
                <button @click="isModalOpen = false" class="text-secondary hover:text-white text-2xl ml-4 flex-shrink-0">&times;</button>
            </div>

            <div class="p-4 overflow-y-auto">
                <p class="text-white"><strong class="text-primary">> ID:</strong> <span
                        x-text="selectedNodeData?.id"></span></p>
                <p class="text-white mt-2"><strong class="text-primary">> Status:</strong> <span
                        class="text-green-400">Active</span></p>
                <p class="text-white mt-2"><strong class="text-primary">> Last Contact:</strong> <span
                        x-text="new Date().toISOString().slice(0, 10)"></span></p>

                <div x-show="showSubAssetForm" x-transition
                    class="mt-4 pt-4 border-t border-dashed border-border-color">
                    <h4 class="text-primary font-bold mb-2">> Register New Sub-Asset</h4>
                    <form method="POST" action="{{ route('connections.store_sub_asset') }}" class="space-y-3">
                        @csrf
                        <input type="hidden" name="source_type" value="App\Models\Friend">
                        <input type="hidden" name="source_id" :value="selectedNodeData?.id.substring(1)">

                        <div>
                            <label for="sub_name" class="block text-secondary text-sm">> REAL NAME</label>
                            <input type="text" id="sub_name" name="name" required
                                class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary p-2 rounded">
                        </div>
                        <div>
                            <label for="sub_codename" class="block text-secondary text-sm">> CODENAME</label>
                            <input type="text" id="sub_codename" name="codename" required
                                class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary p-2 rounded">
                        </div>
                        <div class="text-right">
                            <button type="submit"
                                class="px-3 py-1 bg-primary text-base hover:bg-primary-hover font-bold text-xs rounded">[
                                ESTABLISH LINK ]</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="p-4 border-t border-border-color flex-shrink-0">
                <template x-if="selectedNodeData && selectedNodeData.id.startsWith('f')">
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-between sm:items-center gap-3">
                        <div>
                            <form :action="'/friends/' + selectedNodeData.id.substring(1)" method="POST"
                                onsubmit="return confirm('CONFIRM ASSET TERMINATION. This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full sm:w-auto px-4 py-2 bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white font-bold text-sm rounded transition-colors">
                                    [ DELETE ]
                                </button>
                            </form>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                            <button @click="showSubAssetForm = !showSubAssetForm"
                                class="w-full sm:w-auto px-4 py-2 bg-green-600/20 text-green-400 hover:bg-green-600 hover:text-white font-bold text-sm rounded transition-colors">
                                [ + ADD SUB-ASSET ]
                            </button>
                            <a :href="'/friends/' + selectedNodeData.id.substring(1) + '/edit'"
                                class="text-center w-full sm:w-auto px-4 py-2 bg-blue-600/80 text-white hover:bg-blue-600 font-bold text-sm rounded transition-colors">
                                [ EDIT ]
                            </a>
                        </div>
                    </div>
                </template>
            </div>

        </div>
    </div>
</x-app-layout>