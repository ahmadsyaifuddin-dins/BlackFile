@php
    // fallback ke index
    $backUrl = request('return_url') ?? route('archives.index');
@endphp
<x-app-layout title="Detail Arsip: {{ $archive->name }}">
    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Header Navigation --}}
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 border-b border-primary/20 pb-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-primary break-words tracking-tight font-mono">
                    <span class="text-primary/50 mr-2">FILE_ID:</span>{{ $archive->name }}
                </h1>
                <p class="text-xs text-secondary font-mono mt-1">> ACCESSING SECURE ARCHIVE...</p>
            </div>
            <x-button href="{{ $backUrl }}" variant="outline" class="font-mono text-xs">
                [ ESC ] BACK_TO_VAULT
            </x-button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- KOLOM KIRI: Informasi Utama & Preview --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Image Preview --}}
                @if ($archive->preview_image_url)
                    <div class="w-full overflow-hidden rounded border border-primary/30 bg-black relative group">
                        <div
                            class="absolute top-0 left-0 bg-primary/80 text-black text-[10px] font-bold px-2 py-1 font-mono">
                            IMG_PREVIEW</div>
                        <img src="{{ $archive->preview_image_url }}" alt="Preview"
                            class="w-full h-auto object-cover opacity-80 group-hover:opacity-100 transition-opacity duration-500">
                        {{-- Scanline effect overlay --}}
                        <div
                            class="pointer-events-none absolute inset-0 bg-gradient-to-b from-transparent via-primary/5 to-transparent opacity-20 bg-[length:100%_4px]">
                        </div>
                    </div>
                @endif

                {{-- DESCRIPTION SECTION --}}
                <div class="bg-surface/40 border border-primary/30 rounded overflow-hidden relative">
                    <div class="bg-black/30 px-4 py-2 border-b border-primary/20 flex items-center justify-between">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-secondary font-mono">>
                            DESCRIPTION_DATA</h3>
                        <div class="flex gap-1">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        </div>
                    </div>

                    <div class="p-6">
                        {{-- 
                            PERBAIKAN PENTING UNTUK RESPONSIVE:
                            'break-words' : Memaksa kata panjang (seperti URL) untuk wrap ke baris bawah.
                            'whitespace-pre-wrap' : Menjaga format enter/spasi dari input user.
                        --}}
                        <div
                            class="text-primary/90 font-sans leading-relaxed whitespace-pre-wrap break-words text-sm sm:text-base">
                            {{ $archive->description ?? '// No description data provided in this entry.' }}
                        </div>
                    </div>
                </div>

                {{-- Links Section (Only for URL type) --}}
                @if ($archive->type === 'url' && count($archive->links) > 0)
                    <div class="bg-surface/40 border border-primary/30 rounded p-4">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-secondary font-mono mb-3">>
                            LINKED_RESOURCES</h3>
                        <ul class="space-y-2">
                            @foreach ($archive->links as $link)
                                <li class="flex items-start gap-3 bg-black/20 p-2 rounded border border-primary/10"
                                    x-data="{ tooltip: 'copy' }">
                                    <svg class="h-4 w-4 text-secondary mt-1 flex-shrink-0" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>

                                    {{-- break-all digunakan di sini khusus untuk URL agar tidak merusak layout mobile --}}
                                    <a href="{{ $link }}" target="_blank" rel="noopener noreferrer"
                                        class="text-blue-400 hover:text-blue-300 text-sm flex-grow break-all hover:underline font-mono">
                                        {{ $link }}
                                    </a>

                                    <button
                                        @click="
                                navigator.clipboard.writeText('{{ $link }}'); 
                                tooltip = 'copied'; 
                                setTimeout(() => tooltip = 'copy', 1500)
                            "
                                        class="cursor-pointer relative flex-shrink-0 text-secondary hover:text-primary transition-colors"
                                        :title="tooltip">
                                        <svg x-show="tooltip === 'copy'" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        <svg x-show="tooltip === 'copied'" class="h-4 w-4 text-green-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- KOLOM KANAN: Metadata / Sidebar --}}
            <div class="space-y-6">
                <div class="bg-surface border border-primary/30 rounded p-5 shadow-lg">
                    <h3
                        class="text-xs font-bold uppercase tracking-widest text-secondary font-mono mb-4 border-b border-primary/20 pb-2">
                        > METADATA_INFO
                    </h3>

                    <dl class="space-y-4">
                        {{-- Owner --}}
                        <div>
                            <dt class="text-[10px] uppercase text-secondary font-mono">Owner</dt>
                            <dd class="text-sm font-bold text-primary">{{ $archive->user->name }}</dd>
                        </div>

                        {{-- Visibility --}}
                        <div>
                            <dt class="text-[10px] uppercase text-secondary font-mono">Status</dt>
                            <dd class="mt-1">
                                @if ($archive->is_public)
                                    <span
                                        class="px-2 py-0.5 inline-flex text-[10px] uppercase font-bold tracking-wider rounded bg-green-900/50 text-green-400 border border-green-500/30">Public_Access</span>
                                @else
                                    <span
                                        class="px-2 py-0.5 inline-flex text-[10px] uppercase font-bold tracking-wider rounded bg-red-900/50 text-red-400 border border-red-500/30">Restricted</span>
                                @endif
                            </dd>
                        </div>

                        {{-- Category & Type --}}
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <dt class="text-[10px] uppercase text-secondary font-mono">Category</dt>
                                <dd class="text-sm text-primary">
                                    {{ $archive->category === 'Other' ? $archive->category_other : $archive->category }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-[10px] uppercase text-secondary font-mono">Type</dt>
                                <dd class="text-sm text-primary uppercase">{{ $archive->type }}</dd>
                            </div>
                        </div>

                        {{-- File Specifics --}}
                        @if ($archive->type === 'file')
                            <div class="bg-primary/5 p-2 rounded border border-primary/10">
                                <div class="flex justify-between mb-1">
                                    <dt class="text-[10px] uppercase text-secondary font-mono">Size</dt>
                                    <dd class="text-xs font-mono text-primary">
                                        {{ \Illuminate\Support\Number::fileSize($archive->size, precision: 2) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-[10px] uppercase text-secondary font-mono">MIME</dt>
                                    <dd class="text-[10px] font-mono text-secondary break-all">
                                        {{ $archive->mime_type }}</dd>
                                </div>
                            </div>
                        @endif

                        {{-- Date --}}
                        <div>
                            <dt class="text-[10px] uppercase text-secondary font-mono">Date Logged</dt>
                            <dd class="text-sm text-primary font-mono">
                                {{ $archive->created_at->translatedFormat('d F Y, H:i:s') }}</dd>
                        </div>

                        {{-- Tags --}}
                        <div>
                            <dt class="text-[10px] uppercase text-secondary font-mono mb-2">Tags</dt>
                            <dd class="flex flex-wrap gap-2">
                                @forelse($archive->tags as $tag)
                                    <span
                                        class="px-2 py-0.5 text-[10px] uppercase font-mono rounded bg-surface-light border border-secondary/30 text-secondary">
                                        #{{ $tag->name }}
                                    </span>
                                @empty
                                    <span class="text-xs text-secondary italic opacity-50">No tags attached</span>
                                @endforelse
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="flex flex-col gap-3">
                    {{-- Favorite Button --}}
                    <div x-data="{
                        isFavorited: {{ $archive->is_favorited ? 'true' : 'false' }},
                        count: {{ $archive->favorited_by_count }}
                    }">
                        <button
                            @click="
                            axios.post('{{ route('archives.favorite.toggle', $archive) }}')
                                .then(response => {
                                    isFavorited = response.data.is_favorited;
                                    count = response.data.favorited_by_count;
                                });
                        "
                            class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-surface border border-border rounded hover:bg-surface-light transition-colors group">
                            <svg class="h-5 w-5 transition-colors duration-200"
                                :class="isFavorited ? 'text-red-500 fill-current' : 'text-secondary group-hover:text-red-400'"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.672l1.318-1.354a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                            </svg>
                            <span class="text-sm font-medium text-secondary group-hover:text-primary"
                                x-text="isFavorited ? 'MARKED AS FAVORITE' : 'ADD TO FAVORITES'"></span>
                            <span class="text-xs bg-black/30 px-1.5 rounded text-secondary" x-text="count"></span>
                        </button>
                    </div>

                    @if ($archive->type === 'file')
                        <x-button variant="outline" href="{{ asset('uploads/' . $archive->file_path) }}"
                            target="_blank"
                            class="justify-center border-green-500/50 text-green-400 hover:bg-green-900/20">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            DOWNLOAD_FILE
                        </x-button>
                    @endif

                    <div class="grid grid-cols-2 gap-3">
                        <x-button variant="outline" href="{{ route('archives.edit', $archive) }}"
                            class="justify-center border-yellow-500/50 text-yellow-500 hover:bg-yellow-900/20">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            EDIT
                        </x-button>

                        <x-button.delete :action="route('archives.destroy', $archive)" variant="button" :icon="true" title="DELETE?"
                            message="Action cannot be undone." target="{{ $archive->name }}"
                            class="justify-center border-red-500/50 text-red-500 hover:bg-red-900/20">
                            DELETE
                        </x-button.delete>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
