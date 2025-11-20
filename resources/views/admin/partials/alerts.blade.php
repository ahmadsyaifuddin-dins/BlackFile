@if (session('success'))
    <div class="mb-4 bg-primary/10 border-l-4 border-primary text-primary-hover p-4 rounded-r-lg" role="alert">
        <p>{{ session('success') }}</p>
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 bg-red-600/10 border-l-4 border-red-500 text-red-400 p-4 rounded-r-lg" role="alert">
        <p class="font-bold">Broadcast Failed</p>
        <ul class="mt-1 list-disc list-inside text-xs">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
