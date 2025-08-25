// resources/js/app.js

// 1. Impor semua library dan modul yang dibutuhkan di bagian paling atas
import './bootstrap';
import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';
import axios from 'axios';
import cytoscape from 'cytoscape';
import prototypesCRUD from './prototypes-crud';
import archiveForm from './forms/archive-form.js';

// 2. Buat library bisa diakses secara global (di window object)
//    PENTING: Lakukan ini SEBELUM mendaftarkan plugin atau data
window.Alpine = Alpine;
window.axios = axios;
window.cytoscape = cytoscape;

// 3. Daftarkan plugin-plugin Alpine
Alpine.plugin(intersect);

// 4. Daftarkan semua komponen data Alpine Anda
Alpine.data('prototypesCRUD', prototypesCRUD);
Alpine.data('archiveForm', archiveForm);

// 5. Jalankan Alpine. Ini HARUS menjadi baris terakhir.
Alpine.start();