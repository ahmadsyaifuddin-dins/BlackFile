import './bootstrap';

import cytoscape from 'cytoscape';
window.cytoscape = cytoscape;

// Baris-baris ini SANGAT PENTING untuk Alpine.js
import Alpine from 'alpinejs';

// [ADD THESE TWO LINES]
import prototypesCRUD from './prototypes-crud';
Alpine.data('prototypesCRUD', prototypesCRUD);
// [END OF ADDITION]

window.Alpine = Alpine;
Alpine.start();