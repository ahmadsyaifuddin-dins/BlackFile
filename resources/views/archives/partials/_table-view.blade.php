 {{-- ============ DESKTOP VIEW (TABLE) ==================== --}}
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
                                <span class="px-2 text-xs rounded-full bg-base border border-border-color text-secondary whitespace-nowrap">
                                    {{ $tag->name }}
                                </span>
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
                                <a href="{{ route('archives.show', $archive) }}?return_url={{ urlencode(request()->fullUrl()) }}"
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