<x-app-layout>
    <x-slot:title>
        {{ $pageTitle ?? __('Network Analysis') }}
    </x-slot:title>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
        <h2 class="text-2xl font-bold text-primary">
            > [ {{ $pageTitle ?? __('NETWORK ANALYSIS TERMINAL') }} ]
        </h2>
        <x-button href="{{ route('friends.create') }}" class="mt-2 sm:mt-0">
            [ + {{ __('ESTABLISH CONNECTION') }} ]
        </x-button>
    </div>

    <div>
        <div id="graph-controls" class="mb-4 p-4 bg-surface border border-border-color rounded-lg">
            <div class="flex flex-col md:flex-row gap-4 md:items-center">
                <div class="w-full md:w-1/3">
                    <label for="search-input" class="block text-primary text-sm mb-1">> {{ __('SEARCH ASSET (CODENAME)') }}</label>
                    
                    {{-- Menggunakan x-forms.input dengan slot icon --}}
                    <x-forms.input 
                        id="search-input" 
                        name="search" 
                        placeholder="E.g., EAGLE-01"
                    >
                        {{-- Slot Icon Kaca Pembesar --}}
                        <x-slot:icon>
                            <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </x-slot:icon>
                    </x-forms.input>
                </div>

                <div class="w-full md:w-2/3">
                    <label class="block text-primary text-sm mb-1">> {{ __('FILTER VISIBILITY') }}</label>
                    <div class="flex flex-wrap gap-x-6 gap-y-2">
                        <label for="filter-user"
                            class="flex items-center space-x-2 text-secondary cursor-pointer hover:text-white">
                            <x-forms.checkbox id="filter-user" value="{{ auth()->user()->role->alias }}"
                                class="filter-role form-checkbox bg-base border-border-color text-primary focus:ring-primary">
                            <span>{{ __('My Role') }} ({{ auth()->user()->role->alias }})</span>
                            </x-forms.checkbox>
                        </label>
                        <label for="filter-friend"
                            class="flex items-center space-x-2 text-secondary cursor-pointer hover:text-white">
                            <x-forms.checkbox id="filter-friend" value="Asset"
                                class="filter-role form-checkbox bg-base border-border-color text-primary focus:ring-primary">
                            <span>{{ __('Asset') }}</span>
                            </x-forms.checkbox>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div id="cy" class="w-full h-[90vh] sm:h-[110vh] bg-base border-2 border-border-color rounded-lg" data-nodes='@json($nodes)'
            data-edges='@json($edges)' data-rootnodeid='{{ $rootNodeId }}'></div>
    </div>

    <x-agent-modal />
</x-app-layout>