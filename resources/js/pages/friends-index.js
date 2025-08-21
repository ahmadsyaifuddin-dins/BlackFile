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
            // Style untuk hover dan search
            { selector: '.highlighted', style: { 'min-zoomed-font-size': 12, 'font-weight': 'bold', 'background-color': '#2ea043', 'border-color': '#fff', 'border-width': 2, 'z-index': 9999 }},
            { selector: '.faded', style: { 'opacity': 0.25 }},
            { selector: '.hover-highlighted', style: { 'border-color': '#ffffff', 'border-width': 3, 'box-shadow': '0 0 15px #00ff88' }},
            { selector: '.hover-faded', style: { 'opacity': 0.4 }}
        ],
        layout: {
            name: 'concentric',
            concentric: function(node){ return node.id() === rootNodeId ? 100 : 50; },
            levelWidth: function(nodes){ return 10; },
            spacingFactor: 1.1,
            animate: true
        }
    });

    // --- INTERAKTIVITAS GRAPH ---

    // [DIISI] Logika hover sekarang sudah lengkap
    cy.on('mouseover', 'node', function(evt){
        const selectedNode = evt.target;
        const neighborhood = selectedNode.neighborhood(); // Dapatkan semua elemen yang terhubung langsung

        cy.elements().addClass('hover-faded'); // Buat semua elemen lain meredup
        
        // Buat node yang dipilih dan tetangganya menonjol
        selectedNode.removeClass('hover-faded').addClass('hover-highlighted');
        neighborhood.removeClass('hover-faded');
    });

    cy.on('mouseout', 'node', function(evt){
        // Hapus semua class hover saat mouse keluar
        cy.elements().removeClass('hover-faded hover-highlighted');
    });

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