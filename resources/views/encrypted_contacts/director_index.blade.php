<x-app-layout title="Directorate Vault Oversight">
    {{-- Header Halaman --}}
    <div class="border-y-2 border-dashed border-primary/50 py-4 mb-8">
        <h1 class="text-base sm:text-4xl font-bold text-primary tracking-widest font-mono text-glow">
            > DIRECTORATE VAULT OVERSIGHT
        </h1>
        <p class="text-sm text-secondary font-mono mt-1">Full access to all encrypted contact files across the network.</p>
    </div>

    <div class="space-y-8">
        {{-- Loop untuk setiap grup kontak (per agen) --}}
        @forelse($contactsByUser as $userId => $contacts)
            <div class="bg-surface border border-border-color p-4 font-mono">
                {{-- Tampilkan nama agen sebagai header grup --}}
                <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-3">
                    > AGENT: <span class="text-white">{{ $contacts->first()->user->codename }}</span>
                </h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="border-b-2 border-border-color text-sm text-secondary">
                            <tr>
                                <th class="p-3">CODENAME</th>
                                <th class="p-3 whitespace-nowrap">CREATED AT</th>
                                <th class="p-3 text-right whitespace-nowrap">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contacts as $contact)
                                <tr class="border-b border-border-color">
                                    <td class="p-3 text-white font-bold whitespace-nowrap">{{ $contact->codename }}</td>
                                    <td class="p-3 whitespace-nowrap">{{ $contact->created_at->format('d-m-Y H:i') }}</td>
                                    <td class="p-3 text-right whitespace-nowrap">
                                        {{-- Link ini akan langsung menampilkan data karena Director tidak perlu Master Password --}}
                                        <a href="{{ route('encrypted-contacts.show', $contact) }}" class="text-primary hover:text-white text-sm font-bold">VIEW FILE</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="text-center py-12 border-2 border-dashed border-border-color">
                <p class="text-secondary font-mono text-lg">[ NO ENCRYPTED CONTACTS FOUND IN THE NETWORK ]</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
