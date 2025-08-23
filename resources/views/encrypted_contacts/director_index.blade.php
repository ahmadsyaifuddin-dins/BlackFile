<x-app-layout title="Directorate Vault Oversight">
    {{-- Header Halaman --}}
    <div class="border-y-2 border-dashed border-primary/50 py-4 mb-8">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <div>
                <h1 class="text-base sm:text-4xl font-bold text-primary tracking-widest font-mono text-glow">
                    > DIRECTORATE VAULT OVERSIGHT
                </h1>
                <p class="text-sm text-secondary font-mono mt-1">Full access to all encrypted contact files across the network.</p>
            </div>
            <a href="{{ route('encrypted-contacts.create') }}" class="w-full sm:w-auto text-center bg-primary text-primary font-bold py-2 px-4 hover:bg-primary-hover transition-colors">
                > ADD PERSONAL CONTACT
            </a>
        </div>
    </div>

    {{-- [PERUBAHAN] Bagian untuk Brankas Pribadi Director --}}
    <div class="mb-8 bg-surface border border-border-color p-4 font-mono">
        <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-3">
            > MY PERSONAL VAULT
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
                    @forelse($directorContacts as $contact)
                        <tr class="border-b border-border-color">
                            <td class="p-3 text-white font-bold whitespace-nowrap">{{ $contact->codename }}</td>
                            <td class="p-3 whitespace-nowrap">{{ $contact->created_at->format('d-m-Y H:i') }}</td>
                            <td class="p-3 text-right whitespace-nowrap">
                                <div class="flex justify-end items-center gap-4">
                                    <a href="{{ route('encrypted-contacts.show', $contact) }}" class="text-primary hover:text-white text-sm font-bold">VIEW</a>
                                    <a href="{{ route('encrypted-contacts.edit', $contact) }}" class="text-secondary hover:text-primary text-sm">EDIT</a>
                                    <form action="{{ route('encrypted-contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to terminate this contact file?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400 text-sm font-mono">TERMINATE</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center text-secondary">Your personal vault is empty.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- [PERUBAHAN] Bagian untuk Pengawasan Agen Lain --}}
    <div class="space-y-8">
        <h2 class="text-2xl font-bold text-secondary tracking-widest font-mono">
            AGENT VAULT OVERSIGHT
        </h2>
        @forelse($agentContactsByUser as $userId => $contacts)
            <div class="bg-surface border border-border-color p-4 font-mono">
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
                <p class="text-secondary font-mono text-lg">[ NO OTHER AGENT CONTACTS FOUND IN THE NETWORK ]</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
