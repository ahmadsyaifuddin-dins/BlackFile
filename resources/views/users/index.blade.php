<x-app-layout>
    <x-slot:title>Agent Directory</x-slot:title>

    <h2 class="text-2xl font-bold text-primary text-glow mb-6">> [ AGENT DIRECTORY ]</h2>

    <div class="space-y-4">
        @foreach($users as $agent)
            <div class="block bg-surface/50 border border-border-color rounded-lg p-4 hover:border-primary transition-colors">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                    
                    <!-- [DIUBAH] Info Agent sekarang mencakup avatar -->
                    <div class="flex items-center space-x-4">
                        <img src="{{ $agent->avatar ? asset($agent->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($agent->codename).'&size=64&background=161b22&color=2ea043&bold=true' }}" 
                             alt="Avatar" class="w-12 h-12 rounded-full border-2 border-border-color flex-shrink-0">
                        <div>
                            <a href="{{ route('agents.show', $agent->id) }}">
                                <p class="font-bold text-white text-lg text-glow">{{ $agent->codename }}</p>
                                <p class="text-secondary text-sm">{{ $agent->role->alias }}</p>
                            </a>
                        </div>
                    </div>

                    <!-- Tombol Aksi (Hanya untuk Director) -->
                    @if(strtolower(Auth::user()->role->name) === 'director')
                        <div class="flex items-center space-x-3 mt-4 sm:mt-0 self-end sm:self-center">
                            <a href="{{ route('agents.edit', $agent->id) }}" class="text-blue-400 hover:text-blue-300" title="Edit Agent">
                                [ EDIT ]
                            </a>
                            <form method="POST" action="{{ route('agents.destroy', $agent->id) }}" onsubmit="return confirm('Confirm termination of agent {{ $agent->codename }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-400" title="Delete Agent">
                                    [ DELETE ]
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-6">
        {{ $users->links() }}
    </div>
</x-app-layout>