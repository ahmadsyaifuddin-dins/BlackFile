@php
    // fallback ke index
    $backUrl = request('return_url') ?? route('archives.index');
@endphp
<x-app-layout title="Detail Arsip: {{ $archive->name }}">
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Header Halaman --}}
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <h1 class="text-xl sm:text-2xl font-bold text-primary break-words">[ {{ $archive->name }} ]</h1>
            <a href="{{ $backUrl }}"
                class="flex-shrink-0 text-sm text-secondary hover:text-primary transition-colors duration-200">
                &lt;-- Back to Vault
            </a>
        </div>

        {{-- Tampilkan Gambar Pratinjau jika ada --}}
        @if($archive->preview_image_url)
        <div class="w-full overflow-hidden rounded-lg border border-border">
            <img src="{{ $archive->preview_image_url }}" alt="Archive Preview Image" class="w-full h-auto object-cover">
        </div>
        @endif

        {{-- Kontainer Detail Utama --}}
        <div class="bg-surface border border-border rounded-md shadow-lg">
            {{-- Bagian Deskripsi (Full-width) --}}
            <div class="p-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-secondary">Description</h3>
                <p class="mt-2 text-primary whitespace-pre-wrap">{{ $archive->description ?? '// No description
                    provided' }}</p>
            </div>

            {{-- Bagian Spesifikasi (Grid Layout) --}}
            <div class="border-t border-border p-6">
                <dl class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-3">
                    {{-- Owner --}}
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-secondary">Owner</dt>
                        <dd class="mt-1 text-sm text-primary">{{ $archive->user->name }}</dd>
                    </div>

                    {{-- Visibility --}}
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-secondary">Visibility</dt>
                        <dd class="mt-1 text-sm">
                            @if($archive->is_public)
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900/50 text-green-300">Public</span>
                            @else
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-900/50 text-red-300">Private</span>
                            @endif
                        </dd>
                    </div>

                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-secondary">Category</dt>
                        <dd class="mt-1 text-sm text-primary">
                            @if (is_null($archive->category))
                            <span class="text-red-500">Belum Terkategorikan</span>
                            @else
                            {{ $archive->category === 'Other' ? $archive->category_other : $archive->category }}
                            @endif
                        </dd>
                    </div>

                    {{-- Tags --}}
                    <div class="sm:col-span-3">
                        <dt class="text-sm font-medium text-secondary">Tags</dt>
                        <dd class="mt-1 flex flex-wrap gap-2">
                            @forelse($archive->tags as $tag)
                            <span class="px-2 py-0.5 text-xs rounded-full bg-surface-light text-secondary">{{ $tag->name
                                }}</span>
                            @empty
                            <span class="text-sm text-secondary">// No tags</span>
                            @endforelse
                        </dd>
                    </div>

                    {{-- Entry Type --}}
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-secondary">Entry Type</dt>
                        <dd class="mt-1 text-sm text-primary">{{ ucfirst($archive->type) }}</dd>
                    </div>

                    {{-- Detail Spesifik Tipe File --}}
                    @if ($archive->type === 'file')
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-secondary">File Size</dt>
                        <dd class="mt-1 text-sm text-primary">{{ \Illuminate\Support\Number::fileSize($archive->size,
                            precision: 2) }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-secondary">MIME Type</dt>
                        <dd class="mt-1 text-sm text-primary break-all">{{ $archive->mime_type }}</dd>
                    </div>
                    @endif

                    {{-- Tanggal Ditambahkan --}}
                    <div class="sm:col-span-3">
                        <dt class="text-sm font-medium text-secondary">Date Added</dt>
                        <dd class="mt-1 text-sm text-primary">{{ $archive->created_at->translatedFormat('d F Y, H:i:s')
                            }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Bagian Tautan (Hanya untuk tipe URL) --}}
            @if ($archive->type === 'url' && count($archive->links) > 0)
            <div class="border-t border-border p-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-secondary">Links</h3>
                <ul class="mt-2 space-y-3">
                    @foreach ($archive->links as $link)
                    {{-- [PERBAIKAN] Pastikan nilai awal 'tooltip' adalah huruf kecil --}}
                    <li class="flex items-center gap-4" x-data="{ tooltip: 'copy' }">
                        <svg class="h-5 w-5 text-secondary flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                        <a href="{{ $link }}" target="_blank" rel="noopener noreferrer"
                            class="text-primary text-primary-hover break-all text-sm flex-grow">{{ $link }}</a>

                        <button @click="
                            navigator.clipboard.writeText('{{ $link }}'); 
                            tooltip = 'copied'; 
                            setTimeout(() => tooltip = 'copy', 1500)
                        " class="relative flex-shrink-0 w-5 h-5 cursor-pointer" x-cloak>

                            {{-- Ikon Ceklis (ditampilkan saat tooltip === 'copied') --}}
                            <div x-show="tooltip === 'copied'" x-transition.opacity.duration.300ms
                                class="absolute inset-0 flex items-center justify-center">
                                <svg class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>

                            {{-- Ikon Salin (ditampilkan saat tooltip === 'copy') --}}
                            <div x-show="tooltip === 'copy'" x-transition.opacity.duration.300ms
                                class="absolute inset-0 flex items-center justify-center">
                                <svg class="h-5 w-5 text-secondary hover:text-primary" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </button>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            <div x-data="{ 
                    isFavorited: {{ $archive->is_favorited ? 'true' : 'false' }},
                    count: {{ $archive->favorited_by_count }}
                 }" class="w-full sm:w-auto">
                <x-button variant="outline" @click="
                    axios.post('{{ route('archives.favorite.toggle', $archive) }}')
                        .then(response => {
                            isFavorited = response.data.is_favorited;
                            count = response.data.favorited_by_count;
                        });
                "
                >
                    <svg class="h-5 w-5 transition-colors duration-200"
                        :class="isFavorited ? 'text-red-500 fill-current' : 'text-secondary hover:text-red-400'"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.672l1.318-1.354a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                    </svg>
                    <span x-text="isFavorited ? 'Unfavorite' : 'Add to Favorite'"></span>
                    <span x-text="'(' + count + ')'"></span>
                </x-button>
            </div>
            @if ($archive->type === 'file')
            <x-button variant="outline" href="{{ asset('uploads/' . $archive->file_path) }}" target="_blank">
                <svg class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download File
            </x-button>
            @endif
            <x-button variant="outline" href="{{ route('archives.edit', $archive) }}">
                <svg class="h-4 w-4 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Edit Entry
            </x-button>
            <form action="{{ route('archives.destroy', $archive) }}" method="POST" class="w-full sm:w-auto"
                onsubmit="return confirm('Confirm termination of this entry?');">
                @csrf
                @method('DELETE')
                <x-button variant="outline" type="submit">
                    <svg class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete
                </x-button>
            </form>
        </div>
    </div>
</x-app-layout>