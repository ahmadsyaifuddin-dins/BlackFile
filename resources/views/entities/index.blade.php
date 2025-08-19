<x-app-layout title="Entities Database">
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-primary">Entities Database // Index</h2>
            <a href="{{ route('entities.create') }}" class="bg-primary text-black font-bold py-2 px-4 rounded hover:bg-primary-hover transition-colors">
                > Register New Entity
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-900/50 border border-primary text-primary px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-surface border border-border-color rounded-lg p-4">
            <div class="table-responsive">
                <table class="w-full text-left">
                    <thead class="border-b-2 border-border-color">
                        <tr>
                            <th class="p-3">Codename</th>
                            <th class="p-3">Category</th>
                            <th class="p-3">Rank</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entities as $entity)
                            <tr class="border-b border-border-color hover:bg-surface-light">
                                <td class="p-3 font-mono text-primary">{{ $entity->codename ?? $entity->name }}</td>
                                <td class="p-3">{{ $entity->category }}</td>
                                <td class="p-3">{{ $entity->rank ?? 'N/A' }}</td>
                                <td class="p-3">{{ $entity->status }}</td>
                                <td class="p-3">
                                    <div class="flex items-center space-x-4">
                                        <a href="{{ route('entities.show', $entity) }}" class="text-secondary hover:text-primary">View</a>
                                        <a href="{{ route('entities.edit', $entity) }}" class="text-blue-400 hover:text-blue-300">Edit</a>
                                        {{-- Form untuk tombol delete --}}
                                        <form action="{{ route('entities.destroy', $entity) }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to terminate this entity record? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-400">Terminate</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-3 text-center text-secondary">No entities found in the database.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             <div class="mt-4">
                {{ $entities->links() }}
            </div>
        </div>
    </div>
</x-app-layout>