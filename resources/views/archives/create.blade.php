<x-app-layout title="Add New Archive">
    <div class="max-w-3xl mx-auto">

        {{-- Header Halaman --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-lg sm:text-2xl font-bold text-primary">[ ADD_NEW_ENTRY ]</h1>
            <a href="{{ route('archives.index') }}" 
               class="text-sm text-secondary hover:text-primary transition-colors duration-200">
                &lt;-- Back to Vault
            </a>
        </div>

        {{-- Kontainer Form Utama --}}
        <div class="bg-surface border border-border rounded-md shadow-lg">
            <form action="{{ route('archives.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="p-6 space-y-6">
                    {{-- Input Nama --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-secondary">> Entry Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               class="mt-1 block w-full bg-base border-border rounded-md shadow-sm focus:ring-primary focus:border-primary text-secondary"
                               required>
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Input Deskripsi --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-secondary">> Description (Optional)</label>
                        <textarea name="description" id="description" rows="4"
                                  class="mt-1 block w-full bg-base border-border rounded-md shadow-sm focus:ring-primary focus:border-primary text-secondary">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Input Visibilitas (Desain Baru) --}}
                    <div class="bg-gray-900 border border-border rounded-md p-4 flex items-center justify-between">
                        <div>
                            <label for="is_public" class="font-medium text-primary">Public Access</label>
                            <p class="text-xs text-secondary mt-1">Allow other agents to see this archive.</p>
                        </div>
                        <label for="is_public" class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_public" id="is_public" value="1" class="sr-only peer" @checked(old('is_public'))>
                            <div class="w-11 h-6 bg-gray-800 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary peer-focus:ring-offset-2 peer-focus:ring-offset-surface rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-800"></div>
                        </label>
                    </div>

                    {{-- Alpine.js untuk Pilihan Tipe Dinamis --}}
                    <div x-data="{ type: '{{ old('type', 'file') }}' }" class="space-y-4">
                        {{-- Pilihan Tipe (Desain Kartu Baru) --}}
                        <fieldset>
                            <legend class="block text-sm font-medium text-secondary mb-2">> Entry Type</legend>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Pilihan File Upload --}}
                                <label class="relative flex p-4 border rounded-md cursor-pointer transition-colors duration-200"
                                       :class="type === 'file' ? 'border-primary bg-primary/10' : 'border-border hover:border-primary/50'">
                                    <input type="radio" name="type" value="file" x-model="type" class="sr-only">
                                    <div class="flex items-center gap-4">
                                        <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        <div>
                                            <span class="font-medium text-primary">File Upload</span>
                                            <p class="text-xs text-secondary">PDF, DOCX, XLSX, CSV, ZIP, etc.</p>
                                        </div>
                                    </div>
                                </label>
                                {{-- Pilihan URL --}}
                                <label class="relative flex p-4 border rounded-md cursor-pointer transition-colors duration-200"
                                       :class="type === 'url' ? 'border-primary bg-primary/10' : 'border-border hover:border-primary/50'">
                                    <input type="radio" name="type" value="url" x-model="type" class="sr-only">
                                    <div class="flex items-center gap-4">
                                        <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                        <div>
                                            <span class="font-medium text-primary">URL Link(s)</span>
                                            <p class="text-xs text-secondary">Single or multi-part links.</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </fieldset>

                        {{-- Input untuk File (muncul jika type = 'file') --}}
                        <div x-show="type === 'file'" x-transition>
                            <label for="archive_file" class="block text-sm font-medium text-secondary">> Select File</label>
                            <div class="mt-1 p-4 w-full bg-base border-2 border-dashed border-border rounded-md flex justify-center items-center">
                                <input type="file" name="archive_file" id="archive_file" class="block w-full text-sm text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/20 file:text-primary hover:file:bg-primary/30 cursor-pointer">
                            </div>
                            @error('archive_file') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Input untuk URL (muncul jika type = 'url') --}}
                        <div x-show="type === 'url'" x-transition>
                            <label for="links" class="block text-sm font-medium text-secondary">> URL(s)</label>
                            <textarea name="links" id="links" rows="7" class="mt-1 block w-full bg-base border-border rounded-md shadow-sm focus:ring-primary focus:border-primary text-secondary" placeholder="Satu link per baris untuk tautan multi-bagian...">{{ old('links') }}</textarea>
                            <p class="mt-1 text-xs text-secondary">For multi-part links, place each link on a new line.</p>
                            @error('links') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Tombol Submit di Footer --}}
                <div class="bg-surface-light border-t border-border px-6 py-4 text-right rounded-b-md">
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-base bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary focus:ring-offset-surface-light cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        SAVE_ENTRY
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>