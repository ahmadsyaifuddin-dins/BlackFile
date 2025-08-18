<x-app-layout>
    <x-slot:title>
        {{ $pageTitle ?? 'Network Analysis' }}
    </x-slot:title>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
        <h2 class="text-2xl font-bold text-primary">
            > [ {{ $pageTitle ?? 'NETWORK ANALYSIS TERMINAL' }} ]
        </h2>
        <a href="{{ route('friends.create') }}"
            class="mt-2 sm:mt-0 px-4 py-2 bg-primary text-base text-primary-hover transition-colors font-bold tracking-widest rounded-md text-sm">
            [ + ESTABLISH CONNECTION ]
        </a>
    </div>

    <div>
        <div id="graph-controls" class="mb-4 p-4 bg-surface border border-border-color rounded-lg">
            <div class="flex flex-col md:flex-row gap-4 md:items-center">
                <div class="w-full md:w-1/3">
                    <label for="search-input" class="block text-primary text-sm mb-1">> SEARCH ASSET (CODENAME)</label>
                    <input type="text" id="search-input" placeholder="E.g., EAGLE-01"
                        class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary placeholder-secondary/50 p-2 rounded">
                </div>

                <div class="w-full md:w-2/3">
                    <label class="block text-primary text-sm mb-1">> FILTER VISIBILITY</label>
                    <div class="flex flex-wrap gap-x-6 gap-y-2">
                        <label for="filter-user"
                            class="flex items-center space-x-2 text-secondary cursor-pointer hover:text-white">
                            <input type="checkbox" id="filter-user" value="{{ auth()->user()->role->alias }}"
                                class="filter-role form-checkbox bg-base border-border-color text-primary focus:ring-primary">
                            <span>My Role ({{ auth()->user()->role->alias }})</span>
                        </label>
                        <label for="filter-friend"
                            class="flex items-center space-x-2 text-secondary cursor-pointer hover:text-white">
                            <input type="checkbox" id="filter-friend" value="Asset"
                                class="filter-role form-checkbox bg-base border-border-color text-primary focus:ring-primary">
                            <span>Asset</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div id="cy" class="w-full h-[70vh] bg-base border-2 border-border-color rounded-lg" data-nodes='@json($nodes)'
            data-edges='@json($edges)' data-rootnodeid='{{ $rootNodeId }}'></div>
    </div>

    <x-Agent-modal />
</x-app-layout>