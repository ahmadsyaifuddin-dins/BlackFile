import { initializeGraphControls } from '../graph-controls.js';

document.addEventListener("DOMContentLoaded", function() {
    const cyContainer = document.getElementById('cy');

    if (!cyContainer) return;

    const nodes = JSON.parse(cyContainer.dataset.nodes);
    const edges = JSON.parse(cyContainer.dataset.edges);
    const rootNodeId = cyContainer.dataset.rootnodeid;

    const cy = window.cytoscape({
        container: cyContainer,
        elements: {
            nodes: nodes,
            edges: edges
        },
        style: [
            {
                selector: 'node',
                style: {
                    'background-image': function(ele) {
                        return ele.data('avatar') || 'none';
                    },
                    // [PERBAIKAN CORS] Tambahkan properti ini
                    'background-image-crossorigin': 'anonymous',
                    
                    'background-color': function(ele) {
                        return ele.data('avatar') ? 'transparent' : '#00ff88';
                    },
                    'background-fit': 'cover',
                    'border-color': '#00ff88',
                    'border-width': 2,
                    'text-valign': 'bottom',
                    'text-margin-y': 5,
                    'label': 'data(label)',
                    'color': '#00ff88',
                    'text-outline-width': 2,
                    'text-outline-color': '#000',
                    'font-size': '12px'
                }
            },
            {
                // [PERBAIKAN BUG] Gunakan template literal JavaScript, bukan Blade
                selector: `node[id = "${rootNodeId}"]`,
                style: {
                    'width': 80,
                    'height': 80,
                    'font-size': '16px'
                }
            },
            {
                selector: 'edge',
                style: { 'width': 2, 'line-color': '#00ff88', 'target-arrow-color': '#90ee90', 'target-arrow-shape': 'triangle-backcurve', 'curve-style': 'segments' }
            },
        ],
        layout: {
            name: 'concentric',
            concentric: function(node){ return node.id() === rootNodeId ? 100 : 50; },
            levelWidth: function(nodes){ return 10; },
            spacingFactor: 1.1, // Jarak antara garis node ke pusat
            animate: true
        }
    });

    // --- INTERAKTIVITAS GRAPH ---
    cy.on('mouseover', 'node', function(evt){ /* ... logika hover ... */ });
    cy.on('mouseout', 'node', function(evt){ /* ... logika hover ... */ });
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