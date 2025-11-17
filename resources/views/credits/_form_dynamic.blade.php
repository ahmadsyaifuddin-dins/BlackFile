<!-- resources/views/credits/_form_dynamic.blade.php -->

<div x-data="creditsForm({ 
        initialCredits: {{ $credits ?? '[]' }}, 
        initialMusicPath: '{{ $musicPath ?? '' }}' })" class="text-white">
    @if($errors->any())
    <div class="mb-4 bg-red-900/50 border-l-4 border-red-500 text-red-300 p-4 rounded-r-lg" role="alert">
        <p class="font-bold">> Data Input Anomaly Detected:</p>
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <!-- Daftar Section/Grup Credits -->
    <template x-for="(credit, creditIndex) in credits" :key="credit.uniqueKey">
        <div class="p-4 border border-gray-700 rounded-lg mb-6 bg-gray-900/50 relative">
            <input type="hidden" :name="'credits[' + creditIndex + '][id]'" x-model="credit.id">
            <button @click="removeCredit(creditIndex)" type="button"
                class="absolute top-2 right-2 text-gray-500 hover:text-red-500 p-1 leading-none text-2xl cursor-pointer">&times;</button>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300">Section Title / Role</label>
                <input type="text" :name="'credits[' + creditIndex + '][role]'" x-model="credit.role" required
                    placeholder="e.g., Lead Analysts"
                    class="mt-1 block w-full bg-gray-800 border-gray-600 rounded-md shadow-sm text-white focus:border-cyan-500 focus:ring-cyan-500">
            </div>

            <!-- Kontainer untuk Names dengan toggle -->
            <div class="pl-4 border-l-2 border-gray-700" x-data="{ editMode: 'visual' }">
                <div class="flex justify-between items-center mb-2">
                    <h4 class="text-sm text-gray-400">Names</h4>
                    <div class="flex text-xs border border-gray-700 rounded-md">
                        <button @click.prevent="editMode = 'visual'; switchToVisual(creditIndex)" :class="editMode === 'visual' ? 'bg-primary text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700'" class="px-2 py-1 rounded-l-md">Visual</button>
                        <button @click.prevent="editMode = 'raw'; switchToRaw(creditIndex)" :class="editMode === 'raw' ? 'bg-primary text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700'" class="px-2 py-1 rounded-r-md">Raw JSON</button>
                    </div>
                </div>
                
                <!-- Mode Visual (Add/Remove) -->
                <div x-show="editMode === 'visual'" class="space-y-2">
                    <template x-for="(name, nameIndex) in credit.names" :key="`${creditIndex}-${nameIndex}`">
                        <!-- Dibuat responsif -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                            <input type="text" :name="'credits[' + creditIndex + '][names][' + nameIndex + ']'" x-model="credit.names[nameIndex]" required placeholder="Full Name" class="flex-grow bg-gray-800 border-gray-600 rounded-md shadow-sm text-white">
                            <button @click.prevent="removeName(creditIndex, nameIndex)" type="button" class="text-red-400 hover:text-red-300 text-sm self-end sm:self-center px-2 py-1 rounded-md bg-gray-700/50 hover:bg-red-900/50">Remove</button>
                        </div>
                    </template>
                    <button @click.prevent="addName(creditIndex)" type="button" class="mt-2 text-xs text-primary hover:underline">+ Add Name</button>
                </div>

                <!-- Mode Raw JSON -->
                <div x-show="editMode === 'raw'" style="display: none;">
                    <textarea x-model="credit.namesJson" 
                              @input.debounce.500ms="updateNamesFromJson(creditIndex)"
                              class="w-full h-48 bg-gray-900/50 border-gray-600 rounded-md text-primary font-mono text-sm"
                              placeholder='[&#10;    "Nama Pertama",&#10;    "Nama Kedua"&#10;]'>
                    </textarea>
                    <!-- Tambahkan tombol Format JSON -->
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-500">Edit the list as a JSON array of strings.</p>
                        <button @click.prevent="formatJson(creditIndex)" type="button" class="text-xs bg-gray-700 hover:bg-gray-600 text-gray-300 px-2 py-1 rounded-md">Format JSON</button>
                    </div>
                </div>
            </div>

            <div class="mt-4 pl-4 border-l-2 border-gray-700">
                <h4 class="text-sm text-gray-400 mb-2">Logos for this Section</h4>
                <template x-for="(logo, logoIndex) in credit.logos" :key="logoIndex">
                    <!-- Dibuat tumpuk di mobile, berdampingan di layar besar -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 mb-2">
                        <select :name="'credits[' + creditIndex + '][logos][' + logoIndex + '][type]'"
                            x-model="logo.type" class="bg-gray-800 border-gray-600 rounded-md text-sm">
                            <option value="file">File</option>
                            <option value="url">URL</option>
                        </select>
                        <div class="flex-grow">
                            <template x-if="logo.type === 'url'"><input type="url"
                                    :name="'credits[' + creditIndex + '][logos][' + logoIndex + '][path]'"
                                    x-model="logo.path"
                                    class="w-full bg-gray-800 border-gray-600 rounded-md"></template>
                            <template x-if="logo.type === 'file'">
                                <div>
                                    <input type="hidden"
                                        :name="'credits[' + creditIndex + '][logos][' + logoIndex + '][path]'"
                                        :value="logo.path">
                                    <div x-show="logo.path" class="my-2 flex items-center gap-2">
                                        <img :src="'/' + logo.path" class="h-8 w-8 object-contain bg-white p-1 rounded">
                                        <span x-text="logo.path.split('/').pop()"
                                            class="text-xs text-gray-400 truncate"></span>
                                    </div>
                                    <input type="file"
                                        :name="'credits[' + creditIndex + '][logos][' + logoIndex + '][file]'"
                                        class="cursor-pointer w-full text-sm text-gray-400 file:file-primary file:file-primary-hover file:border-0 file:rounded file:px-2 file:py-1 file:text-white">
                                </div>
                            </template>
                        </div>
                        <button @click="removeLogo(creditIndex, logoIndex)" type="button"
                            class="text-red-500 text-xs px-2 py-1 bg-gray-700/50 rounded-md hover:bg-red-900/50 self-end sm:self-center cursor-pointer">Remove</button>
                    </div>
                </template>
                <button @click="addLogo(creditIndex)" type="button"
                    class="text-xs text-primary hover:underline cursor-pointer ">+ Add Logo</button>
            </div>
        </div>
    </template>

    <button @click="addCredit" type="button"
        class="cursor-pointer mt-4 text-sm font-semibold text-white bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-md">+
        Add Section</button>

    <!-- Bagian Musik dan Tombol Save (SUDAH DIPERBARUI) -->
    <div class="mt-8 pt-6 border-t border-gray-700" x-data="{ 
    musicSource: '{{ (isset($musicPath) && Str::startsWith($musicPath, 'uploads/credits/music')) ? 'custom' : 'default' }}',
    selectedMusic: '{{ (isset($musicPath) && Str::startsWith($musicPath, 'music/default')) ? $musicPath : '' }}'
}">
        <h3 class="text-lg font-medium text-gray-100 mb-4">Background Music</h3>

        <!-- Pilihan Sumber Musik -->
        <div class="flex items-center space-x-4 mb-4">
            <label class="flex items-center text-white">
                <x-forms.radio name="music_source" value="default" x-model="musicSource"
                    class="form-radio bg-gray-900 border-gray-700 text-primary">
                <span class="ml-2">Use Default Music</span>
                </x-forms.radio>
            </label>
            <label class="flex items-center text-white">
                <x-forms.radio name="music_source" value="custom" x-model="musicSource"
                    class="form-radio bg-gray-900 border-gray-700 text-primary">
                <span class="ml-2">Upload Custom</span>
                </x-forms.radio>
            </label>
        </div>

        <!-- Dropdown Musik Bawaan dengan Tombol Preview -->
        <div x-show="musicSource === 'default'" x-transition>
            <label for="default_music_path" class="block text-sm font-medium text-gray-300">Select Music</label>
            <div class="flex items-center gap-2 mt-1">
                <select name="default_music_path" id="default_music_path" x-model="selectedDefaultMusic"
                    class="block w-full bg-gray-800 border-gray-600 rounded-md text-white focus:border-primary focus:ring-primary">
                    <option value="">-- No Music --</option>
                    @foreach($defaultMusics as $music)
                    <option value="{{ $music->path }}">{{ $music->name }}</option>
                    @endforeach
                </select>
                <!-- Tombol Preview BARU -->
                <button @click.prevent="togglePreview(selectedDefaultMusic)" :disabled="!selectedDefaultMusic"
                    type="button" title="Preview Music"
                    class="flex-shrink-0 px-3 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <!-- Ikon Play -->
                    <svg x-show="nowPlaying !== selectedDefaultMusic || !selectedDefaultMusic" class="w-5 h-5"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z">
                        </path>
                    </svg>
                    <!-- Ikon Pause -->
                    <svg x-show="nowPlaying === selectedDefaultMusic" style="display: none;" class="w-5 h-5"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M5.75 3a.75.75 0 00-.75.75v12.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V3.75A.75.75 0 007.25 3h-1.5zm7 0a.75.75 0 00-.75.75v12.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V3.75a.75.75 0 00-.75-.75h-1.5z">
                        </path>
                    </svg>
                </button>
            </div>
        </div>


        <!-- Upload Musik Custom -->
        <div x-show="musicSource === 'custom'" x-transition>
            <label for="music" class="block text-sm font-medium text-gray-300">Upload New Music (MP3, WAV)</label>
            <input type="file" name="music" id="music" accept="audio/mp3,audio/wav,audio/mpeg"
                class="cursor-pointer mt-1 block w-full text-sm text-gray-400 file:bg-primary file:text-white file:border-0 file:rounded file:px-4 file:py-2">
            @error('music')
            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror
            @if(isset($musicPath) && Str::startsWith($musicPath, 'uploads/credits/music'))
            <div class="mt-4">
                <p class="text-xs text-gray-400 mb-2">Current Custom Music:</p>
                <audio controls class="w-full">
                    <source src="{{ url($musicPath) }}" type="audio/mpeg">
                </audio>
            </div>
            @endif
        </div>
    </div>


    <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-700">
        <x-button type="submit">Save All Credits</x-button>
    </div>
</div>

<script>
    function creditsForm({ initialCredits, initialMusicPath }) {
        return {
            credits: [],
            musicSource: (initialMusicPath && initialMusicPath.startsWith('uploads/credits/music')) ? 'custom' : 'default',
            selectedDefaultMusic: (initialMusicPath && initialMusicPath.startsWith('music/default')) ? initialMusicPath : '',
            previewAudio: null,
            nowPlaying: null,

            init() {
                this.credits = (initialCredits && initialCredits.length > 0 ? initialCredits : [{ id: null, role: '', names: [''], logos: [] }])
                    .map((c, i) => ({
                        ...c,
                        uniqueKey: c.id || Date.now() + i,
                        names: c.names && c.names.length > 0 ? c.names : [''],
                        namesJson: JSON.stringify(c.names && c.names.length > 0 ? c.names : [''], null, 4),
                        logos: (c.logos || []).map(l => ({ type: l.startsWith('http') ? 'url' : 'file', path: l }))
                    }));
            },
            
            // --- FUNGSI SINKRONISASI JSON ---
            switchToRaw(creditIndex) {
                this.credits[creditIndex].namesJson = JSON.stringify(this.credits[creditIndex].names, null, 4);
            },
            switchToVisual(creditIndex) {
                this.updateNamesFromJson(creditIndex);
            },
            updateNamesFromJson(creditIndex) {
                try {
                    const parsed = JSON.parse(this.credits[creditIndex].namesJson);
                    if (Array.isArray(parsed)) {
                        this.credits[creditIndex].names = parsed.map(String);
                    }
                } catch (e) {
                    console.error("Invalid JSON format:", e);
                }
            },
            // FUNGSI BARU: Rapikan JSON
            formatJson(creditIndex) {
                try {
                    const parsed = JSON.parse(this.credits[creditIndex].namesJson);
                    this.credits[creditIndex].namesJson = JSON.stringify(parsed, null, 4); // 4 spasi untuk indentasi
                } catch (e) {
                    console.error("Cannot format invalid JSON:", e);
                    // Opsional: berikan feedback visual, misal shake the textarea
                }
            },

            // --- FUNGSI PRATINJAU MUSIK ---
            togglePreview(path) {
                if (!path) return;
                const audioUrl = `{{ url('default-music-file') }}/${path}`;

                if (this.nowPlaying === path) {
                    if (this.previewAudio && !this.previewAudio.paused) {
                        this.previewAudio.pause();
                        this.nowPlaying = null;
                    } else if (this.previewAudio) {
                        this.previewAudio.play();
                        this.nowPlaying = path;
                    }
                    return;
                }

                if (this.previewAudio) {
                    this.previewAudio.pause();
                    this.previewAudio.currentTime = 0;
                }

                this.previewAudio = new Audio(audioUrl);
                this.previewAudio.play();
                this.nowPlaying = path;

                this.previewAudio.onended = () => { this.nowPlaying = null; };
            },

            // --- FUNGSI DASAR MANAJEMEN ---
            addCredit() {
                this.credits.push({ 
                    id: null, role: '', names: [''], logos: [], 
                    uniqueKey: Date.now(),
                    namesJson: JSON.stringify([''], null, 4)
                });
            },
            removeCredit(index) {
                if (this.credits.length > 1) {
                    this.credits.splice(index, 1);
                }
            },
            addName(creditIndex) {
                this.credits[creditIndex].names.push('');
            },
            removeName(creditIndex, nameIndex) {
                if (this.credits[creditIndex].names.length > 1) {
                    this.credits[creditIndex].names.splice(nameIndex, 1);
                }
            },
            addLogo(creditIndex) {
                this.credits[creditIndex].logos.push({ type: 'file', path: '' });
            },
            removeLogo(creditIndex, logoIndex) {
                this.credits[creditIndex].logos.splice(logoIndex, 1);
            }
        }
    }
</script>