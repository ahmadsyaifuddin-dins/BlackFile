import { initializeGraphControls } from '../graph-controls.js';

document.addEventListener("DOMContentLoaded", function() {
    const cyContainer = document.getElementById('cy');

    // Jika elemen graph tidak ada di halaman ini, hentikan eksekusi
    if (!cyContainer) return;

    // [DIUBAH] Ambil data dari atribut data-* di elemen #cy
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
            { selector: 'node', style: { 'background-color': '#00ff88', 'label': 'data(label)', 'color': '#00ff88', 'text-outline-width': 1, 'text-outline-color': '#003322', 'font-size': '12px' }},
            { selector: `node[id = "${rootNodeId}"]`, style: { 'width': 80, 'height': 80, 'font-size': '16px' }},
            { selector: 'edge', style: { 'width': 2, 'line-color': '#00ff88', 'target-arrow-color': '#00ff88', 'target-arrow-shape': 'triangle', 'curve-style': 'bezier' }},
            { selector: '.highlighted', style: { 'min-zoomed-font-size': 12, 'font-weight': 'bold', 'background-color': '#2ea043', 'border-color': '#fff', 'border-width': 2, 'z-index': 9999 }},
            { selector: '.faded', style: { 'opacity': 0.25 }},
            { selector: '.hover-highlighted', style: { 'border-color': '#ffffff', 'border-width': 3, 'box-shadow': '0 0 15px #00ff88' }},
            { selector: '.hover-faded', style: { 'opacity': 0.4 }}
        ],
        layout: {
            name: 'concentric',
            concentric: function(node){ return node.id() === rootNodeId ? 100 : 50; },
            levelWidth: function(nodes){ return 10; },
            spacingFactor: 1.5,
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