<x-app-layout>
    <x-slot:title>
        Friend Tree
    </x-slot>
<h2 class="text-2xl font-bold mb-4">Tree for {{ $root->name }} ({{ $root->codename }})</h2>

<div class="bg-gray-800 p-4 rounded">
    @include('friends.partials.tree-node', ['friend' => $root])
</div>
</x-app-layout>
