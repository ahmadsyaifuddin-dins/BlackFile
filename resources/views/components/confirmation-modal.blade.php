{{-- resources/views/components/confirmation-modal.blade.php --}}
<div x-show="isDeleteModalOpen" @keydown.escape.window="isDeleteModalOpen = false"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 px-4" x-cloak>

    {{-- Perbaikan: ganti isOpen dengan isDeleteModalOpen --}}
    <div @click.outside="isDeleteModalOpen = false" class="bg-gray-900 border border-red-700 rounded-lg shadow-xl w-full max-w-md">
        
        {{-- Perbaikan: ganti actionUrl dengan deleteFormAction --}}
        <form x-bind:action="deleteFormAction" method="POST">
            @csrf
            @method('DELETE')

            <div class="p-6 text-center font-mono">
                <svg class="mx-auto mb-4 h-14 w-14 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                
                <h3 class="mb-5 text-lg font-normal text-gray-300">
                    Are you sure you want to terminate project
                    <br>
                    {{-- Perbaikan: ganti itemName dengan prototypeToDeleteName --}}
                    <span class="font-bold text-yellow-400" x-text="prototypeToDeleteName"></span>?
                </h3>
                
                <p class="text-xs text-gray-500">This action cannot be undone.</p>
            </div>

            <div class="bg-gray-800 px-6 py-3 flex justify-center items-center gap-4">
                {{-- Perbaikan: ganti isOpen dengan isDeleteModalOpen --}}
                <button type="button" @click="isDeleteModalOpen = false" class="text-gray-400 bg-gray-700 hover:bg-gray-600 py-2 px-4 rounded transition cursor-pointer">
                    CANCEL MISSION
                </button>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded cursor-pointer">
                    CONFIRM TERMINATION
                </button>
            </div>
        </form>
    </div>
</div>