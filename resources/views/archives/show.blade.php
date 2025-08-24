<x-app-layout title="Detail Arsip: {{ $archive->name }}">
    <div class="max-w-3xl mx-auto space-y-6">

        {{-- Header Halaman --}}
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-primary truncate">[ {{ $archive->name }} ]</h1>
            <a href="{{ route('archives.index') }}" class="text-sm text-secondary hover:text-primary">
                &lt;-- Back to Vault
            </a>
        </div>

        {{-- Kontainer Detail --}}
        <div class="bg-surface border border-border rounded-md p-6 space-y-4">
            <div>
                <h3 class="font-semibold text-secondary">Description:</h3>
                <p class="text-primary mt-1">{{ $archive->description ?? '// No description provided' }}</p>
            </div>
            <div class="border-t border-border"></div>
            <div>
                <h3 class="font-semibold text-secondary">Entry Type:</h3>
                <p class="text-primary mt-1">{{ ucfirst($archive->type) }}</p>
            </div>
            <div class="border-t border-border"></div>

            {{-- Tampilkan detail berdasarkan tipe --}}
            @if ($archive->type === 'file')
                <div>
                    <h3 class="font-semibold text-secondary">File Size:</h3>
                    <p class="text-primary mt-1">{{ \Illuminate\Support\Number::fileSize($archive->size, precision: 2) }}</p>
                </div>
                <div class="border-t border-border"></div>
                <div>
                    <h3 class="font-semibold text-secondary">MIME Type:</h3>
                    <p class="text-primary mt-1 break-all">{{ $archive->mime_type }}</p>
                </div>
            @else
                <div>
                    <h3 class="font-semibold text-secondary">Links:</h3>
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        @foreach ($archive->links as $link)
                            <li>
                                <a href="{{ $link }}" target="_blank" rel="noopener noreferrer" class="text-primary text-primary-hover break-all">
                                    {{ $link }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="border-t border-border"></div>
            <div>
                <h3 class="font-semibold text-secondary">Date Added:</h3>
                <p class="text-primary mt-1">{{ $archive->created_at->format('d F Y, H:i:s') }}</p>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex items-center gap-4">
            @if ($archive->type === 'file')
                 <a href="{{ asset('uploads/' . $archive->file_path) }}" target="_blank" 
                    class="px-4 py-2 text-sm border rounded-md transition-colors border-primary text-primary hover:bg-primary hover:text-base">
                    Download File
                </a>
            @endif
             <a href="{{ route('archives.edit', $archive) }}" 
                class="px-4 py-2 text-sm border rounded-md transition-colors border-primary text-primary hover:bg-primary hover:text-base">
                Edit Entry
            </a>
        </div>
    </div>
</x-app-layout>