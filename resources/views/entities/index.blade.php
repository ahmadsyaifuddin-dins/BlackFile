<x-app-layout title="Entities Database">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <h2 class="text-xl md:text-2xl text-glow font-bold text-primary">Entities Database // Index</h2>
            <x-button href="{{ route('entities.create') }}">
                > Register New Entity
            </x-button>
        </div>

        @if (session('success'))
            <div class="bg-green-900/50 border border-primary text-primary px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- FORM FILTER DENGAN LAYOUT GRID --}}
        <div class="bg-surface border border-border-color p-4 font-mono">
            <form action="{{ route('entities.index') }}" method="GET">
                {{-- Menjadi 5 kolom di layar besar untuk menampung Status --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

                    {{-- Kolom 1: Search --}}
                    <div class="lg:col-span-1 md:col-span-2">
                        <label for="search" class="sr-only">Search</label>
                        <x-forms.input name="search" placeholder="Search entities..." value="{{ request('search') }}">
                            <x-slot:icon>
                                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </x-slot:icon>
                        </x-forms.input>
                    </div>

                    {{-- Kolom 2: Filter Category (Dari DB) --}}
                    <div>
                        <x-forms.select name="category" :options="$categories" :searchable="true" :selected="request('category')"
                            placeholder="All Categories" />
                    </div>

                    {{-- Kolom 3: Filter Rank (Dari DB) --}}
                    <div>
                        <x-forms.select name="rank" :options="$ranks" :searchable="true" :selected="request('rank')"
                            placeholder="All Ranks" />
                    </div>

                    {{-- Kolom 4: Filter Origin (Dari DB) --}}
                    <div>
                        <x-forms.select name="origin" :options="$origins" :searchable="true" :selected="request('origin')"
                            placeholder="All Origins" />
                    </div>

                    {{-- Kolom 5: Filter Status (BARU - Dari DB) --}}
                    <div>
                        <x-forms.select name="status" :options="$statuses" :searchable="true" :selected="request('status')"
                            placeholder="All Statuses" />
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 mt-4 pt-4 border-t border-border-color/30">

                    {{-- Tombol Reset --}}
                    <x-button variant="outline" href="{{ route('entities.index') }}"
                        class="w-full sm:w-auto justify-center">
                        [ CLEAR FILTERS ]
                    </x-button>

                    {{-- Tombol Submit --}}
                    <x-button type="submit" class="w-full sm:w-auto justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        <span>EXECUTE FILTER</span>
                    </x-button>
                </div>
            </form>
        </div>

        {{-- Grid Kartu Dossier --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($entities as $entity)
                <div x-data="{ inView: false }" x-intersect:enter="inView = true" x-intersect:leave="inView = false"
                    class="entity-card bg-surface border-2 border-border-color hover:border-primary transition-colors duration-300 rounded-none group">
                    <div class="px-4 py-2 border-b-2 border-border-color flex justify-between items-center">
                        <h3
                            class="font-bold text-lg text-primary group-hover:text-white tracking-widest font-mono truncate">
                            {{ $entity->codename ?? $entity->name }}
                        </h3>

                        {{-- Panggil accessor $entity->status_style langsung disini --}}
                        <span class="text-xs font-mono px-2 py-1 border rounded-full {{ $entity->status_style }}">
                            {{ $entity->status }}
                        </span>
                    </div>

                    <div class="p-4 flex flex-col sm:flex-row gap-4">
                        <div class="w-full sm:w-1/3 flex-shrink-0">
                            <a
                                href="{{ route('entities.show', $entity) }}?return_url={{ urlencode(request()->fullUrl()) }}">
                                @php
                                    $thumbnail = $entity->thumbnail;
                                    if (!$thumbnail && $entity->images->isNotEmpty()) {
                                        $thumbnail = $entity->images->first();
                                    }
                                @endphp
                                @if ($thumbnail)
                                    <div class="aspect-square">
                                        {{-- CUKUP PANGGIL ->url --}}
                                        <img src="{{ $thumbnail->url }}" alt="{{ $entity->codename }}"
                                            :class="{ 'grayscale': !inView }"
                                            class="w-full h-full object-cover transition-all duration-500 ease-in-out"
                                            loading="lazy">
                                    </div>
                                @else
                                    {{-- Bagian Else tetap sama --}}
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

                    <div class="px-4 py-2 border-t-2 border-border-color flex items-center justify-end gap-4">

                        <a href="{{ route('entities.assessment', $entity) }}?return_url={{ urlencode(request()->fullUrl()) }}"
                            class="text-yellow-500 hover:text-yellow-600 text-sm font-bold font-mono whitespace-nowrap hidden lg:block">STATS</a>

                        <a href="{{ route('entities.edit', $entity) }}?return_url={{ urlencode(request()->fullUrl()) }}"
                            class="text-secondary hover:text-gray-600 text-sm font-mono whitespace-nowrap">EDIT</a>

                        <x-button.delete :action="route('entities.destroy', $entity)" title="TERMINATE ENTITY?"
                            message="Are you sure you want to terminate this entity record? This action cannot be undone."
                            target="{{ $entity->name }}">
                            TERMINATE
                        </x-button.delete>

                        <a href="{{ route('entities.show', $entity) }}?return_url={{ urlencode(request()->fullUrl()) }}"
                            class="text-primary hover:text-white text-sm font-bold font-mono whitespace-nowrap">ACCESS
                            ENTITY</a>
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
