@props([
    'archive' => null, // Null jika Create, Object jika Edit
    'categories' => [],
])

@php
    $isEdit = !is_null($archive);
    $action = $isEdit ? route('archives.update', $archive) : route('archives.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<div class="bg-surface border border-border-color rounded-md shadow-lg">
    <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <div class="p-6 space-y-6 font-mono">

            {{-- 1. Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-primary mb-1">> Entry Name</label>
                <x-forms.input 
                    name="name" 
                    id="name" 
                    :value="old('name', $archive->name ?? '')" 
                    required 
                />
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- 2. Description --}}
            <div>
                <x-forms.textarea 
                    label="> Description (Optional)"
                    name="description" 
                    id="description" 
                    rows="4" 
                    :value="old('description', $archive->description ?? '')"
                />
                @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- 3. Preview Image --}}
            <div>
                <label for="preview_image_url" class="block text-sm font-medium text-secondary mb-1">> Preview Image URL</label>
                <x-forms.input 
                    type="url" 
                    name="preview_image_url" 
                    class="form-underline"
                    id="preview_image_url" 
                    :value="old('preview_image_url', $archive->preview_image_url ?? '')"
                    placeholder="https://example.com/image.jpg" 
                />
                @error('preview_image_url') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- 4. Category (Alpine Logic untuk 'Other') --}}
            <div x-data="{ 
                cat: '{{ old('category', $archive->category ?? '') }}',
                init() {
                    // Jika kategori yang ada tidak ada di list opsi, anggap 'Other'
                    const options = {{ json_encode($categories) }};
                    if (this.cat && !options.includes(this.cat)) {
                        this.cat = 'Other';
                    }
                }
            }">
                <div>
                    <label for="category" class="block text-sm font-medium text-primary mb-1">> Category</label>
                    <select name="category" id="category" x-model="cat" class="form-control cursor-pointer">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Input Kategori Lainnya --}}
                <div x-show="cat === 'Other'" x-transition class="mt-4 pl-4 border-l-2 border-border-color">
                    <label for="category_other" class="block text-sm font-medium text-secondary mb-1">> Specify Other Category</label>
                    <x-forms.input 
                        name="category_other" 
                        id="category_other" 
                        :value="old('category_other', $archive->category_other ?? '')"
                        placeholder="e.g., Top Secret Protocol" 
                    />
                    @error('category_other') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                @error('category') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- 5. Tags (CUSTOM UI: UNDERLINE ONLY) --}}
            <div>
                <label for="tags" class="block text-sm font-medium text-secondary mb-1">> Tags (Comma Separated)</label>
                <input type="text" 
                       name="tags" 
                       id="tags" 
                       value="{{ old('tags', $isEdit ? $archive->tags->pluck('name')->implode(',') : '') }}"
                       class="form-underline" {{-- Class baru kita --}}
                       placeholder="report, analysis, q4"
                >
                @error('tags') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- 6. Visibility --}}
            <div class="bg-base border border-border-color rounded-md p-4 flex items-center justify-between">
                <div>
                    <label for="is_public" class="font-medium text-primary">Public Access</label>
                    <p class="text-xs text-secondary mt-1">Make this entry visible to other agents.</p>
                </div>
                <label for="is_public" class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_public" id="is_public" value="1" class="sr-only peer"
                        @checked(old('is_public', $archive->is_public ?? false))>
                    <div class="w-11 h-6 bg-gray-800 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                </label>
            </div>

            {{-- 7. Entry Type Logic (Beda antara Create & Edit) --}}
            <div x-data="{ type: '{{ old('type', $archive->type ?? 'file') }}' }" class="space-y-4 pt-4 border-t border-border-color">
                
                @if(!$isEdit)
                    {{-- MODE CREATE: Tampilkan Pilihan Radio --}}
                    <fieldset>
                        <legend class="block text-sm font-medium text-primary mb-4">> Entry Type</legend>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Radio File --}}
                            <label class="relative flex p-4 border rounded-md cursor-pointer transition-colors" 
                                :class="type === 'file' ? 'border-primary bg-primary/10' : 'border-border-color hover:border-primary/50'">
                                <input type="radio" name="type" value="file" x-model="type" class="sr-only">
                                <div class="flex items-center gap-3">
                                    <svg class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                    <span class="font-bold text-white">File Upload</span>
                                </div>
                            </label>
                            {{-- Radio URL --}}
                            <label class="relative flex p-4 border rounded-md cursor-pointer transition-colors" 
                                :class="type === 'url' ? 'border-primary bg-primary/10' : 'border-border-color hover:border-primary/50'">
                                <input type="radio" name="type" value="url" x-model="type" class="sr-only">
                                <div class="flex items-center gap-3">
                                    <svg class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                                    <span class="font-bold text-white">URL Link</span>
                                </div>
                            </label>
                        </div>
                    </fieldset>
                @endif

                {{-- INPUT AREAS --}}
                
                {{-- A. File Input (Hanya Muncul di Create Mode) --}}
                @if(!$isEdit)
                    <div x-show="type === 'file'" x-transition>
                        <label for="archive_file" class="block text-sm font-medium text-white mb-2">> Select Source File</label>
                        <input type="file" name="archive_file" id="archive_file" 
                            class="block w-full text-sm text-secondary
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-primary file:text-black
                            hover:file:bg-primary-hover cursor-pointer"/>
                        @error('archive_file') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                @endif

                {{-- B. Info File (Hanya Muncul di Edit Mode jika tipe file) --}}
                @if($isEdit && $archive->type === 'file')
                    <div class="p-4 bg-surface-light border border-border-color rounded-md flex items-start gap-3">
                        <svg class="h-5 w-5 text-secondary mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <div>
                            <p class="text-sm text-white font-bold">File Locked</p>
                            <p class="text-xs text-secondary">To replace the file, create a new entry.</p>
                        </div>
                    </div>
                @endif

                {{-- C. URL Input (Muncul di Create jika pilih URL, ATAU di Edit jika tipe URL) --}}
                {{-- Kita gunakan logika Alpine (Create) atau PHP (Edit) --}}
                <div 
                    @if(!$isEdit) x-show="type === 'url'" x-transition @endif
                    @if($isEdit && $archive->type !== 'url') style="display: none;" @endif
                >
                    <label for="links" class="block text-sm font-medium text-secondary mb-1">> URL Link(s)</label>
                    {{-- CUSTOM UI: UNDERLINE ONLY --}}
                    <textarea 
                        name="links" 
                        id="links" 
                        rows="5" 
                        class="form-underline font-mono"
                        placeholder="https://..."
                    >{{ old('links', $isEdit && $archive->links ? implode("\n", $archive->links) : '') }}</textarea>
                    
                    <p class="text-xs text-secondary mt-1">One link per line.</p>
                    @error('links') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

            </div>

        </div>

        {{-- Footer --}}
        <div class="bg-surface-light border-t border-border-color px-6 py-4 text-right rounded-b-md">
            <x-button type="submit">
                {{ $isEdit ? '> UPDATE DATABASE' : '> INITIATE UPLOAD' }}
            </x-button>
        </div>
    </form>
</div>