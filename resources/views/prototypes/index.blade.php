<x-app-layout>
    <div x-data="prototypesCRUD" x-init="init()">
        <x-slot:title>Prototypes Project</x-slot:title>

        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Prototypes Project') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="w-full px-4 sm:px-6 lg:px-8">
                <div class="flex justify-end mb-4">
                    <button @click="openCreateModal()"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300 w-full sm:w-auto">
                        [ + FILE NEW PROTOTYPE ]
                    </button>
                </div>

                <x-prototypes.filter-section :projectTypes="$projectTypes" :users="$users" />

                @if (session('success'))
                <div class="bg-green-800 border border-green-600 text-green-200 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                @endif

                <div class="bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-2 sm:p-6 bg-gray-900 border-b border-gray-700">
                        @if($prototypes->isEmpty())
                        <div class="text-center py-10 font-mono text-gray-500">
                            <p>// NO PROTOTYPE FOUND IN THE ARCHIVE //</p>
                            <p class="mt-4">Click "[ + FILE NEW PROTOTYPE ]" to begin cataloging your work.</p>
                        </div>
                        @else
                        <div class="hidden sm:block">
                            {{-- [FIX] Table container that respects sidebar layout --}}
                            <div class="rounded-lg border border-gray-700">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full font-mono divide-y divide-gray-700">
                                        <thead class="bg-gray-800">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider w-16">#</th>
                                                <th class="px-4 py-3 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider min-w-[200px]">Codename</th>
                                                <th class="px-4 py-3 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider min-w-[150px]">Project By</th>
                                                <th class="px-4 py-3 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider min-w-[120px]">Project Type</th>
                                                <th class="px-4 py-3 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider min-w-[100px]">Status</th>
                                                <th class="px-4 py-3 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider min-w-[120px]">Last Update</th>
                                                <th class="px-4 py-3 text-center text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider w-32">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-gray-900 divide-y divide-gray-700">
                                            @foreach ($prototypes as $prototype)
                                            <tr class="hover:bg-gray-800 transition-colors">
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-400">
                                                    {{ ($prototypes->currentPage() - 1) * $prototypes->perPage() + $loop->iteration }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm leading-5 text-green-400">{{ $prototype->codename }}</div>
                                                    <div class="text-xs leading-5 text-gray-400">{{ $prototype->name }}</div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm leading-5 text-gray-300">
                                                    {{ $prototype->user->name }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm leading-5 text-gray-300">
                                                    {{ $prototype->project_type }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-cyan-900 text-cyan-300">
                                                        {{ $prototype->status }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm leading-5 text-gray-400">
                                                    {{ $prototype->updated_at->format('Y-m-d H:i') }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                    <div class="flex justify-center gap-1">
                                                        <a href="{{ route('prototypes.show', $prototype) }}" class="text-indigo-400 hover:text-indigo-600 transition text-xs px-1">View</a>
                                                        <button type="button" @click="openEditModal({{ json_encode($prototype) }})" class="text-yellow-400 hover:text-yellow-600 transition appearance-none bg-transparent border-none cursor-pointer text-xs mr-1">Edit</button>
                                                        <button type="button" @click="$dispatch('open-delete-modal', { actionUrl: '{{ route('prototypes.destroy', $prototype) }}', itemName: '{{ $prototype->codename }}' })" class="text-red-400 hover:text-red-600 transition appearance-none bg-transparent border-none cursor-pointer text-xs">Delete</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- [UI MOBILE BARU] Tampilan Kartu untuk Mobile --}}
                        <div class="sm:hidden font-mono space-y-4">
                            @foreach ($prototypes as $prototype)
                            <div class="bg-gray-800 border border-gray-700 rounded-lg p-4">
                                {{-- Card Header --}}
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1 min-w-0">
                                        {{-- [BARU] Penomoran di mobile --}}
                                        <div class="text-sm text-gray-500 mb-1">#{{ ($prototypes->currentPage() - 1) *
                                            $prototypes->perPage() + $loop->iteration }}</div>
                                        <div class="text-lg leading-5 text-green-400 font-bold truncate">{{ $prototype->codename
                                            }}</div>
                                        <div class="text-xs leading-5 text-gray-400 truncate">{{ $prototype->name }}</div>
                                    </div>
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-cyan-900 text-cyan-300 flex-shrink-0 ml-2">
                                        {{ $prototype->status }}
                                    </span>
                                </div>

                                {{-- Card Body --}}
                                <div class="text-sm space-y-2 text-gray-300 border-t border-gray-700 pt-3">
                                    {{-- [BARU] Project By di mobile --}}
                                    <p class="flex"><span class="text-gray-500 w-24 flex-shrink-0">PROJECT BY</span>: <span class="truncate ml-1">{{
                                        $prototype->user->name }}</span></p>
                                    <p class="flex"><span class="text-gray-500 w-24 flex-shrink-0">TYPE</span>: <span class="truncate ml-1">{{
                                        $prototype->project_type }}</span></p>
                                    <p class="flex"><span class="text-gray-500 w-24 flex-shrink-0">UPDATED</span>: <span class="truncate ml-1">{{
                                        $prototype->updated_at->format('Y-m-d H:i') }}</span></p>
                                </div>

                                {{-- Card Footer --}}
                                <div class="flex justify-end items-center gap-4 mt-4 border-t border-gray-700 pt-3">
                                    <a href="{{ route('prototypes.show', $prototype) }}"
                                        class="text-indigo-400 hover:text-indigo-600 transition text-sm font-semibold">VIEW</a>
                                    <button type="button" @click="openEditModal({{ json_encode($prototype) }})"
                                        class="text-yellow-400 hover:text-yellow-600 transition text-sm font-semibold">EDIT</button>
                                    <button type="button"
                                        @click="$dispatch('open-delete-modal', { actionUrl: '{{ route('prototypes.destroy', $prototype) }}', itemName: '{{ $prototype->codename }}' })"
                                        class="text-red-400 hover:text-red-600 transition appearance-none bg-transparent border-none p-0 cursor-pointer text-sm font-semibold">
                                        DELETE
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $prototypes->withQueryString()->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- The modal component remains unchanged --}}
        <x-prototype-form-modal />

        <x-confirmation-modal />
    </div>
</x-app-layout>