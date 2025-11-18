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
                            <!-- Dibuat flex-wrap agar tombol tidak aneh di layar kecil -->
                            <div class="flex flex-wrap items-center gap-4">
                                <x-button href="{{ route('credits.edit', Auth::user()->id) }}">Edit Your Credits</x-button>
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
                         <!-- Layout diubah menjadi flex-col di mobile, dan flex-row di layar besar -->
                         <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-3 bg-gray-700/50 rounded-lg mb-3">
                            <div>
                                <p class="font-bold text-white">{{ $user->name }}</p>
                                <div class="flex items-center gap-4 text-sm text-gray-400 mt-1">
                                    <span>{{ $user->credits->count() }} entries</span>
                                    <!-- Tampilkan jumlah view -->
                                    <span class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                        {{ $user->credit_views_count }} views
                                    </span>
                                </div>
                            </div>
                            <!-- Dibuat flex-wrap agar tombol tidak aneh di layar kecil -->
                            <div class="flex items-center flex-wrap gap-2 justify-start sm:justify-end">
                                @if($user->slug)
                                    <a href="{{ route('credits.public', $user->slug) }}" class="text-primary hover:underline text-sm" target="_blank">View Public</a>
                                @endif
                                <a href="{{ route('credits.edit', $user->id) }}" class="inline-flex items-center px-3 py-1 bg-gray-600 rounded-md text-xs text-white uppercase hover:bg-gray-500">
                                    Edit
                                </a>
                                <x-button.delete :action="route('credits.destroy', $user->id)" title="TERMINATE CREDITS?" message="Confirm termination of all credits for {{ $user->name }}?" target="{{ $user->name }}" class="inline-flex items-center px-3 py-1 bg-red-700 rounded-md text-xs text-white uppercase hover:bg-red-600 hover:text-white">
                                    Delete
                                </x-button.delete>
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
                            <x-button href="{{ route('credits.edit', Auth::user()->id) }}">Edit Your Credits</x-button>
                            @if(Auth::user()->slug)
                            <x-button href="{{ route('credits.public', Auth::user()->slug) }}" target="_blank">View Public Page</x-button>
                            @endif
                        </div>
                    @else
                        <x-button href="{{ route('credits.create') }}">Create Your Credits</x-button>
                        <p class="mt-4 text-sm text-gray-500">Once you create your credits, a public link will be available here.</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
