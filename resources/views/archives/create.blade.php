<x-app-layout title="Add New Archive">
    <div class="max-w-3xl mx-auto">

        {{-- Header Halaman --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-primary">[ ADD_NEW_ENTRY ]</h1>
            <a href="{{ route('archives.index') }}" class="text-sm text-secondary hover:text-primary">
                &lt;-- Back to Vault
            </a>
        </div>

        <div class="bg-surface border border-border rounded-md p-6">
            <form action="{{ route('archives.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

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
                    <label for="description" class="block text-sm font-medium text-secondary">> Description
                        (Optional)</label>
                    <textarea name="description" id="description" rows="3"
                        class="mt-1 block w-full bg-base border-border rounded-md shadow-sm focus:ring-primary focus:border-primary text-secondary">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-secondary">> Visibility</label>
                    <div class="mt-2 flex items-center">
                        <input type="checkbox" name="is_public" id="is_public" value="1"
                            class="h-4 w-4 rounded border-border bg-base text-primary focus:ring-primary">
                        <label for="is_public" class="ml-2 block text-sm text-secondary">
                            Allow other agents to see this archive
                        </label>
                    </div>
                </div>

                {{-- Alpine.js untuk Pilihan Tipe Dinamis --}}
                <div x-data="{ type: '{{ old('type', 'file') }}' }" class="space-y-4">
                    {{-- Pilihan Tipe (Radio Button) --}}
                    <fieldset>
                        <legend class="block text-sm font-medium text-secondary">> Entry Type</legend>
                        <div class="mt-2 space-x-6">
                            <label class="inline-flex items-center">
                                <input type="radio" name="type" value="file" x-model="type"
                                    class="text-primary bg-base border-border focus:ring-primary">
                                <span class="ml-2 text-sm text-secondary">File Upload</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="type" value="url" x-model="type"
                                    class="text-primary bg-base border-border focus:ring-primary">
                                <span class="ml-2 text-sm text-secondary">URL Link(s)</span>
                            </label>
                        </div>
                    </fieldset>

                    {{-- Input untuk File, muncul jika type = 'file' --}}
                    <div x-show="type === 'file'" x-transition>
                        <label for="archive_file" class="block text-sm font-medium text-secondary">> Select File</label>
                        <input type="file" name="archive_file" id="archive_file"
                            class="mt-1 block w-full text-sm text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/20 file:text-primary hover:file:bg-primary/30">
                        @error('archive_file') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Input untuk URL, muncul jika type = 'url' --}}
                    <div x-show="type === 'url'" x-transition>
                        <label for="links" class="block text-sm font-medium text-secondary">> URL(s)</label>
                        <textarea name="links" id="links" rows="4"
                            class="mt-1 block w-full bg-base border-border rounded-md shadow-sm focus:ring-primary focus:border-primary text-secondary"
                            placeholder="Satu link per baris untuk tautan multi-bagian...">{{ old('links') }}</textarea>
                        <p class="mt-1 text-xs text-secondary">For multi-part links, place each link on a new line.</p>
                        @error('links') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Tombol Submit --}}
                <div class="pt-4 text-right">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-base text-primary bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary focus:ring-offset-base cursor-pointer">
                        SAVE_ENTRY
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>