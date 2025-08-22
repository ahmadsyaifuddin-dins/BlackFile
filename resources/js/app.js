import './bootstrap';

import cytoscape from 'cytoscape';
window.cytoscape = cytoscape;

// Baris-baris ini SANGAT PENTING untuk Alpine.js
import Alpine from 'alpinejs';

// [BARU] Impor plugin Intersect dari Alpine.js
import intersect from '@alpinejs/intersect';

// [BARU] Daftarkan plugin Intersect ke Alpine
Alpine.plugin(intersect);

// [ADD THESE TWO LINES]
import prototypesCRUD from './prototypes-crud';
Alpine.data('prototypesCRUD', prototypesCRUD);
// [END OF ADDITION]

window.Alpine = Alpine;
Alpine.start();