<x-app-layout :title="$entity->codename ?? $entity->name">
    <div class="space-y-8">
        {{-- Header & Tombol Aksi --}}
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-primary tracking-wider font-mono">{{ $entity->codename ?? $entity->name }}</h1>
                <p class="text-secondary">{{ $entity->name }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('entities.edit', $entity) }}" class="bg-surface-light border border-border-color text-secondary font-bold py-2 px-4 rounded hover:text-primary hover:border-primary transition-colors">
                    > Edit
                </a>
                <form action="{{ route('entities.destroy', $entity) }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to terminate this entity record? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-900/50 border border-red-500/50 text-red-400 font-bold py-2 px-4 rounded hover:bg-red-900/80 transition-colors">
                        > Terminate
                    </button>
                </form>
            </div>
        </div>

        {{-- Main Info Grid --}}
        <div class="bg-surface border border-border-color rounded-lg p-6">
            <h3 class="text-lg font-semibold text-primary border-b border-border-color pb-2 mb-4">> CORE DATA</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm">
                <div>
                    <span class="block text-secondary text-xs">CATEGORY</span>
                    <span class="font-medium">{{ $entity->category }}</span>
                </div>
                <div>
                    <span class="block text-secondary text-xs">RANK</span>
                    <span class="font-medium">{{ $entity->rank ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="block text-secondary text-xs">ORIGIN</span>
                    <span class="font-medium">{{ $entity->origin ?? 'Unknown' }}</span>
                </div>
                <div>
                    <span class="block text-secondary text-xs">STATUS</span>
                    <span class="font-bold font-mono">{{ $entity->status }}</span>
                </div>
            </div>
        </div>

        {{-- Descriptions --}}
        <div class="bg-surface border border-border-color rounded-lg p-6 space-y-4">
             <div>
                <h3 class="text-lg font-semibold text-primary mb-2">> DESCRIPTION</h3>
                <p class="text-secondary whitespace-pre-wrap">{{ $entity->description }}</p>
            </div>
             <div class="pt-4 border-t border-border-color">
                <h3 class="text-lg font-semibold text-primary mb-2">> ABILITIES</h3>
                <p class="text-secondary whitespace-pre-wrap">{{ $entity->abilities ?? 'None documented.' }}</p>
            </div>
             <div class="pt-4 border-t border-border-color">
                <h3 class="text-lg font-semibold text-primary mb-2">> WEAKNESSES</h3>
                <p class="text-secondary whitespace-pre-wrap">{{ $entity->weaknesses ?? 'None documented.' }}</p>
            </div>
        </div>
        
        {{-- Image Gallery --}}
        @if($entity->images->isNotEmpty())
            <div class="bg-surface border border-border-color rounded-lg p-6">
                <h3 class="text-lg font-semibold text-primary mb-4">> IMAGE ARCHIVES</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($entity->images as $image)
                        <div>
                            <img src="{{ asset('uploads/' . $image->path) }}" alt="{{ $image->caption ?? 'Entity Image' }}" class="w-full h-48 object-cover rounded-md border-2 border-border-color">
                            @if($image->caption)
                                <p class="text-xs text-secondary mt-2 text-center">{{ $image->caption }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>