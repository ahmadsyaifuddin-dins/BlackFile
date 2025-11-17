@props([
    'entity' => null, // Opsional, untuk halaman edit
])

<div x-data="{
    tab: 'upload',
    files: [],
    filePreviews: [],
    urlInput: '',
    // Logika untuk selectedThumbnail:
    // 1. Cek old('thumbnail_selection')
    // 2. Jika tidak ada, cek apakah ada entity (mode edit) dan ambil thumbnail_image_id
    // 3. Jika tidak ada, default ke 'none'
    selectedThumbnail: '{{ old('thumbnail_selection', $entity->thumbnail_image_id ?? 'none') }}',

    handleFileSelect(event) {
        this.files = Array.from(event.target.files);
        this.filePreviews = [];
        this.files.forEach((file, index) => {
            let reader = new FileReader();
            reader.onload = (e) => {
                this.filePreviews.push(e.target.result);
            };
            reader.readAsDataURL(file);
            // Otomatis pilih file baru pertama sebagai thumbnail
            if(index === 0) {
                this.selectedThumbnail = 'new_index_' + index;
            }
        });
    },
    handleUrlChange() {
        if(this.urlInput.trim() !== '') {
            this.selectedThumbnail = 'new_url';
        }
    }
}" class="pt-6 border-t-2 border-dashed border-border-color space-y-6">

    {{-- Input Tersembunyi untuk Thumbnail --}}
    {{-- Ini akan mengirim ID (jika memilih yg lama), 'new_index_0', 'new_url', atau 'none' --}}
    <input type="hidden" name="thumbnail_selection" x-model="selectedThumbnail">

    {{-- 1. Gambar yang Sudah Ada (Hanya tampil di mode edit) --}}
    @if($entity && $entity->images->isNotEmpty())
    <div>
        <label class="block text-primary">> CURRENT_VISUAL_EVIDENCE</label>
        <p class="text-xs text-secondary mb-3">Select one as thumbnail or mark for termination.</p>
        <div class="mt-2 grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
            @foreach($entity->images as $image)
            @php
                $imagePath = Illuminate\Support\Str::startsWith($image->path, 'http')
                ? $image->path
                : asset('uploads/' . $image->path);
            @endphp
            <div class="relative group">
                <img src="{{ $imagePath }}" alt="{{ $image->caption ?? '' }}"
                    class="w-full h-32 object-cover rounded-none border-2 transition-all"
                    :class="selectedThumbnail == '{{ $image->id }}' ? 'border-primary' : 'border-border-color'">
                
                {{-- Radio Button untuk Thumbnail --}}
                <div class="mt-2 text-center">
                    <label for="thumb_id_{{ $image->id }}"
                        class="flex items-center justify-center text-xs text-secondary cursor-pointer">
                        <x-forms.radio x-model="selectedThumbnail" value="{{ $image->id }}"
                            id="thumb_id_{{ $image->id }}"
                            class="w-4 h-4 bg-base border-border-color text-primary focus:ring-primary mr-2">
                        Set Thumbnail
                        </x-forms.radio>
                    </label>
                </div>
                
                {{-- Checkbox untuk Hapus --}}
                <div class="mt-1 text-center">
                    <label for="delete_image_{{ $image->id }}"
                        class="flex items-center justify-center text-xs text-red-400 cursor-pointer">
                        <x-forms.checkbox name="images_to_delete[]" value="{{ $image->id }}"
                            id="delete_image_{{ $image->id }}"
                            class="w-4 h-4 bg-base border-border-color text-red-600 focus:ring-red-500 mr-2">
                        TERMINATE
                        </x-forms.checkbox>
                    </label>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- 2. Input Gambar Baru (Tampil di create dan edit) --}}
    <div>
        <label class="block text-primary mb-2">
            @if($entity)
            > ATTACH_NEW_EVIDENCE (This will override thumbnail selection)
            @else
            > ATTACH_VISUAL_EVIDENCE
            @endif
        </label>

        {{-- Tombol Tab --}}
        <div class="mb-4 flex border-b border-border-color">
            <button type="button" @click="tab = 'upload'"
                :class="{'border-primary text-primary': tab === 'upload', 'border-transparent text-secondary': tab !== 'upload'}"
                class="py-2 px-4 border-b-2 font-medium transition-colors">
                Upload File
            </button>
            <button type="button" @click="tab = 'link'"
                :class="{'border-primary text-primary': tab === 'link', 'border-transparent text-secondary': tab !== 'link'}"
                class="py-2 px-4 border-b-2 font-medium transition-colors">
                Link from URL
            </button>
        </div>

        {{-- Konten Tab --}}
        <div>
            {{-- Tab 1: Upload File --}}
            <div x-show="tab === 'upload'" x-transition>
                <p class="text-xs text-secondary mb-2">You can upload multiple files at once.</p>
                <input type="file" name="images[]" multiple @change="handleFileSelect"
                    class="block w-full text-sm text-secondary file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-black hover:file:bg-primary-hover cursor-pointer">
                
                {{-- Preview untuk File Upload BARU --}}
                <div x-show="filePreviews.length > 0" class="mt-4">
                    <label class="block text-primary mb-2">> SELECT_NEW_THUMBNAIL</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                        <template x-for="(preview, index) in filePreviews" :key="index">
                            <div class="relative group">
                                <img :src="preview" class="w-full h-32 object-cover border-2" :class="selectedThumbnail === 'new_index_' + index ? 'border-primary' : 'border-border-color'">
                               
                                <div class="mt-2 flex justify-center">
                                    <x-forms.radio
                                        {{-- Gunakan x-bind: untuk atribut Alpine --}}
                                        x-bind:id="'thumb_new_' + index"
                                        x-model="selectedThumbnail"
                                        x-bind:value="'new_index_' + index"
                                    >
                                        Set Thumbnail
                                    </x-forms.radio>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Tab 2: Link from URL --}}
            <div x-show="tab === 'link'" x-transition style="display: none;">
                <label for="image_url" class="flex-shrink-0 text-primary">> IMAGE_URL:</label>
                <x-forms.input type="text" name="image_url" id="image_url"
                    placeholder="https://... Paste image URL here"
                    x-model="urlInput" @input="handleUrlChange"
                    class="mt-1 w-full bg-transparent border-0 border-b-2 border-border-color focus:border-primary focus:ring-0 text-white">
                </x-forms.input>
                <p class="text-xs text-secondary mt-2">Note: Only one URL can be added at a time. This will be automatically selected as thumbnail.</p>
            
                {{-- Preview untuk URL BARU --}}
                <div x-show="urlInput.trim() !== ''" class="mt-4">
                    <label class="block text-primary mb-2">> SELECT_NEW_THUMBNAIL</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                        <div class="relative group">
                            <img :src="urlInput" class="w-full h-32 object-cover border-2" 
                                 :class="selectedThumbnail === 'new_url' ? 'border-primary' : 'border-border-color'" 
                                 x-on:error.once="$event.target.src = '{{ asset('images/placeholder-error.jpg') }}'"> {{-- Fallback jika URL error --}}
                            <div class="mt-2 text-center">
                                <x-forms.radio id="thumb_url" x-model="selectedThumbnail" value="new_url" class="w-4 h-4 bg-base border-border-color text-primary focus:ring-primary mr-2">
                                    Set Thumbnail
                                </x-forms.radio>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>