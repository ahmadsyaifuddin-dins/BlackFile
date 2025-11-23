<div class="bg-surface border border-green-500/30 rounded-md p-4 font-mono">
    <form action="{{ $searchRoute }}" method="GET">

        {{-- Persiapan Data untuk Select Component --}}
        @php
            // 1. Opsi Type (Manual)
            $typeOptions = [
                'file' => 'File',
                'url' => 'URL',
            ];

            // 2. Opsi Owners (Mengubah Collection jadi Array ['id' => 'name'])
            $ownerOptions = [];
            if (isset($owners)) {
                // pluck() sangat berguna untuk ini
                $ownerOptions = $owners->pluck('name', 'id')->toArray();
            }
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">

            {{-- Search Input --}}
            <div class="sm:col-span-2 lg:col-span-2">
                <label for="search" class="block text-base font-medium text-primary mb-1">Search (Name, Desc,
                    Tag)</label>
                <x-forms.input name="search" id="search" placeholder="e.g., laporan, q4, penting..."
                    value="{{ request('search') }}">

                    {{-- Ikon Kaca Pembesar --}}
                    <x-slot:icon>
                        <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </x-slot:icon>
                </x-forms.input>
            </div>

            {{-- Filter Kategori --}}
            <div>
                <x-forms.select label="Category" name="category" :options="$categories" {{-- Asumsi $categories sudah berupa array --}}
                    :selected="request('category')" placeholder="All Categories" :searchable="true" />
            </div>

            {{-- Filter Tipe --}}
            <div>
                <x-forms.select label="Type" name="type" :options="$typeOptions" :selected="request('type')"
                    placeholder="All Types" />
            </div>

            {{-- Filter Owner (Kondisional) --}}
            @if (isset($owners))
                <div>
                    <x-forms.select label="Owner" name="owner" :options="$ownerOptions" :selected="request('owner')"
                        placeholder="All Owners" :searchable="true" />
                </div>
            @endif

        </div>

        {{-- Tombol Aksi (Responsive Stacked) --}}
        <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 mt-4 pt-4 border-t border-green-500/30">
            <x-button variant="outline" href="{{ $searchRoute }}" class="w-full sm:w-auto justify-center">
                [ RESET ]
            </x-button>

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
