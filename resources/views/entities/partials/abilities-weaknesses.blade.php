<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Abilities --}}
    <div>
        <div class="flex items-center gap-2 mb-2">
            <div class="h-2 w-2 bg-blue-500"></div>
            <h3 class="text-blue-400 font-bold tracking-widest text-sm uppercase">ANOMALOUS
                CAPABILITIES</h3>
        </div>
        <div class="bg-surface/50 border-l-2 border-blue-500/50 p-4 h-full">
            <p class="text-sm text-gray-400 font-mono leading-relaxed whitespace-pre-line break-words text-left">
                {{ $entity->abilities ?? 'No data available.' }}
            </p>
        </div>
    </div>

    {{-- Weaknesses --}}
    <div>
        <div class="flex items-center gap-2 mb-2">
            <div class="h-2 w-2 bg-red-500"></div>
            <h3 class="text-red-400 font-bold tracking-widest text-sm uppercase">KNOWN
                VULNERABILITIES</h3>
        </div>
        <div class="bg-surface/50 border-l-2 border-red-500/50 p-4 h-full">
            <p class="text-sm text-gray-400 font-mono leading-relaxed whitespace-pre-line break-words text-left">
                {{ trim($entity->weaknesses ?? 'No data available.') }}</p>
        </div>
    </div>
</div>
