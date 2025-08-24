<x-app-layout title="Archives Vault">
    <div class="space-y-6">

        {{-- Header Halaman --}}
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <h1 class="text-2xl font-bold text-primary">[ ARCHIVES_VAULT ]</h1>
            <a href="{{ route('archives.create') }}"
                class="px-4 py-2 text-sm border rounded-md transition-colors border-primary text-primary hover:bg-primary hover:text-base">
                + ADD_NEW_ENTRY
            </a>
        </div>

        {{-- Notifikasi Sukses --}}
        @if(session('success'))
        <div class="px-4 py-3 border rounded-md bg-surface-light border-primary text-primary">
            <span class="font-bold">> Status:</span> {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="px-4 py-3 border rounded-md bg-surface-light border-red-500 text-red-500">
            <span class="font-bold">> Status:</span> {{ session('error') }}
        </div>
        @endif

        {{-- Kontainer Tabel --}}
        <div class="bg-surface border border-border rounded-md">
            <div class="table-responsive">
                <table class="min-w-full divide-y divide-border">
                    <thead class="bg-surface-light">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">
                                Name</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">
                                Owner</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">
                                Type</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">
                                Description</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">
                                Date Added</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y bg-surface divide-border">
                        @forelse ($archives as $archive)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary font-semibold">{{ $archive->name
                                }}</td>

                            {{-- Tampilkan nama pemilik --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">{{ $archive->user->name }}
                            </td>
                            
                            {{-- Tampilkan Status Public/Private --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($archive->is_public)
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900/50 text-green-300">Public</span>
                                @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-900/50 text-red-300">Private</span>
                                @endif
                            </td>

                            {{-- Tampilkan Tipe --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">{{ $archive->type }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary max-w-xs truncate">{{
                                $archive->description ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">{{
                                $archive->created_at->format('d M Y, H:i') }}</td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                {{-- Link ke Halaman Detail --}}
                                <a href="{{ route('archives.show', $archive) }}"
                                    class="text-secondary text-primary-hover">Details</a>

                                {{-- Link ke Halaman Edit --}}
                                <a href="{{ route('archives.edit', $archive) }}"
                                    class="text-yellow-500 hover:text-yellow-400">Edit</a>

                                {{-- Form untuk Tombol Hapus --}}
                                <form action="{{ route('archives.destroy', $archive) }}" method="POST"
                                    class="inline-block"
                                    onsubmit="return confirm('Confirm termination of this entry?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-400">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-secondary">
                                // NO_DATA_ENTRY_FOUND //
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($archives->hasPages())
            <div class="p-4 border-t border-border bg-surface-light">
                {{ $archives->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>