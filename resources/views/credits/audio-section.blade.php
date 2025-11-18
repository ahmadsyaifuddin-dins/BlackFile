<div class="mt-8 pt-6 border-t-2 border-border-color" x-data="{ 
    musicSource: '{{ (isset($musicPath) && Str::startsWith($musicPath, 'uploads/credits/music')) ? 'custom' : 'default' }}',
    selectedDefaultMusic: '{{ (isset($musicPath) && Str::startsWith($musicPath, 'music/default')) ? $musicPath : '' }}',
    previewAudio: null,
    nowPlaying: null,
    togglePreview(path) {
        if (!path) return;
        const audioUrl = `{{ asset('') }}${path}`;

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
                            class="form-control cursor-pointer">
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