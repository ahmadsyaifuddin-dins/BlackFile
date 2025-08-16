<x-app-layout>
    <x-slot:title>
        Edit Asset: {{ $friend->codename }}
    </x-slot:title>

    <div class="p-4 md:p-6 bg-surface border border-border-color rounded-lg">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-primary">
                > [ EDITING DOSSIER: {{ $friend->codename }} ]
            </h2>
            <a href="{{ route('friends.index') }}" class="text-secondary hover:text-primary transition-colors text-sm">
                &lt; Back to Network
            </a>
        </div>

        @if($errors->any())
            <div class="mb-4 bg-red-900/50 border-l-4 border-red-500 text-red-300 p-4 rounded-r-lg" role="alert">
                <p class="font-bold">> Data Input Anomaly Detected:</p>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('friends.update', $friend->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-primary text-sm">> REAL NAME</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $friend->name) }}"
                           class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary placeholder-secondary/50 p-2 rounded" 
                           required>
                </div>

                <div>
                    <label for="codename" class="block text-primary text-sm">> CODENAME</label>
                    <input type="text" id="codename" name="codename" value="{{ old('codename', $friend->codename) }}"
                           class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary placeholder-secondary/50 p-2 rounded" 
                           required>
                </div>
            </div>

            {{-- Dropdown untuk Parent Asset sudah DIHAPUS --}}
            
            <div class="border-t border-border-color pt-6 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-primary text-base hover:bg-primary-hover transition-colors font-bold tracking-widest rounded-md text-sm">
                    [ UPDATE DOSSIER ]
                </button>
            </div>
        </form>
    </div>
</x-app-layout>