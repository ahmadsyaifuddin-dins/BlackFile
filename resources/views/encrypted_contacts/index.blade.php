<x-app-layout title="Encrypted Contact Vault">
    {{-- Header Halaman --}}
    <div class="border-y-2 border-dashed border-primary/50 py-4 mb-8">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <div>
                <h1 class="text-4xl font-bold text-primary tracking-widest font-mono text-glow">
                    > ENCRYPTED VAULT
                </h1>
                <p class="text-sm text-secondary font-mono mt-1">Personal encrypted contact directory.</p>
            </div>
            {{-- [PERBAIKAN] Warna teks diubah menjadi hitam agar kontras --}}
            <a href="{{ route('encrypted-contacts.create') }}" class="w-full sm:w-auto text-center bg-primary text-primary font-bold py-2 px-4 hover:bg-primary-hover transition-colors">
                > ADD NEW CONTACT
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-900/50 border border-primary text-primary px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-surface border border-border-color p-4 font-mono">
        <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-3">> CONTACT LIST</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b-2 border-border-color text-sm text-secondary">
                    <tr>
                        <th class="p-3">CODENAME</th>
                        <th class="p-3">CREATED AT</th>
                        <th class="p-3 text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                        <tr class="border-b border-border-color">
                            <td class="p-3 text-white font-bold whitespace-nowrap">{{ $contact->codename }}</td>
                            <td class="p-3 whitespace-nowrap">{{ $contact->created_at->format('d-m-Y H:i') }}</td>
                            <td class="p-3 text-right">
                                {{-- ================================================================ --}}
                                {{-- == PERBAIKAN: Tombol Aksi & Logika Hapus == --}}
                                {{-- ================================================================ --}}
                                <div class="flex justify-end items-center gap-4">
                                    <a href="{{ route('encrypted-contacts.show', $contact) }}" class="text-primary hover:text-white text-sm font-bold whitespace-nowrap">VIEW FILE</a>
                                    <a href="{{ route('encrypted-contacts.edit', $contact) }}" class="text-secondary hover:text-primary text-sm">EDIT</a>
                                    
                                    {{-- Form Hapus yang Benar --}}
                                    <form action="{{ route('encrypted-contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('WARNING: This action is irreversible. Are you sure you want to terminate this contact file?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400 text-sm font-mono whitespace-nowrap cursor-pointer">> TERMINATE</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-6 text-center text-secondary">No encrypted contacts found. Add your first one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
