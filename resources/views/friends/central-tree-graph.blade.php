<x-app-layout>
    <x-slot:title>Central Tree (Radar Style)</x-slot>

    <div class="p-6">
        <h1 class="text-2xl mb-4 text-green-400 font-bold">
            Central Network: {{ auth()->user()->codename }}
        </h1>
        <div id="cy" style="width: 100%; height: 600px; background: black;"></div>
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
            });
        </script>
    @endpush

</x-app-layout>
