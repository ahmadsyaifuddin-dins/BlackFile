// resources/js/archive-thumb.js
// Thumbnail arsip dengan fallback gracefully.
// Alur: coba muat preview_image_url -> kalau gagal/URL kosong, pakai ikon default
// sesuai tipe arsip. Dipakai di table view (ikon kecil) dan card view (gambar besar).

const DEFAULT_ICONS = {
    url: 'M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244',
    file: 'M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9z',
    image: 'm2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0z',
};

const GENERIC_ICON =
    'M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z';

export default function archiveThumb(config = {}) {
    return {
        // URL gambar dari database (bisa null/kosong)
        src: config.src || null,
        // Tipe arsip: menentukan ikon fallback
        type: config.type || 'url',
        // Nama arsip, dipakai untuk alt text
        name: config.name || '',

        // Status muat gambar
        loaded: false,
        failed: false,
        loading: true,

        // Ikon fallback sesuai tipe
        get fallbackIcon() {
            return DEFAULT_ICONS[this.type] || GENERIC_ICON;
        },

        // Tampilkan gambar hanya kalau ada URL, belum gagal, dan sudah selesai load
        get showImage() {
            return Boolean(this.src) && !this.failed;
        },

        // Tampilkan ikon default kalau tidak ada / gagal load
        get showFallback() {
            return !this.src || this.failed;
        },

        init() {
            // Tanpa URL -> langsung ke mode fallback, jangan tunggu error 404
            if (!this.src) {
                this.loading = false;
                this.failed = true;
                return;
            }

            // Muat gambar secara manual supaya bisa deteksi error-nya
            const probe = new Image();

            probe.onload = () => {
                this.loaded = true;
                this.loading = false;
            };

            probe.onerror = () => {
                // URL rusak / 404 / diblokir -> pakai ikon default
                this.failed = true;
                this.loading = false;
            };

            probe.src = this.src;
        },
    };
}