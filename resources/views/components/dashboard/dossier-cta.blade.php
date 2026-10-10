@props(['incomplete' => false])

@if (!empty($incomplete))
    <div class="mb-6 border-2 border-amber-500/50 bg-amber-500/10 px-4 py-3 rounded flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="flex items-center gap-3">
            <span class="w-2.5 h-2.5 bg-amber-400 animate-pulse rounded-full"></span>
            <div>
                <p class="text-sm font-bold text-amber-400 tracking-wider">> [ {{ __('DOSSIER INCOMPLETE') }} ]</p>
                <p class="text-xs text-secondary mt-0.5">{{ __('Fill in your gender & date of birth to complete this dossier.') }}</p>
            </div>
        </div>
        <x-button variant="outline" href="{{ route('profile.edit') }}" class="w-full sm:w-auto justify-center">
            > [ {{ __('COMPLETE PROFILE') }} ]
        </x-button>
    </div>
@endif