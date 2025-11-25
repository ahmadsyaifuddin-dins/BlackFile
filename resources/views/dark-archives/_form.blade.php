<!-- LOGIKA ACTION FORM: Cek apakah variable $archive ada isinya (Edit) atau Null (Create) -->
<form action="{{ $archive ? route('dark-archives.update', $archive->id) : route('dark-archives.store') }}" method="POST"
    enctype="multipart/form-data" class="space-y-8">

    @csrf

    <!-- Jika Edit Mode, tambahkan Method PUT (karena HTML Form cuma support GET/POST) -->
    @if ($archive)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- INPUT: Case Code -->
        <div class="relative group">
            <label class="block text-xs text-green-800 uppercase mb-1 tracking-widest">Case Code ID</label>
            <input type="text" name="case_code" value="{{ old('case_code', $archive->case_code ?? '') }}"
                placeholder="CASE-1997-BJM"
                class="w-full bg-gray-900/50 border border-green-900 text-green-500 focus:ring-1 focus:ring-green-500 focus:border-green-500 placeholder-green-900/50 p-3 transition-all font-mono"
                {{ $archive ? 'readonly' : '' }} title="{{ $archive ? 'Case Code cannot be changed' : '' }}">
            <!-- Note: Case Code biasanya unik dan tidak boleh diganti sembarangan saat edit, jadi bisa di-set readonly jika mau -->
            @error('case_code')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <!-- INPUT: Incident Date -->
        <div>
            <label class="block text-xs text-green-800 uppercase mb-1 tracking-widest">Incident Date</label>
            <input type="date" name="incident_date"
                value="{{ old('incident_date', optional($archive->incident_date ?? null)->format('Y-m-d')) }}"
                class="w-full bg-gray-900/50 border border-green-900 text-green-500 focus:ring-1 focus:ring-green-500 focus:border-green-500 p-3 font-mono">
            @error('incident_date')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <!-- INPUT: Title -->
    <div>
        <label class="block text-xs text-green-800 uppercase mb-1 tracking-widest">Operation / Tragedy Name</label>
        <input type="text" name="title" value="{{ old('title', $archive->title ?? '') }}"
            placeholder="Jumat Kelabu 1997: The Burning Plaza"
            class="w-full bg-gray-900/50 border border-green-900 text-white text-xl md:text-2xl font-bold focus:ring-1 focus:ring-red-900 focus:border-red-900 p-3 font-mono">
        @error('title')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    <!-- INPUT: Location -->
    <div>
        <label class="block text-xs text-green-800 uppercase mb-1 tracking-widest">Location Coordinates</label>
        <input type="text" name="location" value="{{ old('location', $archive->location ?? '') }}"
            placeholder="Banjarmasin, South Kalimantan"
            class="w-full bg-gray-900/50 border border-green-900 text-gray-400 p-3 font-mono">
    </div>

    <!-- INPUT: Evidence Photo -->
    <div class="border border-dashed border-gray-800 p-6 bg-gray-900/20 hover:bg-gray-900/40 transition-colors">
        <label class="block text-xs text-gray-500 uppercase mb-3 tracking-widest">
            {{ $archive ? 'Update Visual Evidence (Leave blank to keep current)' : 'Upload Visual Evidence (Thumbnail)' }}
        </label>

        <!-- Preview Gambar Lama jika sedang Edit -->
        @if ($archive && $archive->thumbnail)
            <div class="mb-4 flex items-center gap-4">
                <img src="{{ asset($archive->thumbnail) }}" alt="Current Evidence"
                    class="h-20 w-auto border border-red-900 opacity-70">
                <span class="text-xs text-green-700">[ CURRENT EVIDENCE ON FILE ]</span>
            </div>
        @endif

        <input type="file" name="thumbnail"
            class="block w-full text-sm text-gray-400
            file:mr-4 file:py-2 file:px-4
            file:border-0
            file:text-sm file:font-semibold
            file:bg-red-900 file:text-white
            hover:file:bg-red-800
            cursor-pointer font-mono
        ">
        @error('thumbnail')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    <!-- INPUT: RICH TEXT EDITOR -->
    <div>
        <label class="block text-xs text-green-800 uppercase mb-2 tracking-widest">> CASE DETAILS REPORT</label>
        <div class="border border-green-900/50">
            <textarea name="content" id="darkEditor">
                {{ old('content', $archive->content ?? '') }}
            </textarea>
        </div>
        @error('content')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    <!-- INPUT: Status & Buttons -->
    <div class="flex flex-col md:flex-row items-center justify-between pt-8 border-t border-gray-900 gap-4">
        <div class="w-full md:w-auto">
            <label class="block text-xs text-gray-600 uppercase mb-1">Clearance Level</label>
            <select name="status"
                class="w-full bg-gray-900 border border-gray-700 text-gray-300 text-sm p-2 font-mono focus:border-red-900 focus:ring-1 focus:ring-red-900">
                <option value="draft" {{ old('status', $archive->status ?? '') == 'draft' ? 'selected' : '' }}>
                    LEVEL 1: DRAFT (CLASSIFIED)
                </option>
                <option value="declassified"
                    {{ old('status', $archive->status ?? '') == 'declassified' ? 'selected' : '' }}>
                    LEVEL 0: DECLASSIFY NOW (PUBLIC)
                </option>
            </select>
        </div>

        <div class="flex flex-col-reverse md:flex-row gap-4 w-full md:w-auto">
            <a href="{{ route('dark-archives.index') }}"
                class="flex items-center justify-center w-full md:w-auto bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-gray-200 font-mono py-3 px-6 tracking-widest text-xs border border-gray-700 hover:border-gray-500 transition-all duration-300">
                [ ABORT ]
            </a>
            <button type="submit"
                class="cursor-pointer group relative w-full md:w-auto bg-red-900/80 hover:bg-red-800 text-white font-bold py-3 px-8 tracking-[0.15em] uppercase text-sm transition-all duration-300 border border-red-800 hover:border-red-600 overflow-hidden">
                <span class="relative z-10 flex items-center justify-center gap-2">
                    {{ $archive ? 'REWRITE ARCHIVE' : 'ENCRYPT & SAVE' }}
                    <span class="text-red-400 group-hover:translate-x-1 transition-transform">>></span>
                </span>
                <div
                    class="absolute inset-0 bg-gradient-to-r from-red-950 to-red-900 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
            </button>
        </div>
    </div>
</form>

<!-- CKEDITOR 5 STYLE OVERRIDES FOR DARK MODE (Dipindah kesini agar terload dimanapun form ini dipakai) -->
<style>
    /* Base Editor Style */
    .ck-editor__editable_inline {
        min-height: 500px;
        background-color: #050505 !important;
        color: #d4d4d4 !important;
        font-family: 'Courier New', Courier, monospace !important;
        padding: 2rem !important;
        border: none !important;
    }

    /* Toolbar Style */
    .ck.ck-toolbar {
        background-color: #111 !important;
        border: none !important;
        border-bottom: 1px solid #14532d !important;
    }

    /* Icons & Text in Toolbar */
    .ck.ck-icon,
    .ck.ck-button__label {
        color: #888 !important;
    }

    .ck.ck-button:hover {
        background-color: #222 !important;
    }

    .ck.ck-button.ck-on {
        background-color: #14532d !important;
        color: white !important;
    }

    /* Content Typography inside Editor */
    .ck-content h2 {
        color: #dc2626 !important;
        border-bottom: 1px solid #333;
        padding-bottom: 5px;
        margin-top: 30px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .ck-content h3 {
        color: #16a34a !important;
        text-transform: uppercase;
        margin-top: 20px;
    }

    .ck-content p {
        margin-bottom: 1em;
        line-height: 1.6;
    }

    .ck-content blockquote {
        border-left: 4px solid #dc2626 !important;
        background: #1a0505 !important;
        color: #a8a29e !important;
        font-style: italic;
        padding: 10px 20px;
        margin: 20px 0;
    }

    .ck-content a {
        color: #ef4444 !important;
        text-decoration: underline;
    }
</style>

<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>
    // Cek apakah editor sudah ter-init sebelumnya untuk menghindari error duplikasi
    if (!window.darkEditorInitialized) {
        ClassicEditor
            .create(document.querySelector('#darkEditor'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'blockQuote', 'bulletedList', 'numberedList', 'link',
                    'insertTable', 'undo', 'redo'
                ],
                placeholder: 'Mulai menulis laporan intelijen...'
            })
            .catch(error => {
                console.error(error);
            });
        window.darkEditorInitialized = true;
    }
</script>
