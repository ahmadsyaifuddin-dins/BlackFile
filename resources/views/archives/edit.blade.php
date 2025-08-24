<x-app-layout title="Edit Arsip: {{ $archive->name }}">
    <div class="max-w-3xl mx-auto">
        
        {{-- Header Halaman --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-primary">[ EDIT_ENTRY ]</h1>
            <a href="{{ route('archives.index') }}" 
               class="text-sm text-secondary hover:text-primary">
                &lt;-- Back to Vault
            </a>
        </div>

        <div class="bg-surface border border-border rounded-md p-6">
            {{-- PERUBAHAN PENTING PADA FORM --}}
            <form action="{{ route('archives.update', $archive) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT') {{-- Beritahu Laravel ini adalah request UPDATE --}}

                {{-- Input Nama --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-secondary">> Entry Name</label>
                    {{-- Isi value dengan data yang ada --}}
                    <input type="text" name="name" id="name" value="{{ old('name', $archive->name) }}"
                           class="mt-1 block w-full bg-base border-border rounded-md shadow-sm focus:ring-primary focus:border-primary text-secondary"
                           required>
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Input Deskripsi --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-secondary">> Description (Optional)</label>
                     {{-- Isi value dengan data yang ada --}}
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full bg-base border-border rounded-md shadow-sm focus:ring-primary focus:border-primary text-secondary">{{ old('description', $archive->description) }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-secondary">> Visibility</label>
                    <div class="mt-2 flex items-center">
                        <input type="checkbox" name="is_public" id="is_public" value="1"
                               @checked(old('is_public', $archive->is_public))
                               class="h-4 w-4 rounded border-border bg-base text-primary focus:ring-primary">
                        <label for="is_public" class="ml-2 block text-sm text-secondary">
                            Allow other agents to see this archive
                        </label>
                    </div>
                </div>

                {{-- Tampilkan field 'links' hanya jika tipenya 'url' --}}
                @if($archive->type === 'url')
                    <div>
                        <label for="links" class="block text-sm font-medium text-secondary">> URL(s)</label>
                        {{-- Isi value dengan data yang ada (gabungkan array jadi string) --}}
                        <textarea name="links" id="links" rows="4"
                                  class="mt-1 block w-full bg-base border-border rounded-md shadow-sm focus:ring-primary focus:border-primary text-secondary"
                                  placeholder="Satu link per baris untuk tautan multi-bagian...">{{ old('links', implode("\n", $archive->links)) }}</textarea>
                        <p class="mt-1 text-xs text-secondary">For multi-part links, place each link on a new line.</p>
                        @error('links') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                @else
                    {{-- Beri info bahwa file tidak bisa diubah --}}
                    <div>
                        <label class="block text-sm font-medium text-secondary">> File</label>
                        <div class="mt-1 p-3 bg-base border border-border rounded-md text-secondary text-sm">
                            File tidak bisa diganti. Untuk mengganti file, hapus entri ini dan buat yang baru.
                        </div>
                    </div>
                @endif
                
                {{-- Tombol Submit --}}
                <div class="pt-4 text-right">
                    <button type="submit"
                            class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-base bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary focus:ring-offset-base">
                        UPDATE_ENTRY
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>