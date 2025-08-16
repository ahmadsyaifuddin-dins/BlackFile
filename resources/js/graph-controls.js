/**
 * Modul untuk menginisialisasi kontrol interaktif pada instance Cytoscape.
 * @param {object} options - Opsi untuk inisialisasi.
 * @param {object} options.cytoscapeInstance - Instance Cytoscape yang aktif (variabel 'cy').
 * @param {string} options.searchInputId - ID dari elemen input untuk pencarian.
 * @param {string} options.filterCheckboxSelector - Selector CSS untuk checkbox filter.
 */
export function initializeGraphControls({ cytoscapeInstance, searchInputId, filterCheckboxSelector }) {
    
    const cy = cytoscapeInstance;
    const searchInput = document.getElementById(searchInputId);
    const filterCheckboxes = document.querySelectorAll(filterCheckboxSelector);

    if (!cy || !searchInput || filterCheckboxes.length === 0) {
        console.error("Graph controls initialization failed: Cytoscape instance or control elements not found.");
        return;
    }

    // --- FITUR PENCARIAN ---
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
    function applyFilters() {
        const selectedRoles = Array.from(filterCheckboxes)
                                 .filter(i => i.checked)
                                 .map(i => i.value);
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

    filterCheckboxes.forEach(checkbox => checkbox.addEventListener('change', applyFilters));
}