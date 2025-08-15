<x-app-layout>
    <x-slot:title>
        Add Friend
    </x-slot>
<h2 class="text-2xl font-bold mb-4">Add Friend</h2>

@if($errors->any())
<div class="bg-red-700 text-white p-3 rounded mb-4">
    {{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('friends.store') }}" class="space-y-4">
    @csrf
    <div>
        <label>Name</label>
        <input type="text" name="name" class="w-full p-2 bg-gray-700 rounded" required>
    </div>
    <div>
        <label>Codename</label>
        <input type="text" name="codename" class="w-full p-2 bg-gray-700 rounded" required>
    </div>
    <div>
        <label>Parent Friend (optional)</label>
        <select name="parent_id" class="w-full p-2 bg-gray-700 rounded">
            <option value="">-- None --</option>
            @foreach($parentFriends as $pf)
                <option value="{{ $pf->id }}">{{ $pf->name }} ({{ $pf->codename }})</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="bg-blue-600 px-4 py-2 rounded hover:bg-blue-700">Save</button>
</form>
</x-app-layout>
