<div x-data="creditsForm({ 
    initialCredits: {{ $credits ?? '[]' }}, 
    initialMusicPath: '{{ $musicPath ?? '' }}' 
})" class="font-mono text-sm">

{{-- Error Handling --}}
@if($errors->any())
<div class="mb-6 bg-red-900/20 border-l-4 border-red-500 text-red-400 p-4" role="alert">
    <p class="font-bold flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        > SYSTEM_ERROR: Data Input Anomaly
    </p>
    <ul class="mt-2 list-disc list-inside text-xs opacity-80">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="space-y-6">
    <template x-for="(credit, creditIndex) in credits" :key="credit.uniqueKey">
        {{-- Card Container --}}
        <div class="relative p-4 border border-border-color bg-surface rounded-sm group hover:border-primary transition-colors duration-300">
            
            {{-- Hidden ID --}}
            <input type="hidden" :name="'credits[' + creditIndex + '][id]'" x-model="credit.id">
            
            {{-- Hapus Section Button --}}
            <button @click="removeCredit(creditIndex)" type="button"
                class="absolute top-0 right-0 p-2 text-secondary hover:text-red-500 transition-colors"
                title="Remove Section">
                [X]
            </button>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                {{-- KOLOM KIRI: ROLE & LOGOS --}}
                <div class="lg:col-span-4 space-y-4 border-r border-border-color/30 pr-0 lg:pr-6">
                    
                    {{-- Input Role --}}
                    <div>
                        <label class="block text-primary mb-1">> SECTION TITLE / ROLE</label>
                        <input type="text" :name="'credits[' + creditIndex + '][role]'" x-model="credit.role" required
                            placeholder="e.g., Lead Analysts"
                            class="form-control w-full"> {{-- Pakai class form-control dari app.css --}}
                    </div>

                    {{-- Logos --}}
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <label class="block text-secondary text-xs">> SECTION LOGOS</label>
                            <button @click="addLogo(creditIndex)" type="button" class="text-xs text-primary hover:text-white hover:underline">
                                [+ ADD LOGO]
                            </button>
                        </div>
                        
                        <div class="space-y-3">
                            <template x-for="(logo, logoIndex) in credit.logos" :key="logoIndex">
                                <div class="p-2 border border-dashed border-border-color bg-base/50 rounded relative">
                                    <div class="flex gap-2 mb-2">
                                        <select :name="'credits[' + creditIndex + '][logos][' + logoIndex + '][type]'"
                                            x-model="logo.type" 
                                            class="bg-base border border-border-color text-xs text-secondary p-1 focus:border-primary focus:outline-none">
                                            <option value="file">File</option>
                                            <option value="url">URL</option>
                                        </select>
                                        
                                        {{-- Remove Logo --}}
                                        <button @click="removeLogo(creditIndex, logoIndex)" type="button"
                                            class="ml-auto text-red-500 hover:text-red-400 text-xs">
                                            [RMV]
                                        </button>
                                    </div>

                                    {{-- Input URL --}}
                                    <template x-if="logo.type === 'url'">
                                        <input type="url"
                                            :name="'credits[' + creditIndex + '][logos][' + logoIndex + '][path]'"
                                            x-model="logo.path"
                                            placeholder="https://..."
                                            class="w-full bg-transparent border-b border-border-color text-xs text-white focus:border-primary focus:outline-none placeholder-secondary/30">
                                    </template>

                                    {{-- Input File --}}
                                    <template x-if="logo.type === 'file'">
                                        <div>
                                            <input type="hidden"
                                                :name="'credits[' + creditIndex + '][logos][' + logoIndex + '][path]'"
                                                :value="logo.path">
                                            
                                            {{-- Preview Gambar Kecil --}}
                                            <div x-show="logo.path" class="mb-2 flex items-center gap-2 bg-black p-1 border border-border-color">
                                                <img :src="'/' + logo.path" class="h-6 w-6 object-contain">
                                                <span x-text="logo.path.split('/').pop()" class="text-[10px] text-gray-500 truncate w-20"></span>
                                            </div>
                                            
                                            <input type="file"
                                                :name="'credits[' + creditIndex + '][logos][' + logoIndex + '][file]'"
                                                class="block w-full text-[10px] text-secondary
                                                file:mr-2 file:py-1 file:px-2
                                                file:border-0 file:text-[10px]
                                                file:bg-primary file:text-black hover:file:bg-green-400 cursor-pointer">
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: NAMES LIST --}}
                <div class="lg:col-span-8" x-data="{ editMode: 'visual' }">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-primary">> NAMES LIST</label>
                        
                        {{-- Toggle Mode --}}
                        <div class="flex text-[10px] border border-border-color">
                            <button @click.prevent="editMode = 'visual'; switchToVisual(creditIndex)" 
                                :class="editMode === 'visual' ? 'bg-primary text-black font-bold' : 'bg-base text-secondary hover:text-white'" 
                                class="px-2 py-1 transition-colors">VISUAL</button>
                            <button @click.prevent="editMode = 'raw'; switchToRaw(creditIndex)" 
                                :class="editMode === 'raw' ? 'bg-primary text-black font-bold' : 'bg-base text-secondary hover:text-white'" 
                                class="px-2 py-1 transition-colors">RAW JSON</button>
                        </div>
                    </div>

                    {{-- Mode Visual --}}
                    <div x-show="editMode === 'visual'" class="space-y-2">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <template x-for="(name, nameIndex) in credit.names" :key="`${creditIndex}-${nameIndex}`">
                                <div class="flex items-center gap-1 group/item">
                                    <span class="text-secondary select-none">></span>
                                    <input type="text" 
                                        :name="'credits[' + creditIndex + '][names][' + nameIndex + ']'" 
                                        x-model="credit.names[nameIndex]" 
                                        placeholder="Operative Name" 
                                        class="flex-grow form-underline">
                                    <button @click.prevent="removeName(creditIndex, nameIndex)" type="button" 
                                        class="text-red-500 opacity-0 group-hover/item:opacity-100 transition-opacity text-xs px-1">
                                        [x]
                                    </button>
                                </div>
                            </template>
                        </div>
                        <button @click.prevent="addName(creditIndex)" type="button" 
                            class="cursor-pointer mt-3 text-xs text-secondary hover:text-primary border border-dashed border-border-color hover:border-primary w-full py-1 text-center transition-colors">
                            + APPEND NAME ENTRY
                        </button>
                    </div>

                    {{-- Mode Raw JSON --}}
                    <div x-show="editMode === 'raw'" style="display: none;">
                        <textarea x-model="credit.namesJson" 
                            @input.debounce.500ms="updateNamesFromJson(creditIndex)"
                            class="w-full h-48 bg-black border border-border-color text-green-500 font-mono text-xs p-2 focus:border-primary focus:ring-0"
                            placeholder='["Name 1", "Name 2"]'></textarea>
                        <div class="flex justify-between items-center mt-1">
                            <p class="text-[10px] text-secondary">// Edit array structure directly.</p>
                            <button @click.prevent="formatJson(creditIndex)" type="button" class="text-[10px] text-primary hover:underline">[FORMAT]</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<div class="mt-4">
    <button @click="addCredit" type="button"
        class="cursor-pointer w-full py-3 border-2 border-dashed border-border-color text-secondary hover:text-primary hover:border-primary transition-all uppercase tracking-widest text-sm font-bold">
        [ + INITIALIZE NEW CREDIT SECTION ]
    </button>
</div>

<div class="mt-8 pt-6 border-t-2 border-border-color" x-data="{ 
    musicSource: '{{ (isset($musicPath) && Str::startsWith($musicPath, 'uploads/credits/music')) ? 'custom' : 'default' }}',
    selectedDefaultMusic: '{{ (isset($musicPath) && Str::startsWith($musicPath, 'music/default')) ? $musicPath : '' }}',
    previewAudio: null,
    nowPlaying: null,
    togglePreview(path) {
        if (!path) return;
        const audioUrl = `{{ asset('') }}${path}`; // Perbaikan path asset

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
    }
}">
    <h3 class="text-lg font-bold text-primary mb-4">> AUDIO CONFIGURATION</h3>

    <div class="bg-surface border border-border-color p-4">
        <div class="flex flex-col md:flex-row gap-6">
            
            {{-- Opsi Sumber --}}
            <div class="w-full md:w-1/3 space-y-2">
                <label class="block text-secondary text-xs mb-2">SOURCE TYPE:</label>
                <label class="flex items-center cursor-pointer group">
                    <x-forms.radio name="music_source" value="default" x-model="musicSource" class="form-radio text-primary focus:ring-primary bg-base border-border-color">
                    <span class="ml-2 text-white group-hover:text-primary transition-colors">System Library</span>
                    </x-forms.radio>
                </label>
                <label class="flex items-center cursor-pointer group">
                    <x-forms.radio name="music_source" value="custom" x-model="musicSource" class="form-radio text-primary focus:ring-primary bg-base border-border-color">
                    <span class="ml-2 text-white group-hover:text-primary transition-colors">Custom Upload</span>
                    </x-forms.radio>
                </label>
            </div>

            <div class="w-full md:w-2/3 pl-0 md:pl-6 md:border-l border-border-color/30">
                
                {{-- Dropdown Library --}}
                <div x-show="musicSource === 'default'" x-transition>
                    <label for="default_music_path" class="block text-sm font-medium text-primary mb-2">> SELECT TRACK</label>
                    <div class="flex items-center gap-2">
                        <select name="default_music_path" id="default_music_path" x-model="selectedDefaultMusic"
                            class="form-control cursor-pointer"> {{-- Pakai class form-control --}}
                            <option value="">[ NO AUDIO ]</option>
                            @foreach($defaultMusics as $music)
                            <option value="{{ $music->path }}">{{ $music->name }}</option>
                            @endforeach
                        </select>
                        
                        {{-- Tombol Preview --}}
                        <button @click.prevent="togglePreview(selectedDefaultMusic)" :disabled="!selectedDefaultMusic"
                            type="button"
                            class="px-3 py-2 border border-primary text-primary hover:bg-primary hover:text-green-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors cursor-pointer"
                            :title="nowPlaying === selectedDefaultMusic ? 'Pause' : 'Preview'">
                            <svg x-show="nowPlaying !== selectedDefaultMusic" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <svg x-show="nowPlaying === selectedDefaultMusic" style="display: none;" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </button>
                    </div>
                </div>

                {{-- Upload Custom --}}
                <div x-show="musicSource === 'custom'" x-transition style="display: none;">
                    <label for="music" class="block text-sm font-medium text-primary mb-2">> UPLOAD FILE</label>
                    <input type="file" name="music" id="music" accept="audio/mp3,audio/wav,audio/mpeg"
                        class="block w-full text-sm text-secondary
                        file:mr-4 file:py-2 file:px-4
                        file:border border-primary file:bg-black file:text-primary
                        hover:file:bg-primary hover:file:text-green-800 cursor-pointer">
                    <p class="text-xs text-secondary mt-2">Supported: MP3, WAV.</p>

                    @if(isset($musicPath) && Str::startsWith($musicPath, 'uploads/credits/music'))
                    <div class="mt-4 p-3 border border-border-color bg-base">
                        <p class="text-xs text-primary mb-2">CURRENT FILE:</p>
                        <audio controls class="w-full h-8">
                            <source src="{{ url($musicPath) }}" type="audio/mpeg">
                        </audio>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SAVE BUTTON --}}
<div class="flex items-center justify-end mt-8 pt-6 border-t border-border-color">
    <x-button type="submit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
        <span>SAVE SEQUENCE</span>
    </x-button>
</div>
</div>

{{-- SCRIPT --}}
<script>
function creditsForm({ initialCredits, initialMusicPath }) {
    return {
        credits: [],
        
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
        formatJson(creditIndex) {
            try {
                const parsed = JSON.parse(this.credits[creditIndex].namesJson);
                this.credits[creditIndex].namesJson = JSON.stringify(parsed, null, 4);
            } catch (e) {}
        },

        // --- FUNGSI MANAJEMEN ---
        addCredit() {
            this.credits.push({ 
                id: null, role: '', names: [''], logos: [], 
                uniqueKey: Date.now(),
                namesJson: JSON.stringify([''], null, 4)
            });
        },
        removeCredit(index) {
            if (confirm('Delete this entire section?')) {
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