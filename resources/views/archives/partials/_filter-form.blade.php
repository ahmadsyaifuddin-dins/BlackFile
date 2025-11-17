<div class="bg-surface border border-border rounded-md p-4">
    <form action="{{ $searchRoute }}" method="GET">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">

            {{-- Search Input --}}
            <div class="sm:col-span-2 lg:col-span-2">
                <label for="search" class="block text-xs font-medium text-secondary">Search (Name, Desc, Tag)</label>
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
                        <option value="{{ $category }}" @selected(request('category') == $category)>
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
                    <option value="file" @selected(request('type') == 'file')>File</option>
                    <option value="url" @selected(request('type') == 'url')>URL</option>
                </select>
            </div>

            {{-- Filter Owner (jika ada) --}}
            @if(isset($owners))
            <div>
                <label for="owner" class="block text-xs font-medium text-secondary">Owner</label>
                <select name="owner" id="owner"
                    class="mt-1 block w-full text-sm bg-base border-border rounded-md focus:ring-primary focus:border-primary text-secondary">
                    <option value="">All Owners</option>
                    @foreach($owners as $owner)
                        <option value="{{ $owner->id }}" @selected(request('owner') == $owner->id)>
                            {{ $owner->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>
        <div class="flex items-center justify-end gap-4 mt-4">
            <a href="{{ $searchRoute }}" class="text-sm text-secondary hover:text-primary">Reset</a>
            <x-button type="submit" variant="outline">
                Filter
            </x-button>
        </div>
    </form>
</div>