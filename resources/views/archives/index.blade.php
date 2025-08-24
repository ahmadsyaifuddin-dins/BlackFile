<x-app-layout title="Archives Vault">
    <div class="space-y-6">

        {{-- Header Halaman --}}
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <h1 class="text-xl sm:text-2xl font-bold text-primary">[ ARCHIVES_VAULT ]</h1>
            <a href="{{ route('archives.create') }}"
                class="px-4 py-2 text-sm border rounded-md transition-colors border-primary text-primary hover:bg-primary hover:text-base">
                + ADD_NEW_ENTRY
            </a>
        </div>

        {{-- Notifikasi (Diletakkan di luar agar selalu terlihat) --}}
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

        {{-- ====================================================== --}}
        {{-- ============ DESKTOP VIEW (TABLE) ==================== --}}
        {{-- `hidden` secara default, `md:block` akan menampilkannya di layar medium ke atas --}}
        {{-- ====================================================== --}}
        <div class="hidden md:block">
            <div class="bg-surface border border-border rounded-md">
                <div class="table-responsive">
                    <table class="min-w-full divide-y divide-border">
                        <thead class="bg-surface-light">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Owner</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Description</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Date Added</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y bg-surface divide-border">
                            @forelse ($archives as $archive)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-primary font-semibold">{{ $archive->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">{{ $archive->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($archive->is_public)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900/50 text-green-300">Public</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-900/50 text-red-300">Private</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">{{ $archive->type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary max-w-xs truncate">{{ $archive->description ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">{{ $archive->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                        <a href="{{ route('archives.show', $archive) }}" class="text-secondary text-primary-hover">Details</a>
                                        <a href="{{ route('archives.edit', $archive) }}" class="text-yellow-500 hover:text-yellow-400">Edit</a>
                                        <form action="{{ route('archives.destroy', $archive) }}" method="POST" class="inline-block" onsubmit="return confirm('Confirm termination of this entry?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-400">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-secondary">// NO_DATA_ENTRY_FOUND //</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ====================================================== --}}
        {{-- ============= MOBILE VIEW (CARDS) ==================== --}}
        {{-- Terlihat secara default, `md:hidden` akan menyembunyikannya di layar medium ke atas --}}
        {{-- ====================================================== --}}
        <div class="md:hidden space-y-4">
            @forelse ($archives as $archive)
                <div class="bg-surface border border-border rounded-md p-4 space-y-3">
                    {{-- Baris Header Kartu --}}
                    <div class="flex justify-between items-start">
                        <h2 class="font-bold text-primary break-all">{{ $archive->name }}</h2>
                        <div class="flex-shrink-0 ml-2">
                             @if($archive->is_public)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900/50 text-green-300">Public</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-900/50 text-red-300">Private</span>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Detail dalam Kartu --}}
                    <div class="text-sm text-secondary space-y-1">
                        <p><span class="font-semibold">Owner:</span> {{ $archive->user->name }}</p>
                        <p><span class="font-semibold">Type:</span> {{ $archive->type }}</p>
                        <p><span class="font-semibold">Added:</span> {{ $archive->created_at->format('d M Y, H:i') }}</p>
                        <p class="pt-1 break-words"><span class="font-semibold">Desc:</span> {{ $archive->description ?? 'N/A' }}</p>
                    </div>

                    {{-- Aksi dalam Kartu --}}
                    <div class="border-t border-border pt-3 flex items-center justify-end space-x-4">
                         <a href="{{ route('archives.show', $archive) }}" class="text-secondary text-primary-hover text-sm">Details</a>
                        <a href="{{ route('archives.edit', $archive) }}" class="text-yellow-500 hover:text-yellow-400 text-sm">Edit</a>
                        <form action="{{ route('archives.destroy', $archive) }}" method="POST" class="inline-block" onsubmit="return confirm('Confirm termination of this entry?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-400 text-sm">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-surface border border-border rounded-md p-4 text-center text-sm text-secondary">
                    // NO_DATA_ENTRY_FOUND //
                </div>
            @endforelse
        </div>
        
        {{-- Paginasi (Diletakkan di luar agar selalu terlihat) --}}
        @if ($archives->hasPages())
        <div class="p-4 bg-surface border-t border-border rounded-md">
            {{ $archives->links() }}
        </div>
        @endif
    </div>
</x-app-layout>