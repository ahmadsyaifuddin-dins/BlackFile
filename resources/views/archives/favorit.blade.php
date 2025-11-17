<x-app-layout title="Favorite Archives">
    <div class="space-y-6">

        {{-- Header Halaman --}}
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <h1 class="text-xl sm:text-2xl font-bold text-primary">[ FAVORITE_ARCHIVES ]</h1>
            <div>
                {{-- Menggunakan component x-button untuk konsistensi UI --}}
                <x-button variant="outline" href="{{ route('archives.index') }}">
                    &lt;-- Back to Vault
                </x-button>
            </div>
        </div>

        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="px-4 py-3 border rounded-md bg-surface-light border-primary text-primary font-mono text-sm">
                <span class="font-bold">> Status:</span> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="px-4 py-3 border rounded-md bg-surface-light border-red-500 text-red-500 font-mono text-sm">
                <span class="font-bold">> Error:</span> {{ session('error') }}
            </div>
        @endif

        @include('archives.partials._filter-form', [
            'searchRoute' => route('favorites.archives'),
            'categories' => $categories, 
            // 'owners' => $owners // Sengaja tidak dikirim karena favorites tidak butuh filter owner (opsional)
        ])

        {{-- Stats Info --}}
        <div class="bg-surface border border-border-color rounded-md p-4">
            <div class="flex items-center gap-4">
                <svg class="h-6 w-6 text-red-500 fill-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.672l1.318-1.354a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                </svg>
                <span class="text-secondary font-mono">
                    Total Favorites: <span class="font-bold text-primary">{{ $favorites->total() }}</span>
                </span>
            </div>
        </div>

        {{-- DESKTOP VIEW (TABLE) --}}
        <div class="hidden md:block">
            <div class="bg-surface border border-border-color rounded-md overflow-hidden">
                <div class="table-responsive">
                    <table class="min-w-full divide-y divide-border-color">
                        <thead class="bg-surface-light">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider font-mono">Name & Tags</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider font-mono">Owner</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider font-mono">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider font-mono">Category</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider font-mono">Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider font-mono">Favorited Date</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y bg-surface divide-border-color">
                            @forelse ($favorites as $archive)
                            <tr class="hover:bg-surface-light transition-colors">
                                <td class="px-6 py-4 align-top text-sm text-primary font-semibold font-mono">
                                    <div>{{ $archive->name }}</div>
                                    @if($archive->tags->isNotEmpty())
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        @foreach($archive->tags as $tag)
                                        <span class="px-2 text-xs rounded-full bg-base border border-border-color text-secondary whitespace-nowrap">
                                            {{ $tag->name }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-top text-sm text-secondary whitespace-nowrap font-mono">{{ $archive->user->name }}</td>
                                <td class="px-6 py-4 align-top text-sm font-mono">
                                    @if($archive->is_public)
                                    <span class="text-green-400">[PUBLIC]</span>
                                    @else
                                    <span class="text-red-400">[PRIVATE]</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-top text-sm text-secondary whitespace-nowrap font-mono">
                                    {{ $archive->category === 'Other' ? $archive->category_other : $archive->category }}
                                </td>
                                <td class="px-6 py-4 align-top text-sm text-secondary whitespace-nowrap font-mono">{{ $archive->type }}</td>
                                <td class="px-6 py-4 align-top text-sm text-secondary whitespace-nowrap font-mono">
                                    {{ $archive->pivot->created_at->translatedFormat('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 align-top whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-4">
                                        {{-- Logic Unfavorite dengan AlpineJS --}}
                                        <div x-data="{ isFavorited: true }" class="inline-flex items-center gap-1">
                                            <button class="cursor-pointer focus:outline-none" 
                                                title="Unfavorite"
                                                @click="
                                                    if(!confirm('Remove from favorites?')) return;
                                                    axios.post('{{ route('archives.favorite.toggle', $archive) }}')
                                                        .then(response => {
                                                            if (!response.data.is_favorited) {
                                                                $el.closest('tr').remove();
                                                                // Optional: Reload page if list empty logic needed
                                                            }
                                                        });
                                                ">
                                                <svg class="h-5 w-5 text-red-500 fill-current hover:text-red-400 transition-colors" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.672l1.318-1.354a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <a href="{{ route('archives.show', $archive) }}" class="text-secondary hover:text-primary font-mono">VIEW</a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-secondary border-dashed border-border-color">
                                    <div class="flex flex-col items-center gap-3">
                                        <p class="text-lg font-medium font-mono">// NO_FAVORITE_ARCHIVES_FOUND //</p>
                                        <x-button href="{{ route('archives.index') }}" size="sm">
                                            Browse Archives
                                        </x-button>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- MOBILE VIEW (CARDS) --}}
        <div class="md:hidden space-y-4">
            @forelse ($favorites as $archive)
            <div class="bg-surface border border-border-color rounded-md p-4 flex flex-col h-full font-mono">
                <div class="flex justify-between items-start mb-2">
                    <h2 class="font-bold text-primary break-all pr-2 text-lg">{{ $archive->name }}</h2>
                    <div class="flex-shrink-0">
                        @if($archive->is_public)
                            <span class="text-green-400 text-xs font-bold border border-green-900 bg-green-900/20 px-1 rounded">PUB</span>
                        @else
                            <span class="text-red-400 text-xs font-bold border border-red-900 bg-red-900/20 px-1 rounded">PVT</span>
                        @endif
                    </div>
                </div>

                <div class="text-sm text-secondary space-y-1 flex-grow">
                    <p>> Owner: {{ $archive->user->name }}</p>
                    <p>> Type: {{ $archive->type }}</p>
                    <p>> Cat: {{ $archive->category === 'Other' ? $archive->category_other : $archive->category }}</p>
                    <p>> Added: {{ $archive->pivot->created_at->format('d M Y') }}</p>
                </div>

                <div class="mt-4 pt-3 border-t border-border-color flex items-center justify-between">
                     {{-- Unfavorite Button Mobile --}}
                     <button class="text-red-500 hover:text-red-400 flex items-center gap-1 text-xs font-bold cursor-pointer"
                        onclick="if(confirm('Remove from favorites?')) { 
                            axios.post('{{ route('archives.favorite.toggle', $archive) }}').then(() => this.closest('.bg-surface').remove()) 
                        }">
                        <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.672l1.318-1.354a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" /></svg>
                        UNFAV
                    </button>

                    <div class="flex gap-3">
                        <a href="{{ route('archives.show', $archive) }}" class="text-primary hover:text-white text-sm font-bold">[ OPEN ]</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-surface border border-dashed border-border-color rounded-md p-8 text-center">
                <p class="text-secondary font-mono mb-4">// EMPTY //</p>
                <x-button href="{{ route('archives.index') }}" >Browse</x-button>
            </div>
            @endforelse
        </div>

        {{-- Paginasi --}}
        @if ($favorites->hasPages())
        <div class="p-4 bg-surface border border-border-color rounded-md">
            {{ $favorites->links() }}
        </div>
        @endif
    </div>
</x-app-layout>