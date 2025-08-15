<div class="ml-4">
    <div class="p-2 border-l border-gray-600">
        <span class="font-semibold">{{ $friend->name }}</span>
        <span class="text-gray-400">({{ $friend->codename }})</span>
    </div>
    @if($friend->children->count())
        <ul class="ml-6 border-l border-gray-600 pl-4">
            @foreach($friend->children as $child)
                @include('friends.partials.tree-node', ['friend' => $child])
            @endforeach
        </ul>
    @endif
</div>
