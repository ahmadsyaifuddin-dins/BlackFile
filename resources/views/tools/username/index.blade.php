<x-app-layout title="IDENTITY SEEKER // OSINT">
    <div class="max-w-7xl mx-auto py-3 sm:py-6 px-3 sm:px-6 lg:px-8 min-h-screen flex flex-col">

        {{-- Header Section --}}
        @include('tools.username.partials.header')

        {{-- Search Form + History + Export --}}
        @include('tools.username.partials.search-form')

        {{-- Main Content Area --}}
        <div class="flex-grow grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 min-h-0">
            
            {{-- Terminal Log (Desktop Only) --}}
            @include('tools.username.partials.terminal-log')

            {{-- Results Grid --}}
            @include('tools.username.partials.results-grid')
            
        </div>

        {{-- Mobile Log Modal --}}
        @include('tools.username.partials.mobile-log')

    </div>

    {{-- JavaScript Logic --}}
    @include('tools.username.partials.scripts')
    
</x-app-layout>