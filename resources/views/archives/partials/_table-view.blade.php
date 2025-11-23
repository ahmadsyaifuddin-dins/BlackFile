{{-- DESKTOP VIEW (TABLE) --}}
<div class="hidden md:block">
    {{-- Wrapper dengan border tipis dan shadow --}}
    <div class="bg-surface/50 border border-green-500/30 rounded-sm overflow-hidden shadow-lg backdrop-blur-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-green-500/20">
                {{-- Header dengan gaya Terminal --}}
                <thead class="bg-black/40 text-primary">
                    <tr class="font-mono text-xs tracking-widest">
                        <th scope="col" class="px-6 py-4 text-left font-bold uppercase border-b border-green-500/30">
                            > FILE_NAME & TAGS
                        </th>
                        <th scope="col" class="px-6 py-4 text-left font-bold uppercase border-b border-green-500/30">
                            OWNER
                        </th>
                        <th scope="col" class="px-6 py-4 text-left font-bold uppercase border-b border-green-500/30">
                            STATUS
                        </th>
                        <th scope="col" class="px-6 py-4 text-left font-bold uppercase border-b border-green-500/30">
                            CAT / TYPE
                        </th>
                        <th scope="col" class="px-6 py-4 text-left font-bold uppercase border-b border-green-500/30">
                            DATE_LOGGED
                        </th>
                        <th scope="col" class="relative px-6 py-4 border-b border-green-500/30">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-green-500/10 bg-transparent">
                    @forelse ($archives as $archive)
                        {{-- Efek Hover pada baris --}}
                        <tr class="group hover:bg-green-500/5 transition-colors duration-200">

                            {{-- Kolom Nama --}}
                            <td class="px-6 py-4 align-top">
                                <div class="text-sm font-bold text-primary group-hover:text-glow transition-all">
                                    {{ $archive->name }}
                                </div>
                                @if ($archive->tags->isNotEmpty())
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        @foreach ($archive->tags as $tag)
                                            <span
                                                class="px-1.5 py-0.5 text-[10px] uppercase font-mono rounded bg-green-500/10 border border-green-500/20 text-green-500/80">
                                                #{{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>

                            {{-- Kolom Owner --}}
                            <td class="px-6 py-4 align-top text-sm text-secondary font-mono">
                                {{ $archive->user->name }}
                            </td>

                            {{-- Kolom Status --}}
                            <td class="px-6 py-4 align-top">
                                @if ($archive->is_public)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-green-900/30 text-green-400 border border-green-500/30">
                                        <span class="w-1.5 h-1.5 mr-1.5 bg-green-400 rounded-full animate-pulse"></span>
                                        PUBLIC
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-red-900/30 text-red-400 border border-red-500/30">
                                        <span class="w-1.5 h-1.5 mr-1.5 bg-red-400 rounded-full"></span>
                                        PRIVATE
                                    </span>
                                @endif
                            </td>

                            {{-- Kolom Kategori & Tipe --}}
                            <td class="px-6 py-4 align-top text-sm text-secondary">
                                <div class="flex flex-col gap-1">
                                    <span>{{ $archive->category === 'Other' ? $archive->category_other : $archive->category }}</span>
                                    <span
                                        class="text-xs font-mono opacity-70 text-primary">[{{ strtoupper($archive->type) }}]</span>
                                </div>
                            </td>

                            {{-- Kolom Tanggal --}}
                            <td class="px-6 py-4 align-top text-sm text-secondary font-mono whitespace-nowrap">
                                {{ $archive->created_at->format('d M Y') }}<br>
                                <span class="text-xs opacity-60">{{ $archive->created_at->format('H:i') }}</span>
                            </td>

                            {{-- Kolom Aksi (Disederhanakan dengan Icon) --}}
                            <td class="px-6 py-4 align-middle text-right text-sm font-medium">
                                <div
                                    class="flex items-center justify-end gap-3 opacity-60 group-hover:opacity-100 transition-opacity">

                                    {{-- Favorite Button --}}
                                    <div x-data="{
                                        isFavorited: {{ $archive->is_favorited ? 'true' : 'false' }},
                                        count: {{ $archive->favorited_by_count }}
                                    }" class="inline-flex items-center gap-1">
                                        <button
                                            class="cursor-pointer focus:outline-none transform hover:scale-110 transition-transform"
                                            @click="
                                            axios.post('{{ route('archives.favorite.toggle', $archive) }}')
                                                .then(response => {
                                                    isFavorited = response.data.is_favorited;
                                                    count = response.data.favorited_by_count;
                                                });
                                        "
                                            title="Toggle Favorite">
                                            <svg class="h-5 w-5 transition-colors duration-200"
                                                :class="isFavorited ? 'text-red-500 fill-current' :
                                                    'text-secondary hover:text-red-400'"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.672l1.318-1.354a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- Details --}}
                                    <a href="{{ route('archives.show', $archive) }}?return_url={{ urlencode(request()->fullUrl()) }}"
                                        class="text-blue-400 hover:text-blue-300 transform hover:scale-110 transition-transform"
                                        title="View Details">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('archives.edit', $archive) }}"
                                        class="text-yellow-500 hover:text-yellow-400 transform hover:scale-110 transition-transform"
                                        title="Edit Entry">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    {{-- Delete --}}
                                    <x-button.delete :action="route('archives.destroy', $archive)" title="TERMINATE?" message="Confirm deletion?"
                                        target="{{ $archive->name }}">
                                        <div class="text-red-500 hover:text-red-400 cursor-pointer transform hover:scale-110 transition-transform"
                                            title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </div>
                                    </x-button.delete>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-secondary/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3 opacity-50"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="font-mono text-sm tracking-wider"> // NO_DATA_ENTRY_FOUND // </span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
