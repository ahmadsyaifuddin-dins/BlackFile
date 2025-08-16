<x-app-layout>
    <x-slot:title>Central Tree (Radar Style)</x-slot:title>

    <div class="p-4 md:p-6">
        <h1 class="text-2xl mb-4 text-primary font-bold">
            > Central Network Analysis: {{ auth()->user()->codename }}
        </h1>

        <div id="graph-controls" class="p-4 bg-surface border border-border-color rounded-lg mb-4 flex flex-col md:flex-row gap-4 items-center">
    
            <div class="w-full md:w-1/3">
                <label for="search-input" class="block text-primary text-sm mb-1">> SEARCH ASSET (CODENAME)</label>
                <input type="text" id="search-input" placeholder="E.g., EAGLE-01" 
                       class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary placeholder-secondary/50 p-2 rounded">
            </div>
        
            <div class="w-full md:w-2/3">
                <label class="block text-primary text-sm mb-1">> FILTER VISIBILITY</label>
                <div class="flex flex-wrap gap-x-6 gap-y-2">
                    {{-- Di data graph, hanya ada role 'Friend' dan role user itu sendiri, jadi kita sesuaikan --}}
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

        {{-- Div untuk Graph Cytoscape, kita pakai tema dari layout --}}
        <div id="cy" class="w-full h-[70vh] bg-base border-2 border-border-color"></div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const cy = window.cytoscape({
                    container: document.getElementById('cy'),
                    elements: {
                        nodes: @json($nodes),
                        edges: @json($edges)
                    },
                    style: [{
                            selector: 'node',
                            style: {
                                'background-color': '#00ff88',
                                'label': 'data(label)',
                                'color': '#00ff88',
                                'text-outline-width': 1,
                                'text-outline-color': '#003322',
                                'font-size': '12px'
                            }
                        },
                        {
                            selector: `node[id = "u{{ auth()->user()->id }}"]`,
                            style: {
                                'background-color': '#00ff88',
                                'width': 80,
                                'height': 80,
                                'label': 'data(label)',
                                'font-size': '16px',
                                'color': '#00ff88',
                                'text-outline-width': 2,
                                'text-outline-color': '#003322'
                            }
                        },
                        {
                            selector: 'edge',
                            style: {
                                'width': 2,
                                'line-color': '#00ff88',
                                'target-arrow-color': '#00ff88',
                                'target-arrow-shape': 'triangle',
                                'curve-style': 'bezier'
                            }
                        }
                    ],
                    layout: {
                        name: 'concentric',
                        animate: true,
                        concentric: function(node) {
                            return node.id() === 'u{{ auth()->user()->id }}' ? 100 : 50;
                        },
                        levelWidth: function(nodes) {
                            return 10;
                        },
                        spacingFactor: 1.5
                    }
                });


                // Efek glow bergerak di edge
                cy.edges().forEach(function(edge) {
                    function animateEdge() {
                        edge.animate({ style: { 'line-color': '#00ffaa', 'target-arrow-color': '#00ffaa' } }, { duration: 400 })
                            .animate({ style: { 'line-color': '#00ff88', 'target-arrow-color': '#00ff88' } }, { duration: 400, complete: animateEdge });
                    }
                    animateEdge();
                });

                // Klik node → info popup
                cy.on('tap', 'node', function(evt) {
                    const nodeData = evt.target.data();
                    alert(`Node: ${nodeData.label}\nRole: ${nodeData.role}`);
                });

                       // =======================================================
                // [BARU] Logika untuk Search & Filter
                // =======================================================
                
                // --- FITUR PENCARIAN ---
                const searchInput = document.getElementById('search-input');
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.trim().toUpperCase();
                    cy.elements().removeClass('highlighted faded');

                    if (searchTerm === '') {
                        cy.fit(null, 100);
                        return;
                    }
                    
                    const targetNodes = cy.nodes('[label *= "' + searchTerm + '"]');

                    if (targetNodes.length > 0) {
                        const otherElements = cy.elements().not(targetNodes);
                        targetNodes.addClass('highlighted');
                        otherElements.addClass('faded');
                        cy.animate({ fit: { eles: targetNodes, padding: 150 }, duration: 500 });
                    }
                });

                // --- FITUR FILTER ---
                const filterCheckboxes = document.querySelectorAll('.filter-role');

                function applyFilters() {
                    const selectedRoles = [];
                    filterCheckboxes.forEach(checkbox => {
                        if (checkbox.checked) {
                            selectedRoles.push(checkbox.value);
                        }
                    });

                    if (selectedRoles.length === 0) {
                        cy.elements().style('display', 'element');
                    } else {
                        const roleSelector = selectedRoles.map(role => `node[role = "${role}"]`).join(', ');
                        const filteredNodes = cy.nodes(roleSelector);
                        const visibleElements = filteredNodes.union(filteredNodes.connectedEdges());
                        
                        cy.elements().style('display', 'none');
                        visibleElements.style('display', 'element');
                    }
                }

                filterCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', applyFilters);
                });

            });
        </script>
    @endpush
</x-app-layout>
