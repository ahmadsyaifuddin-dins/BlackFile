<x-app-layout title="Entities Database">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <h2 class="text-2xl font-bold text-primary">Entities Database // Index</h2>
            <a href="{{ route('entities.create') }}"
                class="w-full sm:w-auto text-center bg-primary text-primary font-bold py-2 px-4 rounded hover:bg-primary-hover transition-colors">
                > Register New Entity
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-900/50 border border-primary text-primary px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        {{-- ================================================================ --}}
        {{-- == PERUBAHAN: FORM FILTER DENGAN LAYOUT GRID == --}}
        {{-- ================================================================ --}}
        <div class="bg-surface border border-border-color p-4 font-mono">
            <form action="{{ route('entities.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- Kolom 1: Search --}}
                    <div class="lg:col-span-3">
                        <label for="search" class="sr-only">Search</label>
                        <input type="text" name="search" id="search"
                            placeholder="Search by keyword, name, or codename..." value="{{ request('search') }}"
                            class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white px-3 py-2">
                    </div>

                    {{-- Kolom 2: Filter Category --}}
                    <div>
                        <label for="category" class="sr-only">Category</label>
                        @php $categories = config('blackfile.entity_categories'); @endphp
                        <div x-data="{ open: false, selected: '{{ request('category', '') }}' }"
                            class="relative font-mono">
                            <input type="hidden" name="category" x-bind:value="selected">
                            <button type="button" @click="open = !open"
                                class="relative w-full bg-base border-2 border-border-color text-left text-white p-2 pr-10 focus:outline-none focus:border-primary">
                                <span x-text="selected || '-- All Categories --'"></span>
                                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"><svg
                                        class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 7.03 7.78a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l2.97-2.97a.75.75 0 111.06 1.06l-3.5 3.5a.75.75 0 01-1.06 0l-3.5-3.5a.75.75 0 010-1.06z"
                                            clip-rule="evenodd" />
                                    </svg></span>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                class="absolute z-10 mt-1 w-full max-h-60 overflow-y-auto bg-black border border-border-color shadow-lg"
                                style="display: none;">
                                <a @click="selected = ''; open = false"
                                    class="block px-4 py-2 text-sm cursor-pointer text-primary hover:text-green-400"
                                    :class="{'text-green-800 bg-surface': selected === ''}">-- All Categories --</a>
                                @foreach($categories as $category)
                                <a @click="selected = '{{ addslashes($category) }}'; open = false"
                                    class="block px-4 py-2 text-sm cursor-pointer text-primary hover:text-green-400"
                                    :class="{'text-green-800 bg-surface': selected === '{{ addslashes($category) }}'}">{{
                                    $category }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Kolom 3: Filter Rank --}}
                    <div>
                        <label for="rank" class="sr-only">Rank</label>
                        @php $ranks = config('blackfile.entity_ranks'); @endphp
                        <div x-data="{ open: false, selected: '{{ request('rank', '') }}' }" class="relative font-mono">
                            <input type="hidden" name="rank" x-bind:value="selected">
                            <button type="button" @click="open = !open"
                                class="relative w-full bg-base border-2 border-border-color text-left text-white p-2 pr-10 focus:outline-none focus:border-primary">
                                <span x-text="selected || '-- All Ranks --'"></span>
                                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"><svg
                                        class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 7.03 7.78a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l2.97-2.97a.75.75 0 111.06 1.06l-3.5 3.5a.75.75 0 01-1.06 0l-3.5-3.5a.75.75 0 010-1.06z"
                                            clip-rule="evenodd" />
                                    </svg></span>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                class="absolute z-10 mt-1 w-full max-h-60 overflow-y-auto bg-black border border-border-color shadow-lg"
                                style="display: none;">
                                <a @click="selected = ''; open = false"
                                    class="block px-4 py-2 text-sm cursor-pointer text-primary hover:text-green-400"
                                    :class="{'text-green-800 bg-surface': selected === ''}">-- All Ranks --</a>
                                @foreach($ranks as $rank)
                                <a @click="selected = '{{ addslashes($rank) }}'; open = false"
                                    class="block px-4 py-2 text-sm cursor-pointer text-primary hover:text-green-400"
                                    :class="{'text-green-800 bg-surface': selected === '{{ addslashes($rank) }}'}">{{
                                    $rank }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Kolom 4: Filter Origin --}}
                    <div>
                        <label for="origin" class="sr-only">Origin</label>
                        @php $origins = config('blackfile.entity_origins'); @endphp
                        <div x-data="{ open: false, selected: '{{ request('origin', '') }}' }"
                            class="relative font-mono">
                            <input type="hidden" name="origin" x-bind:value="selected">
                            <button type="button" @click="open = !open"
                                class="relative w-full bg-base border-2 border-border-color text-left text-white p-2 pr-10 focus:outline-none focus:border-primary">
                                <span x-text="selected || '-- All Origins --'"></span>
                                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"><svg
                                        class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 7.03 7.78a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l2.97-2.97a.75.75 0 111.06 1.06l-3.5 3.5a.75.75 0 01-1.06 0l-3.5-3.5a.75.75 0 010-1.06z"
                                            clip-rule="evenodd" />
                                    </svg></span>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                class="absolute z-10 mt-1 w-full max-h-60 overflow-y-auto bg-black border border-border-color shadow-lg"
                                style="display: none;">
                                <a @click="selected = ''; open = false"
                                    class="block px-4 py-2 text-sm cursor-pointer text-primary hover:text-green-400"
                                    :class="{'text-green-800 bg-surface': selected === ''}">-- All Origins --</a>
                                @foreach($origins as $origin)
                                <a @click="selected = '{{ addslashes($origin) }}'; open = false"
                                    class="block px-4 py-2 text-sm cursor-pointer text-primary hover:text-green-400"
                                    :class="{'text-green-800 bg-surface': selected === '{{ addslashes($origin) }}'}">{{
                                    $origin }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Tombol Aksi --}}
                <div class="flex items-center gap-2 mt-4">
                    <button type="submit"
                        class="w-full md:w-auto bg-primary text-primary font-bold py-2 px-6 hover:text-green-400 transition-colors cursor-pointer">
                        FILTER
                    </button>
                    <a href="{{ route('entities.index') }}"
                        class="w-full md:w-auto text-center border border-border-color text-secondary py-2 px-6 hover:bg-surface-light">
                        RESET
                    </a>
                </div>
            </form>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($entities as $entity)
            <div
                class="bg-surface border-2 border-border-color hover:border-primary transition-colors duration-300 rounded-none group">
                <div class="px-4 py-2 border-b-2 border-border-color flex justify-between items-center">
                    <h3
                        class="font-bold text-lg text-primary group-hover:text-white tracking-widest font-mono truncate">
                        {{ $entity->codename ?? $entity->name }}
                    </h3>
                    <span class="text-xs font-mono px-2 py-1 border border-secondary text-secondary rounded-full">
                        {{ $entity->status }}
                    </span>
                </div>

                <div class="p-4 flex flex-col sm:flex-row gap-4">
                    <div class="w-full sm:w-1/3 flex-shrink-0">
                        <a href="{{ route('entities.show', $entity) }}">
                            @if($entity->images->isNotEmpty())
                            @php
                            $thumbnail = $entity->images->first();
                            $imagePath = Illuminate\Support\Str::startsWith($thumbnail->path, 'http')
                            ? $thumbnail->path
                            : asset('uploads/' . $thumbnail->path);
                            @endphp
                           <div class="aspect-square">
                            <img src="{{ $imagePath }}" alt="{{ $entity->codename }}"
                                 class="w-full h-full sm:h-28 object-cover object-center grayscale group-hover:grayscale-0 transition-all duration-300"
                                 loading="lazy">
                          </div>
                            @else
                            <div
                                class="w-full h-32 bg-base flex items-center justify-center border border-dashed border-border-color">
                                <span class="text-secondary font-mono text-sm">[NO VISUALS]</span>
                            </div>
                            @endif
                        </a>
                    </div>

                    <div class="flex-grow">
                        <p class="text-secondary text-sm mb-2 line-clamp-3">
                            {{ Str::limit($entity->description, 100) }}
                        </p>
                        <div class="text-xs font-mono text-gray-400">
                            <p>> Name: {{ $entity->name }}</p>
                            <p>> Category: {{ $entity->category }}</p>
                            <p>> Rank: {{ $entity->rank ?? 'Unclassified' }}</p>
                            <p>> Origin: {{ $entity->origin ?? 'Unknown' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Footer Aksi dengan Tombol Hapus --}}
                <div class="px-4 py-2 border-t-2 border-border-color flex items-center justify-end gap-4">
                    <a href="{{ route('entities.edit', $entity) }}"
                        class="text-secondary hover:text-primary text-sm font-mono">> EDIT</a>

                    <form action="{{ route('entities.destroy', $entity) }}" method="POST"
                        onsubmit="return confirm('WARNING: Are you sure you want to terminate this entity record? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-400 text-sm font-mono">>
                            TERMINATE</button>
                    </form>

                    <a href="{{ route('entities.show', $entity) }}"
                        class="text-primary hover:text-white text-sm font-bold font-mono">> ACCESS ENTITY</a>
                </div>
            </div>
            @empty
            <div class="md:col-span-2 xl:col-span-3 text-center py-12 border-2 border-dashed border-border-color">
                <p class="text-secondary font-mono text-lg">[ NO ENTITY RECORDS FOUND IN DATABASE ]</p>
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $entities->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>