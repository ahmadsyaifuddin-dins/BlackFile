<x-app-layout title="Entities Database">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <h2 class="text-2xl font-bold text-primary">Entities Database // Index</h2>
            <a href="{{ route('entities.create') }}"
                class="w-full sm:w-auto text-center bg-primary text-primary font-bold py-2 px-4 rounded hover:bg-primary-hover transition-colors">
                > Register New Entity
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-900/50 border border-primary text-primary px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($entities as $entity)
            <div
                class="bg-surface border-2 border-border-color hover:border-primary transition-colors duration-300 rounded-none group">
                <div class="px-4 py-2 border-b-2 border-border-color flex justify-between items-center">
                    <h3
                        class="font-bold text-lg text-primary group-hover:text-white tracking-widest font-mono truncate">
                        {{ $entity->codename ?? $entity->name }}
                    </h3>
                    <span class="text-xs font-mono px-2 py-1 border border-secondary text-secondary rounded-full">
                        {{ $entity->status }}
                    </span>
                </div>

                <div class="p-4 flex flex-col sm:flex-row gap-4">
                    <div class="w-full sm:w-1/3 flex-shrink-0">
                        <a href="{{ route('entities.show', $entity) }}">
                            @if($entity->images->isNotEmpty())
                                @php
                                    $thumbnail = $entity->images->first();
                                    $imagePath = Illuminate\Support\Str::startsWith($thumbnail->path, 'http')
                                        ? $thumbnail->path
                                        : asset('uploads/' . $thumbnail->path);
                                @endphp
                                <img src="{{ $imagePath }}"
                                    alt="{{ $entity->codename }}"
                                    class="w-full h-32 object-cover grayscale group-hover:grayscale-0 transition-all duration-300">
                            @else
                                <div
                                    class="w-full h-32 bg-base flex items-center justify-center border border-dashed border-border-color">
                                    <span class="text-secondary font-mono text-sm">[NO VISUALS]</span>
                                </div>
                            @endif
                        </a>
                    </div>

                    <div class="flex-grow">
                        <p class="text-secondary text-sm mb-2 line-clamp-3">
                            {{ Str::limit($entity->description, 100) }}
                        </p>
                        <div class="text-xs font-mono text-gray-400">
                            <p>> Category: {{ $entity->category }}</p>
                            <p>> Rank: {{ $entity->rank ?? 'Unclassified' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Footer Aksi dengan Tombol Hapus --}}
                <div class="px-4 py-2 border-t-2 border-border-color flex items-center justify-end gap-4">
                    <a href="{{ route('entities.edit', $entity) }}"
                        class="text-secondary hover:text-primary text-sm font-mono">> EDIT</a>
                    
                    <form action="{{ route('entities.destroy', $entity) }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to terminate this entity record? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-400 text-sm font-mono">> TERMINATE</button>
                    </form>

                    <a href="{{ route('entities.show', $entity) }}"
                        class="text-primary hover:text-white text-sm font-bold font-mono">> VIEW ENTITIES</a>
                </div>
            </div>
            @empty
            <div class="md:col-span-2 xl:col-span-3 text-center py-12 border-2 border-dashed border-border-color">
                <p class="text-secondary font-mono text-lg">[ NO ENTITY RECORDS FOUND IN DATABASE ]</p>
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $entities->links() }}
        </div>
    </div>
</x-app-layout>
