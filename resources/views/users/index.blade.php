<x-app-layout>
    <x-slot:title>Agents Directory</x-slot:title>

    <h2 class="text-2xl font-bold text-primary text-glow mb-6">> [ AGENTS DIRECTORY ]</h2>

    <div class="space-y-4">
        @foreach($users as $agent)
            <div class="block bg-surface/50 border border-border-color rounded-lg p-4 transition-colors">
                {{-- [DIUBAH] Tambahkan class flex-col dan sm:flex-row --}}
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                    
                    <div>
                        <a href="{{ route('agents.show', $agent->id) }}" class="hover:text-primary">
                            <p class="font-bold text-white text-lg text-glow">{{ $agent->codename }}</p>
                            <p class="text-secondary text-sm">{{ $agent->role->alias }}</p>
                        </a>
                    </div>
    
                    @if(strtolower(Auth::user()->role->name) === 'director')
                        {{-- [DIUBAH] Tambahkan margin atas untuk mobile dan atur alignment --}}
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