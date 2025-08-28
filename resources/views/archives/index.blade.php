<x-app-layout title="Archives Files">
    <div class="space-y-6">

        {{-- Header Halaman --}}
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <h1 class="text-xl sm:text-2xl font-bold text-primary">[ ARCHIVES_FILES ]</h1>
            <a href="{{ route('archives.create') }}"
                class="px-4 py-2 text-sm border rounded-md transition-colors border-primary text-primary hover:bg-primary hover:text-base">
                + ADD_NEW_ENTRY
            </a>
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

        {{-- ====================================================== --}}
        {{-- ============ [BARU] FILTER & SEARCH FORM ============= --}}
        {{-- ====================================================== --}}
        <div class="bg-surface border border-border rounded-md p-4">
            <form action="{{ route('archives.index') }}" method="GET">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">

                    {{-- Search Input --}}
                    <div class="sm:col-span-2 lg:col-span-2">
                        <label for="search" class="block text-xs font-medium text-secondary">Search (Name, Desc,
                            Tag)</label>
                        <input type="search" name="search" id="search" value="{{ request('search') }}"
                            class="mt-1 block w-full text-sm bg-base border-border rounded-md focus:ring-primary focus:border-primary text-secondary"
                            placeholder="e.g., laporan, q4, penting...">
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

                    {{-- Filter Owner --}}
                    <div>
                        <label for="owner" class="block text-xs font-medium text-secondary">Owner</label>
                        <select name="owner" id="owner"
                            class="mt-1 block w-full text-sm bg-base border-border rounded-md focus:ring-primary focus:border-primary text-secondary">
                            <option value="">All Owners</option>
                            @foreach($owners as $owner)
                            <option value="{{ $owner->id }}" @selected(request('owner')==$owner->id)>
                                {{ $owner->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-4 mt-4">
                    <a href="{{ route('archives.index') }}" class="text-sm text-secondary hover:text-primary">Reset</a>
                    <button type="submit"
                        class="px-4 py-2 text-sm border rounded-md transition-colors border-primary text-primary hover:bg-primary hover:text-base cursor-pointer">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- ====================================================== --}}
        {{-- ============ DESKTOP VIEW (TABLE) ==================== --}}
        {{-- ====================================================== --}}
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
                                    Date Added</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y bg-surface divide-border">
                            @forelse ($archives as $archive)
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
                                    $archive->created_at->translatedFormat('d M Y, H:i') }}</td>
                                <td
                                    class="px-6 py-4 align-top whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-4">
                                        <div x-data="{ 
                                                isFavorited: {{ $archive->is_favorited ? 'true' : 'false' }},
                                                count: {{ $archive->favorited_by_count }}
                                             }" class="inline-flex items-center gap-1">
                                            <button class="cursor-pointer" @click="
                                                axios.post('{{ route('archives.favorite.toggle', $archive) }}')
                                                    .then(response => {
                                                        isFavorited = response.data.is_favorited;
                                                        count = response.data.favorited_by_count;
                                                    });
                                            ">
                                                <svg class="h-5 w-5 transition-colors duration-200"
                                                    :class="isFavorited ? 'text-red-500 fill-current' : 'text-secondary hover:text-red-400'"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
                                            <button type="submit" class="text-red-500 hover:text-red-400">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-secondary">//
                                    NO_DATA_ENTRY_FOUND //</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ====================================================== --}}
        {{-- ============= MOBILE VIEW (CARDS) ==================== --}}
        {{-- ====================================================== --}}
        <div class="md:hidden space-y-4">
            @forelse ($archives as $archive)
            {{-- [PERUBAHAN] Menambahkan flex-col dan h-full untuk struktur yang lebih baik --}}
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

                {{-- [PERUBAHAN] flex-grow akan mendorong footer ke bawah --}}
                <div class="text-sm text-secondary space-y-1 mt-3 flex-grow">
                    <p><span class="font-semibold">Owner:</span> {{ $archive->user->name }}</p>
                    <p><span class="font-semibold">Type:</span> {{ $archive->type }}</p>
                    <p><span class="font-semibold">Category:</span> {{ $archive->category === 'Other' ?
                        $archive->category_other : $archive->category }}</p>
                    <p><span class="font-semibold">Added:</span> {{ $archive->created_at->translatedFormat('d M Y, H:i')
                        }}</p>
                    <p class="pt-1 break-words"><span class="font-semibold">Desc:</span> {{ $archive->description ??
                        'N/A' }}</p>
                </div>

                {{-- [PERUBAHAN] Footer Kartu untuk Tags dan Aksi --}}
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
                                isFavorited: {{ $archive->is_favorited ? 'true' : 'false' }},
                                count: {{ $archive->favorited_by_count }}
                             }" class="inline-flex items-center gap-1">
                            <button class="cursor-pointer" @click="
                                axios.post('{{ route('archives.favorite.toggle', $archive) }}')
                                    .then(response => {
                                        isFavorited = response.data.is_favorited;
                                        count = response.data.favorited_by_count;
                                    });
                            ">
                                <svg class="h-5 w-5 transition-colors duration-200"
                                    :class="isFavorited ? 'text-red-500 fill-current' : 'text-secondary hover:text-red-400'"
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
            <div class="bg-surface border border-border rounded-md p-4 text-center text-sm text-secondary">
                // NO_DATA_ENTRY_FOUND //
            </div>
            @endforelse
        </div>

        {{-- Paginasi --}}
        @if ($archives->hasPages())
        <div class="p-4 bg-surface border-t border-border rounded-md">
            {{ $archives->links() }}
        </div>
        @endif
    </div>
</x-app-layout>