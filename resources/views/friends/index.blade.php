<x-app-layout>
    <x-slot:title>
        Friends Network
    </x-slot>
<div class="flex justify-between mb-4">
    <h2 class="text-2xl font-bold">Friends</h2>
    <a href="{{ route('friends.create') }}" class="bg-blue-600 px-4 py-2 rounded hover:bg-blue-700 text-black">+ Add Friend</a>
</div>

@if(session('success'))
<div class="bg-green-700 text-white p-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

@if($friends->count())
    <ul class="space-y-2">
        @foreach($friends as $friend)
            <li class="bg-gray-800 p-4 rounded">
                <div class="flex justify-between items-center">
                    <div>
                        <strong>{{ $friend->name }}</strong> <span class="text-sm text-gray-400">({{ $friend->codename }})</span>
                    </div>
                    <a href="{{ route('friends.tree', $friend->id) }}" class="text-blue-400 hover:underline">View Tree</a>
                </div>
            </li>
        @endforeach
    </ul>
@else
    <p class="text-gray-400">No friends found.</p>
@endif
</x-app-layout>