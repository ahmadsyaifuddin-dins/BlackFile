<x-app-layout>
    {{-- 
        [MODULAR] 
        The giant x-data object is now gone. 
        We now call the 'prototypesCRUD' component we registered in app.js.
    --}}
    <div x-data="prototypesCRUD">
        <x-slot:title>Prototypes Project</x-slot:title>
        
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Prototypes Project') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-end mb-4">
                    {{-- This button now calls the openCreateModal() function from our JS file --}}
                    <button @click="openCreateModal()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300 w-full sm:w-auto">
                        [ + FILE NEW PROTOTYPE ]
                    </button>
                </div>

                @if (session('success'))
                <div class="bg-green-800 border border-green-600 text-green-200 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                @endif

                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-2 sm:p-6 bg-gray-900 border-b border-gray-700">
                        @if($prototypes->isEmpty())
                        <div class="text-center py-10 font-mono text-gray-500">
                            <p>// NO PROTOTYPE FOUND IN THE ARCHIVE //</p>
                            <p class="mt-4">Click "[ + FILE NEW PROTOTYPE ]" to begin cataloging your work.</p>
                        </div>
                        @else
                        <div class="w-full">
                            <table class="min-w-full font-mono">
                                <thead class="hidden sm:table-header-group">
                                    <tr>
                                        <th class="px-6 py-3 border-b-2 border-gray-700 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider">Codename</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-700 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider">Project Type</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-700 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-700 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider">Last Update</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-700"></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800">
                                    @foreach ($prototypes as $prototype)
                                    <tr class="block sm:table-row border-b sm:border-b-0 border-gray-700 mb-4 sm:mb-0">
                                        <td class="px-6 py-4 whitespace-no-wrap border-b sm:border-b-0 border-gray-700 block sm:table-cell" data-label="Codename:">
                                            <div class="text-sm leading-5 text-green-400">{{ $prototype->codename }}</div>
                                            <div class="text-xs leading-5 text-gray-400">{{ $prototype->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b sm:border-b-0 border-gray-700 block sm:table-cell" data-label="Project Type:">
                                            <span class="sm:hidden text-gray-400 mr-2">Project Type: </span>{{ $prototype->project_type }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b sm:border-b-0 border-gray-700 block sm:table-cell" data-label="Status:">
                                            <span class="sm:hidden text-gray-400 mr-2">Status: </span>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-cyan-900 text-cyan-300">
                                                {{ $prototype->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b sm:border-b-0 border-gray-700 block sm:table-cell" data-label="Last Update:">
                                            <span class="sm:hidden text-gray-400 mr-2">Last Update: </span>{{ $prototype->updated_at->format('Y-m-d H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-left sm:text-right border-b sm:border-b-0 border-gray-700 block sm:table-cell">
                                            <a href="{{ route('prototypes.show', $prototype) }}" class="text-indigo-400 hover:text-indigo-600 transition">View</a>
                                            
                                            {{-- This button now calls the openEditModal() function from our JS file --}}
                                            <button 
                                                type="button" 
                                                @click="openEditModal({{ json_encode($prototype) }})" 
                                                class="ml-0 sm:ml-4 mt-2 sm:mt-0 inline-block text-yellow-400 hover:text-yellow-600 transition appearance-none bg-transparent border-none p-0 cursor-pointer"
                                            >
                                                Edit
                                            </button>

                                             {{-- [BARU] Tombol Delete --}}
                                             <button
                                             @click="
                                                 isDeleteModalOpen = true;
                                                 deleteUrl = '{{ route('prototypes.destroy', $prototype) }}';
                                                 prototypeToDeleteName = '{{ $prototype->codename }}';
                                             "
                                             class="ml-0 sm:ml-4 mt-2 sm:mt-0 inline-block text-red-400 hover:text-red-600 transition appearance-none bg-transparent border-none p-0 cursor-pointer"
                                         >
                                             Delete
                                         </button>
                                            
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $prototypes->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        {{-- The modal component remains unchanged --}}
        <x-prototype-form-modal />

         {{-- [BARU] Modal Konfirmasi Hapus --}}
         <div x-show="isDeleteModalOpen" x-transition class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 px-4" x-cloak>
            <div @click.outside="isDeleteModalOpen = false" class="bg-gray-900 border border-red-700 rounded-lg shadow-xl w-full max-w-md">
                <form x-bind:action="deleteUrl" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="p-6 text-center font-mono">
                        <svg class="mx-auto mb-4 h-14 w-14 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-300">
                            Are you sure you want to terminate project
                            <br>
                            <span class="font-bold text-yellow-400" x-text="prototypeToDeleteName"></span>?
                        </h3>
                        <p class="text-xs text-gray-500">This action cannot be undone.</p>
                    </div>
                    <div class="bg-gray-800 px-6 py-3 flex justify-center items-center gap-4">
                        <button type="button" @click="isDeleteModalOpen = false" class="text-gray-400 bg-gray-700 hover:bg-gray-600 py-2 px-4 rounded transition">
                            CANCEL MISSION
                        </button>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            CONFIRM TERMINATION
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>