<div x-data="creditsForm({ 
    initialCredits: {{ $credits ?? '[]' }}, 
    initialMusicPath: '{{ $musicPath ?? '' }}' 
})" class="font-mono text-sm">

    {{-- Error Handling --}}
    @if($errors->any())
    <div class="mb-6 bg-red-900/20 border-l-4 border-red-500 text-red-400 p-4" role="alert">
        <p class="font-bold flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            > SYSTEM_ERROR: Data Input Anomaly
        </p>
        <ul class="mt-2 list-disc list-inside text-xs opacity-80">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Main Loop --}}
    <div class="space-y-6">
        <template x-for="(credit, creditIndex) in credits" :key="credit.uniqueKey">
            {{-- Include Card Module --}}
            @include('credits.card-item')
        </template>
    </div>

    {{-- Add New Section Button --}}
    <div class="mt-4">
        <button @click="addCredit" type="button"
            class="cursor-pointer w-full py-3 border-2 border-dashed border-border-color text-secondary hover:text-primary hover:border-primary transition-all uppercase tracking-widest text-sm font-bold">
            [ + INITIALIZE NEW CREDIT SECTION ]
        </button>
    </div>

    {{-- Audio Module --}}
    @include('credits.audio-section')

    {{-- Save Button --}}
    <div class="flex items-center justify-end mt-8 pt-6 border-t border-border-color">
        <x-button type="submit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
            <span>SAVE SEQUENCE</span>
        </x-button>
    </div>

</div>

{{-- Scripts Module --}}
@include('credits.scripts')