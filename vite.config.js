// vite.config.js (Kode Baru & Benar)

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

// [PERBAIKAN] Impor plugin di bagian atas menggunakan sintaks ESM
import aspectRatio from '@tailwindcss/aspect-ratio';

export default defineConfig({
  plugins: [
    tailwindcss({
        plugins: [
            // [PERBAIKAN] Gunakan variabel yang sudah di-impor
            aspectRatio,
        ]
    }),
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/graph-controls.js','resources/js/pages/friends-index.js' ],
      refresh: true,
    }),
  ],
});