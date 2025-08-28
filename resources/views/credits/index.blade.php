<!-- resources/views/credits/index.blade.php -->
<x-app-layout>
    <x-slot:title>
        End Credits Management
    </x-slot:title>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('End Credits Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">

                @if(Auth::user()->role->name === 'Director')
                    <!-- BAGIAN MANAJEMEN PRIBADI DIRECTOR -->
                    <div class="pb-6 mb-6 border-b border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Your Personal Credits</h3>
                        <p class="text-gray-400 mt-1 mb-4">Manage your own name in the end credits roster.</p>
                        @if($directorHasCredits)
                            <!-- PERBAIKAN: Dibuat flex-wrap agar tombol tidak aneh di layar kecil -->
                            <div class="flex flex-wrap items-center gap-4">
                                <a href="{{ route('credits.edit', Auth::user()->id) }}" class="inline-flex items-center px-4 py-2 bg-primary border rounded-md font-semibold text-xs text-white uppercase bg-primary-hover">
                                    Edit Your Credits
                                </a>
                                @if(Auth::user()->slug)
                                <a href="{{ route('credits.public', Auth::user()->slug) }}" class="text-primary hover:underline" target="_blank">
                                    View Public Page
                                </a>
                                @endif
                            </div>
                        @else
                            <a href="{{ route('credits.create') }}" class="inline-flex items-center px-4 py-2 bg-primary border rounded-md font-semibold text-xs text-white uppercase hover:bg-primary-hover">
                                Create Your Credits
                            </a>
                        @endif
                    </div>
                    
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Credits Roster by Operative</h3>
                    @forelse ($usersWithCredits as $user)
                         <!-- PERBAIKAN: Layout diubah menjadi flex-col di mobile, dan flex-row di layar besar -->
                         <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-3 bg-gray-700/50 rounded-lg mb-3">
                            <div>
                                <p class="font-bold text-white">{{ $user->name }}</p>
                                <p class="text-sm text-gray-400">{{ $user->credits->count() }} entries</p>
                            </div>
                            <!-- PERBAIKAN: Dibuat flex-wrap agar tombol tidak aneh di layar kecil -->
                            <div class="flex items-center flex-wrap gap-2 justify-start sm:justify-end">
                                @if($user->slug)
                                    <a href="{{ route('credits.public', $user->slug) }}" class="text-primary hover:underline text-sm" target="_blank">View Public</a>
                                @endif
                                <a href="{{ route('credits.edit', $user->id) }}" class="inline-flex items-center px-3 py-1 bg-gray-600 border rounded-md text-xs text-white uppercase hover:bg-gray-500">
                                    Edit
                                </a>
                                <form action="{{ route('credits.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all credits for {{ $user->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-700 border rounded-md text-xs text-white uppercase hover:bg-red-600">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">No credit entries found from any operative.</p>
                    @endforelse
                @else
                    <!-- Tampilan untuk Agent -->
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Your End Credits</h3>
                    <p class="text-gray-400 mt-1 mb-6">Manage the entire list of names that will appear on your public credits page.</p>
                    @if($hasCredits)
                        <div class="flex flex-wrap items-center gap-4">
                            <a href="{{ route('credits.edit', Auth::user()->id) }}" class="inline-flex items-center px-4 py-2 bg-primary border rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-hover">
                                Edit Your Credits
                            </a>
                            @if(Auth::user()->slug)
                            <a href="{{ route('credits.public', Auth::user()->slug) }}" class="text-primary hover:underline" target="_blank">
                                View Public Page
                            </a>
                            @endif
                        </div>
                    @else
                        <a href="{{ route('credits.create') }}" class="inline-flex items-center px-4 py-2 bg-primary border rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-hover">
                            Create Your Credits
                        </a>
                        <p class="mt-4 text-sm text-gray-500">Once you create your credits, a public link will be available here.</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
