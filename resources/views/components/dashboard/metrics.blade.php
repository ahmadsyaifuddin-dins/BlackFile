@props(['totalAgents', 'totalEntities', 'totalPrototypes', 'totalPublicArchives', 'totalUrlArchives', 'totalFileArchives', 'totalPhysicalStorage'])

<div class="lg:col-span-1 border border-border-color border-gray-600 bg-surface p-4">
    <h2 class="text-lg font-bold text-primary border-b border-gray-600 border-border-color pb-2 mb-3">> {{ __('KEY METRICS') }}</h2>
    <div class="space-y-2 text-sm">
        <p class="flex justify-between"><span>> {{ __('AGENTS ROSTER') }}:</span> <span class="text-white font-bold">{{ $totalAgents }}</span></p>
        <p class="flex justify-between"><span>> {{ __('ENTITIES DATABASE') }}:</span> <span class="text-white font-bold">{{ $totalEntities }}</span></p>
        <p class="flex justify-between border-b border-border-color pb-2"><span>> {{ __('PROJECTS PIPELINE') }}:</span> <span class="text-white font-bold">{{ $totalPrototypes }}</span></p>
        <p class="flex justify-between"><span>> PUBLIC ARCHIVES:</span> <span class="text-white font-bold">{{ $totalPublicArchives }}</span></p>
        <p class="flex justify-between"><span>> URL ARCHIVES:</span> <span class="text-white font-bold">{{ $totalUrlArchives }}</span></p>
        <p class="flex justify-between"><span>> FILE ARCHIVES:</span> <span class="text-white font-bold">{{ $totalFileArchives }}</span></p>
        <p class="flex justify-between"><span>> PHYSICAL STORAGE:</span> <span class="text-white font-bold">{{ $totalPhysicalStorage }}</span></p>
    </div>
</div>