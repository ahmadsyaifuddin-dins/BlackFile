<!-- LOGIKA ACTION FORM -->
<form action="{{ $archive ? route('dark-archives.update', $archive->id) : route('dark-archives.store') }}" method="POST"
    enctype="multipart/form-data" class="space-y-8">

    @csrf

    @if ($archive)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Case Code -->
        <div class="relative group">
            <label class="block text-xs text-green-800 uppercase mb-1 tracking-widest">Case Code ID</label>
            <input type="text" name="case_code" value="{{ old('case_code', $archive?->case_code) }}"
                placeholder="CASE-1997-BJM"
                class="w-full bg-gray-900/50 border border-green-900 text-green-500 focus:ring-1 focus:ring-green-500 focus:border-green-500 placeholder-green-900/50 p-3 transition-all font-mono"
                {{ $archive ? 'readonly' : '' }}>
            @error('case_code')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <!-- Incident Date -->
        <div>
            <label class="block text-xs text-green-800 uppercase mb-1 tracking-widest">Incident Date</label>
            <input type="date" name="incident_date"
                value="{{ old('incident_date', $archive?->incident_date?->format('Y-m-d')) }}"
                class="w-full bg-gray-900/50 border border-green-900 text-green-500 focus:ring-1 focus:ring-green-500 focus:border-green-500 p-3 font-mono">
            @error('incident_date')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <!-- Title -->
    <div>
        <label class="block text-xs text-green-800 uppercase mb-1 tracking-widest">Operation / Tragedy Name</label>
        <input type="text" name="title" value="{{ old('title', $archive?->title) }}"
            placeholder="Jumat Kelabu 1997: The Burning Plaza"
            class="w-full bg-gray-900/50 border border-green-900 text-white text-xl md:text-2xl font-bold focus:ring-1 focus:ring-red-900 focus:border-red-900 p-3 font-mono">
        @error('title')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    <!-- Location -->
    <div>
        <label class="block text-xs text-green-800 uppercase mb-1 tracking-widest">Location Coordinates</label>
        <input type="text" name="location" value="{{ old('location', $archive?->location) }}"
            placeholder="Banjarmasin, South Kalimantan"
            class="w-full bg-gray-900/50 border border-green-900 text-gray-400 p-3 font-mono">
    </div>

    <!-- Evidence Photo (Thumbnail) -->
    <div
        class="border border-dashed border-gray-800 p-4 md:p-6 bg-gray-900/20 hover:bg-gray-900/40 transition-colors rounded-lg">
        <label class="block text-xs text-gray-500 uppercase mb-3 tracking-widest">
            {{ $archive ? 'Update Visual Evidence (Leave blank to keep current)' : 'Upload Visual Evidence (Thumbnail)' }}
        </label>

        <!-- Preview Gambar Lama -->
        @if ($archive && $archive->thumbnail)
            <div id="current-evidence" class="mb-4 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <img src="{{ asset($archive->thumbnail) }}" alt="Current Evidence"
                    class="h-20 w-auto border border-red-900 opacity-70 rounded shadow-lg shadow-red-900/20">
                <span class="text-xs text-green-700 font-mono bg-green-900/10 px-2 py-1 rounded">
                    [ CURRENT EVIDENCE ON FILE ]
                </span>
            </div>
        @endif

        <!-- Preview Gambar Baru -->
        <div id="new-preview-container" class="hidden flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-4">
            <img id="new-preview-image" src="#" alt="New Evidence Preview"
                class="h-24 w-auto border border-yellow-600 opacity-100 rounded shadow-lg shadow-yellow-900/20">
            <span class="text-xs text-yellow-500 font-mono bg-yellow-900/10 px-2 py-1 rounded animate-pulse">
                [ NEW EVIDENCE SELECTED_ ]
            </span>
        </div>

        <input type="file" name="thumbnail" id="thumbnail-input" onchange="previewImage(event)"
            class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs md:file:text-sm file:font-semibold file:bg-red-900 file:text-white hover:file:bg-red-800 cursor-pointer font-mono focus:outline-none">
        @error('thumbnail')
            <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- TINYMCE EDITOR AREA -->
    <div>
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-2 mb-2">
            <label class="block text-xs text-green-800 uppercase tracking-widest font-bold">
                &gt; CASE DETAILS REPORT
            </label>
            <div class="group relative cursor-help focus:outline-none self-end sm:self-auto" tabindex="0">
                <span
                    class="text-[10px] text-green-700 font-mono border-b border-dashed border-green-700 hover:text-green-500 group-focus:text-green-500 transition-colors">
                    [ MODE BANTUAN ]
                </span>
                <div
                    class="absolute bottom-full right-0 mb-2 w-48 sm:w-56 bg-black border border-green-900 p-3 text-[10px] text-gray-400 hidden group-hover:block group-focus:block z-50 shadow-[0_0_15px_rgba(0,0,0,0.9)] rounded-sm">
                    <p class="mb-0 leading-relaxed">Gunakan tombol <strong class="text-white">Fullscreen (⤢)</strong> di
                        toolbar di bagian menu Views
                        editor untuk kenyamanan menulis maksimal di perangkat mobile.</p>
                </div>
            </div>
        </div>

        <div class="border border-green-900/50">
            <textarea name="content" id="darkEditor">{{ old('content', $archive?->content) }}</textarea>
        </div>
        @error('content')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    <!-- Status & Buttons -->
    <div class="flex flex-col md:flex-row items-center justify-between pt-8 border-t border-gray-900 gap-4">
        <div class="w-full md:w-auto">
            <label class="block text-xs text-gray-600 uppercase mb-1">Clearance Level</label>
            <select name="status"
                class="w-full bg-gray-900 border border-gray-700 text-gray-300 text-sm p-2 font-mono focus:border-red-900 focus:ring-1 focus:ring-red-900">
                <option value="draft" {{ old('status', $archive?->status) == 'draft' ? 'selected' : '' }}>LEVEL 1:
                    DRAFT (CLASSIFIED)</option>
                <option value="declassified"
                    {{ old('status', $archive?->status) == 'declassified' ? 'selected' : '' }}>LEVEL 0: DECLASSIFY NOW
                    (PUBLIC)</option>
            </select>
        </div>

        <div class="flex flex-col-reverse md:flex-row gap-4 w-full md:w-auto">
            <a href="{{ route('dark-archives.index') }}"
                class="flex items-center justify-center w-full md:w-auto bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-gray-200 font-mono py-3 px-6 tracking-widest text-xs border border-gray-700 hover:border-gray-500 transition-all duration-300">
                [ ABORT ]
            </a>
            <button type="submit"
                class="cursor-pointer group relative w-full md:w-auto bg-red-900/80 hover:bg-red-800 text-white font-bold py-3 px-8 tracking-[0.15em] uppercase text-xs md:text-sm transition-all duration-300 border border-red-800 hover:border-red-600 overflow-hidden">
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

<!-- INCLUDE JAVASCRIPT MODULAR -->
@include('dark-archives._scripts')
