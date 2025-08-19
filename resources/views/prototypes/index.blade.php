<x-app-layout>
    {{--
    [MODULAR]
    The giant x-data object is now gone.
    We now call the 'prototypesCRUD' component we registered in app.js.
    --}}
    <div x-data="prototypesCRUD" x-init="init()">
        <x-slot:title>Prototypes Project</x-slot:title>

        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Prototypes Project') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-end mb-4">
                    <button @click="openCreateModal()"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300 w-full sm:w-auto cursor-pointer">
                        [ + FILE NEW PROTOTYPE ]
                    </button>
                </div>

                @if (session('success'))
                <div class="bg-green-800 border border-green-600 text-green-200 px-4 py-3 rounded relative mb-4"
                    role="alert">
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
                        {{-- Tampilan Tabel untuk Desktop --}}
                        <table class="min-w-full font-mono hidden sm:table">
                            <thead class="sm:table-header-group">
                                <tr>
                                    <th
                                        class="px-6 py-3 border-b-2 border-gray-700 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider">
                                        Codename</th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-gray-700 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider">
                                        Project Type</th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-gray-700 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-gray-700 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider">
                                        Last Update</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-700"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800">
                                @foreach ($prototypes as $prototype)
                                <tr class="sm:table-row">
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-700">
                                        <div class="text-sm leading-5 text-green-400">{{ $prototype->codename }}</div>
                                        <div class="text-xs leading-5 text-gray-400">{{ $prototype->name }}</div>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap border-b border-gray-700 text-sm leading-5 text-gray-300">
                                        {{ $prototype->project_type }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-700">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-cyan-900 text-cyan-300">
                                            {{ $prototype->status }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap border-b border-gray-700 text-sm leading-5 text-gray-400">
                                        {{ $prototype->updated_at->format('Y-m-d H:i') }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-700 text-sm font-medium">
                                        <a href="{{ route('prototypes.show', $prototype) }}"
                                            class="text-indigo-400 hover:text-indigo-600 transition">View</a>
                                        <button type="button" @click="openEditModal({{ json_encode($prototype) }})"
                                            class="ml-4 text-yellow-400 hover:text-yellow-600 transition appearance-none bg-transparent border-none p-0 cursor-pointer">Edit</button>
                                        <button
                                            @click="openDeleteModal('{{ route('prototypes.destroy', $prototype) }}', '{{ $prototype->codename }}')"
                                            class="ml-4 text-red-400 hover:text-red-600 transition appearance-none bg-transparent border-none p-0 cursor-pointer">Delete</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- [UI MOBILE BARU] Tampilan Kartu untuk Mobile --}}
                        <div class="sm:hidden font-mono space-y-4">
                            @foreach ($prototypes as $prototype)
                            <div class="bg-gray-800 border border-gray-700 rounded-lg p-4">
                                {{-- Card Header --}}
                                <div class="flex justify-between items-center mb-3">
                                    <div>
                                        <div class="text-lg leading-5 text-green-400 font-bold">{{ $prototype->codename
                                            }}</div>
                                        <div class="text-xs leading-5 text-gray-400">{{ $prototype->name }}</div>
                                    </div>
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-cyan-900 text-cyan-300">
                                        {{ $prototype->status }}
                                    </span>
                                </div>

                                {{-- Card Body --}}
                                <div class="text-sm space-y-2 text-gray-300 border-t border-gray-700 pt-3">
                                    <p><span class="text-gray-500">TYPE:</span> {{ $prototype->project_type }}</p>
                                    <p><span class="text-gray-500">LAST UPDATE:</span> {{
                                        $prototype->updated_at->format('Y-m-d H:i') }}</p>
                                </div>

                                {{-- Card Footer --}}
                                <div class="flex justify-end items-center gap-4 mt-4 border-t border-gray-700 pt-3">
                                    <a href="{{ route('prototypes.show', $prototype) }}"
                                        class="text-indigo-400 hover:text-indigo-600 transition text-sm font-semibold">VIEW</a>
                                    <button type="button" @click="openEditModal({{ json_encode($prototype) }})"
                                        class="text-yellow-400 hover:text-yellow-600 transition text-sm font-semibold">EDIT</button>
                                    <button type="button"
                                        @click="openDeleteModal('{{ route('prototypes.destroy', $prototype) }}', '{{ $prototype->codename }}')"
                                        class="ml-4 text-red-400 hover:text-red-600 transition appearance-none bg-transparent border-none p-0 cursor-pointer">
                                        Delete
                                    </button>
                                </div>
                            </div>
                            @endforeach
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

        <x-confirmation-modal />

    </div>
</x-app-layout>