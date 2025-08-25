<x-app-layout title="Edit Arsip: {{ $archive->name }}">
    <div class="max-w-3xl mx-auto">

        {{-- Header Halaman --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-primary">[ EDIT_ENTRY ]</h1>
            <a href="{{ url()->previous() }}"
                class="text-sm text-secondary hover:text-primary transition-colors duration-200">
                &lt;-- Back to Vault
            </a>
        </div>

        {{-- Kontainer Form Utama --}}
        <div class="bg-surface border border-border rounded-md shadow-lg">
            <form action="{{ route('archives.update', $archive) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    {{-- Input Nama --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-secondary">> Entry Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $archive->name) }}"
                            class="mt-1 block w-full bg-base border-border rounded-md shadow-sm focus:ring-primary focus:border-primary text-secondary"
                            required>
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Input Deskripsi --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-secondary">> Description
                            (Optional)</label>
                        <textarea name="description" id="description" rows="4"
                            class="mt-1 block w-full bg-base border-border rounded-md shadow-sm focus:ring-primary focus:border-primary text-secondary">{{ old('description', $archive->description) }}</textarea>
                        @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- [BARU] Wrapper Alpine.js untuk Kategori --}}
                    <div x-data="{ selectedCategory: '{{ old('category', $archive->category) }}' }" class="space-y-6">
                        {{-- Input Kategori --}}
                        <div>
                            <label for="category" class="block text-sm font-medium text-secondary">> Category</label>
                            <select name="category" id="category" x-model="selectedCategory"
                                class="mt-1 block w-full bg-base border-border rounded-md shadow-sm focus:ring-primary focus:border-primary text-secondary">
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                            @error('category') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Input Kategori Lainnya (Kondisional) --}}
                        <div x-show="selectedCategory === 'Other'" x-transition>
                            <label for="category_other" class="block text-sm font-medium text-secondary">> Specify Other
                                Category</label>
                            <input type="text" name="category_other" id="category_other"
                                value="{{ old('category_other', $archive->category_other) }}"
                                class="mt-1 block w-full bg-base border-border rounded-md shadow-sm focus:ring-primary focus:border-primary text-secondary"
                                placeholder="e.g., File Konfigurasi Server">
                            @error('category_other') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Input Tags --}}
                    <div>
                        <label for="tags" class="block text-sm font-medium text-secondary">> Tags (pisahkan dengan
                            koma)</label>
                        <input type="text" name="tags" id="tags"
                            value="{{ old('tags', $archive->tags->pluck('name')->implode(',')) }}"
                            class="mt-1 block w-full bg-base border-border rounded-md shadow-sm focus:ring-primary focus:border-primary text-secondary"
                            placeholder="contoh: laporan, penting, q4">
                        <template x-if="errors.tags">
                            <p class="mt-1 text-xs text-red-500" x-text="errors.tags[0]"></p>
                        </template>
                    </div>

                    {{-- Input Visibilitas (Desain Baru) --}}
                    <div class="bg-gray-900 border border-border rounded-md p-4 flex items-center justify-between">
                        <div>
                            <label for="is_public" class="font-medium text-primary">Public Access</label>
                            <p class="text-xs text-secondary mt-1">Allow other agents to see this archive.</p>
                        </div>
                        <label for="is_public" class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_public" id="is_public" value="1" class="sr-only peer"
                                @checked(old('is_public', $archive->is_public))>
                            <div
                                class="w-11 h-6 bg-gray-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary peer-focus:ring-offset-2 peer-focus:ring-offset-surface rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-700">
                            </div>
                        </label>
                    </div>

                    {{-- Tampilkan field 'links' atau Info File --}}
                    @if($archive->type === 'url')
                    <div>
                        <label for="links" class="block text-sm font-medium text-secondary">> URL(s)</label>
                        <textarea name="links" id="links" rows="10"
                            class="mt-1 block w-full bg-base border-border rounded-md shadow-sm focus:ring-primary focus:border-primary text-secondary"
                            placeholder="Satu link per baris untuk tautan multi-bagian...">{{ old('links', implode("\n", $archive->links)) }}</textarea>
                        <p class="mt-1 text-xs text-secondary">For multi-part links, place each link on a new line.</p>
                        @error('links') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    @else
                    {{-- Panel Info File (Desain Baru) --}}
                    <div>
                        <label class="block text-sm font-medium text-secondary">> File</label>
                        <div
                            class="mt-1 flex items-center gap-3 p-3 bg-base border border-border rounded-md text-secondary text-sm">
                            <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span>File cannot be changed. To replace, delete this entry and create a new one.</span>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Tombol Submit di Footer --}}
                <div class="bg-surface-light border-t border-border px-6 py-4 text-right rounded-b-md">
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-base bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary focus:ring-offset-surface-light">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        UPDATE_ENTRY
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>