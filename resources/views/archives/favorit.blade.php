<x-app-layout title="Favorite Archives">
    <div class="space-y-6">

        {{-- Header Halaman --}}
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <h1 class="text-xl sm:text-2xl font-bold text-primary">[ FAVORITE_ARCHIVES ]</h1>
            <div class="flex gap-3">
                <x-button variant="outline" href="{{ route('archives.index') }}">
                    &lt;-- Back to Vault
                </x-button>
            </div>
        </div>

        {{-- Notifikasi --}}
        @if(session('success'))
        <div class="px-4 py-3 border rounded-md bg-surface-light border-primary text-primary">
            <span class="font-bold">> Status:</span> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="px-4 py-3 border rounded-md bg-surface-light border-red-500 text-red-500">
            <span class="font-bold">> Status:</span> {{ session('error') }}
        </div>
        @endif

        {{-- Filter & Search Form --}}
        <div class="bg-surface border border-border rounded-md p-4">
            <form action="{{ route('favorites.archives') }}" method="GET">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

                    {{-- Search Input --}}
                    <div class="sm:col-span-2 lg:col-span-2">
                        <label for="search" class="block text-xs font-medium text-secondary">Search Favorites</label>
                        <input type="search" name="search" id="search" value="{{ request('search') }}"
                            class="mt-1 block w-full text-sm bg-base border-border rounded-md focus:ring-primary focus:border-primary text-secondary"
                            placeholder="Search in your favorites...">
                    </div>

                    {{-- Filter Kategori --}}
                    <div>
                        <label for="category" class="block text-xs font-medium text-secondary">Category</label>
                        <select name="category" id="category"
                            class="mt-1 block w-full text-sm bg-base border-border rounded-md focus:ring-primary focus:border-primary text-secondary">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                            <option value="{{ $category }}" @selected(request('category')==$category)>
                                {{ $category }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Tipe --}}
                    <div>
                        <label for="type" class="block text-xs font-medium text-secondary">Type</label>
                        <select name="type" id="type"
                            class="mt-1 block w-full text-sm bg-base border-border rounded-md focus:ring-primary focus:border-primary text-secondary">
                            <option value="">All Types</option>
                            <option value="file" @selected(request('type')=='file' )>File</option>
                            <option value="url" @selected(request('type')=='url' )>URL</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-4 mt-4">
                    <a href="{{ route('favorites.archives') }}"
                        class="text-sm text-secondary hover:text-primary">Reset</a>
                    <x-button variant="outline" type="submit">Filter</x-button>
                </div>
            </form>
        </div>

        {{-- Stats Info --}}
        <div class="bg-surface border border-border rounded-md p-4">
            <div class="flex items-center gap-4">
                <svg class="h-6 w-6 text-red-500 fill-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.672l1.318-1.354a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                </svg>
                <span class="text-secondary">
                    Total Favorites: <span class="font-bold text-primary">{{ $favorites->total() }}</span>
                </span>
            </div>
        </div>

        {{-- DESKTOP VIEW (TABLE) --}}
        <div class="hidden md:block">
            <div class="bg-surface border border-border rounded-md">
                <div class="table-responsive">
                    <table class="min-w-full divide-y divide-border">
                        <thead class="bg-surface-light">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">
                                    Name & Tags</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">
                                    Owner</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">
                                    Status</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">
                                    Category</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">
                                    Type</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">
                                    Favorited Date</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y bg-surface divide-border">
                            @forelse ($favorites as $archive)
                            <tr>
                                <td class="px-6 py-4 align-top text-sm text-primary font-semibold">
                                    <div>{{ $archive->name }}</div>
                                    {{-- Tampilkan Tags di bawah nama --}}
                                    @if($archive->tags->isNotEmpty())
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        @foreach($archive->tags as $tag)
                                        <span
                                            class="px-2 text-xs rounded-full bg-surface-light text-secondary whitespace-nowrap">{{
                                            $tag->name }}</span>
                                        @endforeach
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-top text-sm text-secondary whitespace-nowrap">{{
                                    $archive->user->name }}</td>
                                <td class="px-6 py-4 align-top text-sm">
                                    @if($archive->is_public)
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900/50 text-green-300">Public</span>
                                    @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-900/50 text-red-300">Private</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-top text-sm text-secondary whitespace-nowrap">{{
                                    $archive->category === 'Other' ? $archive->category_other : $archive->category }}
                                </td>
                                <td class="px-6 py-4 align-top text-sm text-secondary whitespace-nowrap">{{
                                    $archive->type }}</td>
                                <td class="px-6 py-4 align-top text-sm text-secondary whitespace-nowrap">{{
                                    $archive->pivot->created_at->translatedFormat('d M Y, H:i') }}</td>
                                <td class="px-6 py-4 align-top whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-4">
                                        <div x-data="{ 
                                                isFavorited: true,
                                                count: {{ $archive->favorited_by_count }}
                                             }" class="inline-flex items-center gap-1">
                                            <button class="cursor-pointer" @click="
                                                axios.post('{{ route('archives.favorite.toggle', $archive) }}')
                                                    .then(response => {
                                                        isFavorited = response.data.is_favorited;
                                                        count = response.data.favorited_by_count;
                                                        if (!isFavorited) {
                                                            $el.closest('tr').remove();
                                                        }
                                                    });
                                            ">
                                                <svg class="h-5 w-5 transition-colors duration-200 text-red-500 fill-current"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.672l1.318-1.354a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                                                </svg>
                                            </button>
                                            <span x-text="count" class="text-sm text-secondary"></span>
                                        </div>
                                        <a href="{{ route('archives.show', $archive) }}"
                                            class="text-secondary text-primary-hover">Details</a>
                                        <a href="{{ route('archives.edit', $archive) }}"
                                            class="text-yellow-500 hover:text-yellow-400">Edit</a>
                                        <form action="{{ route('archives.destroy', $archive) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Confirm termination of this entry?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-500 hover:text-red-400">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-secondary">
                                    <div class="flex flex-col items-center gap-3 py-8">
                                        <svg class="h-12 w-12 text-secondary opacity-50" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.672l1.318-1.354a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                                        </svg>
                                        <div>
                                            <p class="text-lg font-medium">// NO_FAVORITE_ARCHIVES_FOUND //</p>
                                            <p class="mt-1">Start adding archives to your favorites!</p>
                                        </div>
                                        <a href="{{ route('archives.index') }}"
                                            class="mt-2 px-4 py-2 text-sm border rounded-md transition-colors border-primary text-primary hover:bg-primary hover:text-base">
                                            Browse Archives
                                        </a>
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
            <div class="bg-surface border border-border rounded-md p-4 flex flex-col h-full">
                {{-- Baris Header Kartu --}}
                <div class="flex justify-between items-start">
                    <h2 class="font-bold text-primary break-all pr-2">{{ $archive->name }}</h2>
                    <div class="flex-shrink-0">
                        @if($archive->is_public)
                        <span
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900/50 text-green-300">Public</span>
                        @else
                        <span
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-900/50 text-red-300">Private</span>
                        @endif
                    </div>
                </div>

                {{-- Content --}}
                <div class="text-sm text-secondary space-y-1 mt-3 flex-grow">
                    <p><span class="font-semibold">Owner:</span> {{ $archive->user->name }}</p>
                    <p><span class="font-semibold">Type:</span> {{ $archive->type }}</p>
                    <p><span class="font-semibold">Category:</span> {{ $archive->category === 'Other' ?
                        $archive->category_other : $archive->category }}</p>
                    <p><span class="font-semibold">Favorited:</span> {{ $archive->pivot->created_at->translatedFormat('d
                        M Y, H:i') }}</p>
                    <p class="pt-1 break-words"><span class="font-semibold">Desc:</span> {{ $archive->description ??
                        'N/A' }}</p>
                </div>

                {{-- Footer Kartu untuk Tags dan Aksi --}}
                <div class="mt-4 pt-3 border-t border-border">
                    {{-- Bagian Tags --}}
                    @if($archive->tags->isNotEmpty())
                    <div class="flex flex-wrap gap-2 mb-3">
                        @foreach($archive->tags as $tag)
                        <span class="px-2 py-0.5 text-xs rounded-full bg-surface-light text-secondary">{{ $tag->name
                            }}</span>
                        @endforeach
                    </div>
                    @endif

                    {{-- Bagian Aksi --}}
                    <div class="flex items-center justify-between">
                        <div x-data="{ 
                                isFavorited: true,
                                count: {{ $archive->favorited_by_count }}
                             }" class="inline-flex items-center gap-1">
                            <button @click="
                                axios.post('{{ route('archives.favorite.toggle', $archive) }}')
                                    .then(response => {
                                        isFavorited = response.data.is_favorited;
                                        count = response.data.favorited_by_count;
                                        if (!isFavorited) {
                                            $el.closest('.bg-surface').remove();
                                        }
                                    });
                            ">
                                <svg class="h-5 w-5 transition-colors duration-200 text-red-500 fill-current"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.672l1.318-1.354a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                                </svg>
                            </button>
                            <span x-text="count" class="text-sm text-secondary"></span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('archives.show', $archive) }}"
                                class="text-secondary text-primary-hover text-sm">Details</a>
                            <a href="{{ route('archives.edit', $archive) }}"
                                class="text-yellow-500 hover:text-yellow-400 text-sm">Edit</a>
                            <form action="{{ route('archives.destroy', $archive) }}" method="POST" class="inline-block"
                                onsubmit="return confirm('Confirm termination of this entry?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-400 text-sm">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-surface border border-border rounded-md p-8 text-center text-sm text-secondary">
                <div class="flex flex-col items-center gap-4">
                    <svg class="h-16 w-16 text-secondary opacity-50" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.672l1.318-1.354a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                    </svg>
                    <div>
                        <p class="text-lg font-medium">// NO_FAVORITE_ARCHIVES_FOUND //</p>
                        <p class="mt-1">Start adding archives to your favorites!</p>
                    </div>
                    <a href="{{ route('archives.index') }}"
                        class="mt-2 px-4 py-2 text-sm border rounded-md transition-colors border-primary text-primary hover:bg-primary hover:text-base">
                        Browse Archives
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        {{-- Paginasi --}}
        @if ($favorites->hasPages())
        <div class="p-4 bg-surface border-t border-border rounded-md">
            {{ $favorites->links() }}
        </div>
        @endif
    </div>
</x-app-layout>