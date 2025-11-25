<!-- Menggunakan TinyMCE 6 (Versi Stabil & Gratis via Cloudflare CDN) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>

<script>
    /**
     * 1. FUNGSI PREVIEW IMAGE THUMBNAIL
     */
    function previewImage(event) {
        const input = event.target;
        const previewContainer = document.getElementById('new-preview-container');
        const previewImage = document.getElementById('new-preview-image');
        const currentEvidence = document.getElementById('current-evidence');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
                if (currentEvidence) currentEvidence.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            previewContainer.classList.add('hidden');
            if (currentEvidence) currentEvidence.classList.remove('hidden');
        }
    }

    /**
     * 2. INISIALISASI TINYMCE (THE DARK EDITOR)
     */
    if (!window.darkEditorInitialized) {
        tinymce.init({
            selector: '#darkEditor',
            height: 600,
            menubar: false,
            promotion: false,
            branding: false,

            // TEMA GELAP
            skin: 'oxide-dark',
            content_css: 'dark',

            // PLUGIN
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen', // Pastikan fullscreen ada
                'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons'
            ],

            // TOOLBAR: Ditambahkan 'fullscreen' di akhir
            toolbar: 'undo redo | blocks fontfamily fontsize | ' +
                'bold italic underline strikethrough | forecolor backcolor | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | ' +
                'link image table | fullscreen removeformat code',

            // KONFIGURASI MOBILE (RESPONSIVE)
            mobile: {
                menubar: true,
                toolbar_mode: 'floating',
                height: '100vh' // Full height di mobile saat focus
            },

            // CONFIG GAMBAR (BASE64)
            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
            paste_data_images: true,

            file_picker_callback: (cb, value, meta) => {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');

                input.addEventListener('change', (e) => {
                    const file = e.target.files[0];
                    const reader = new FileReader();

                    reader.addEventListener('load', () => {
                        const id = 'blobid' + (new Date()).getTime();
                        const blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        const base64 = reader.result.split(',')[1];
                        const blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);
                        cb(blobInfo.blobUri(), {
                            title: file.name
                        });
                    });
                    reader.readAsDataURL(file);
                });

                input.click();
            },

            // CSS KONSISTENSI FONT
            content_style: `
                body { 
                    font-family: 'Courier New', Courier, monospace; 
                    background-color: #050505; 
                    color: #d4d4d4; 
                    font-size: 14px;
                    line-height: 1.6;
                }
                h1, h2, h3, h4, h5, h6 { color: #dc2626; text-transform: uppercase; margin-top: 1em; }
                a { color: #ef4444; }
                blockquote { border-left: 3px solid #dc2626; padding-left: 10px; color: #a8a29e; font-style: italic; background: #111; padding: 10px; }
            `
        });

        window.darkEditorInitialized = true;
    }
</script>

<style>
    /* Styling agar Editor menyatu dengan tema Dark */
    .tox-tinymce {
        border: 1px solid #14532d !important;
        border-radius: 0 !important;
    }

    .tox .tox-toolbar,
    .tox .tox-toolbar__overflow,
    .tox .tox-toolbar__primary {
        background: #111 !important;
        border-bottom: 1px solid #14532d !important;
    }

    .tox .tox-tbtn {
        color: #888 !important;
    }

    .tox .tox-tbtn:hover {
        background: #222 !important;
        color: #fff !important;
    }

    /* Tombol Aktif */
    .tox .tox-tbtn--enabled,
    .tox .tox-tbtn--enabled:hover {
        background: #14532d !important;
        color: #fff !important;
    }

    .tox .tox-statusbar {
        background: #050505 !important;
        border-top: 1px solid #14532d !important;
        color: #666 !important;
    }

    .tox-statusbar__branding {
        display: none !important;
    }
</style>
