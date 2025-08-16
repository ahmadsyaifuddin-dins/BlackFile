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
                        <label for="filter-user" class="flex items-center space-x-2 text-secondary cursor-pointer hover:text-white">
                            <input type="checkbox" id="filter-user" value="{{ auth()->user()->role->alias }}" class="filter-role form-checkbox bg-base border-border-color text-primary focus:ring-primary">
                            <span>My Role ({{ auth()->user()->role->alias }})</span>
                        </label>
                        <label for="filter-friend" class="flex items-center space-x-2 text-secondary cursor-pointer hover:text-white">
                            <input type="checkbox" id="filter-friend" value="Friend" class="filter-role form-checkbox bg-base border-border-color text-primary focus:ring-primary">
                            <span>Friend</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    
        <div id="cy" class="w-full h-[70vh] bg-base border-2 border-border-color rounded-lg"></div>
    </div>

    <div 
        x-data="{ 
            isModalOpen: false, 
            selectedNodeData: null 
        }"
        x-show="isModalOpen"
        @open-node-modal.window="
            isModalOpen = true; 
            selectedNodeData = $event.detail;
        "
        @keydown.escape.window="isModalOpen = false"
        class="fixed inset-0 z-30 flex items-center justify-center p-4"
        style="display: none;"
    >
        <div x-show="isModalOpen" x-transition.opacity class="absolute inset-0 bg-black/75"></div>
    
        <div x-show="isModalOpen" x-transition @click.outside="isModalOpen = false"
             class="relative w-full max-w-lg bg-surface border-2 border-border-color rounded-lg shadow-lg">
            
            <div class="flex items-start justify-between p-4 border-b border-border-color">
                <div>
                    <h3 class="text-2xl font-bold text-primary" x-text="selectedNodeData?.label || 'Loading...'"></h3>
                    <p class="text-secondary" x-text="selectedNodeData?.role || '...'"></p>
                </div>
                <button @click="isModalOpen = false" class="text-secondary hover:text-white text-2xl">&times;</button>
            </div>
    
            <div class="p-4 max-h-[60vh] overflow-y-auto">
                <p class="text-white"><strong class="text-primary">> ID:</strong> <span x-text="selectedNodeData?.id"></span></p>
                <p class="text-white mt-2"><strong class="text-primary">> Status:</strong> <span class="text-green-400">Active</span></p>
                <p class="text-white mt-2"><strong class="text-primary">> Last Contact:</strong> <span x-text="new Date().toISOString().slice(0, 10)"></span></p>
            </div>
    
            <template x-if="selectedNodeData && selectedNodeData.id.startsWith('f')">
                <div class="p-4 border-t border-border-color flex items-center justify-end space-x-3">
                    
                    <a :href="'/friends/' + selectedNodeData.id.substring(1) + '/edit'"
                       class="px-4 py-2 bg-blue-600/80 text-white hover:bg-blue-600 font-bold text-sm rounded transition-colors">
                        [ EDIT ]
                    </a>
                    
                    <form :action="'/friends/' + selectedNodeData.id.substring(1)" method="POST" onsubmit="return confirm('CONFIRM ASSET TERMINATION. This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600/80 text-white hover:bg-red-600 font-bold text-sm rounded transition-colors">
                            [ DELETE ]
                        </button>
                    </form>
            
                </div>
            </template>
        </div>
    </div>


    @push('scripts')
        <script type="module">
            // Import fungsi kontroler dari file eksternal
            import { initializeGraphControls } from '{{ Vite::asset('resources/js/graph-controls.js') }}';

            document.addEventListener("DOMContentLoaded", function() {
                // Inisialisasi Cytoscape
                const cy = window.cytoscape({
                    container: document.getElementById('cy'),
                    elements: {
                        nodes: @json($nodes),
                        edges: @json($edges)
                    },
                    style: [
                        { selector: 'node', style: { 'background-color': '#00ff88', 'label': 'data(label)', 'color': '#00ff88', 'text-outline-width': 1, 'text-outline-color': '#003322', 'font-size': '12px' }},
                        { selector: `node[id = "u{{ auth()->user()->id }}"]`, style: { 'width': 80, 'height': 80, 'font-size': '16px' }},
                        { selector: 'edge', style: { 'width': 2, 'line-color': '#00ff88', 'target-arrow-color': '#00ff88', 'target-arrow-shape': 'triangle', 'curve-style': 'bezier' }},
                        { selector: '.highlighted', style: { 'min-zoomed-font-size': 12, 'font-weight': 'bold', 'background-color': '#2ea043', 'border-color': '#fff', 'border-width': 2, 'z-index': 9999 }},
                        { selector: '.faded', style: { 'opacity': 0.25 }},
                        { selector: '.hover-highlighted', style: { 'border-color': '#ffffff', 'border-width': 3, 'box-shadow': '0 0 15px #00ff88' }},
                        { selector: '.hover-faded', style: { 'opacity': 0.4 }}
                    ],
                    layout: {
                        name: 'concentric',
                        concentric: function(node){ return node.id() === 'u{{ auth()->user()->id }}' ? 100 : 50; },
                        levelWidth: function(nodes){ return 10; },
                        spacingFactor: 1.5,
                        animate: true
                    }
                });

                // --- INTERAKTIVITAS GRAPH ---

                // Efek Hover
                cy.on('mouseover', 'node', function(evt){
                    const selectedNode = evt.target;
                    const neighborhood = selectedNode.neighborhood();
                    cy.elements().addClass('hover-faded');
                    selectedNode.removeClass('hover-faded').addClass('hover-highlighted');
                    neighborhood.removeClass('hover-faded');
                });
                cy.on('mouseout', 'node', function(evt){
                    cy.elements().removeClass('hover-faded hover-highlighted');
                });

                // Klik Node -> Buka Modal
                cy.on('tap', 'node', function(evt) {
                    const nodeData = evt.target.data();
                    window.dispatchEvent(new CustomEvent('open-node-modal', { detail: nodeData }));
                });

                // Panggil modul untuk mengaktifkan search & filter
                initializeGraphControls({
                    cytoscapeInstance: cy,
                    searchInputId: 'search-input',
                    filterCheckboxSelector: '.filter-role'
                });
            });
        </script>
    @endpush
</x-app-layout>