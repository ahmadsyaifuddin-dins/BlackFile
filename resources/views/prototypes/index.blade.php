<x-app-layout>

    <div x-data="prototypesCRUD" x-init="init()">

        <x-slot:title>Prototypes Project</x-slot:title>

        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Prototypes Project') }}
            </h2>
        </x-slot>

        <div class="py-1">
            <div class="w-full sm:px-3 lg:px-3">
                <div class="flex justify-end mb-4">
                    <x-button variant="outline" @click="openCreateModal()">
                        [ + FILE NEW PROTOTYPE ]
                    </x-button>
                </div>

                @if(session('success'))
                <div class="mb-4 bg-primary/10 border-l-4 border-primary text-primary-hover p-4 rounded-r-lg"
                    role="alert">
                    <p>{{ session('success') }}</p>
                </div>
                @endif

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

                <x-prototypes.filter-section :projectTypes="$projectTypes" :users="$users" />

                <div class="bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-2 sm:p-6 bg-gray-900 border-b border-gray-700">
                        @if($prototypes->isEmpty())
                        <div class="text-center py-10 font-mono text-gray-500">
                            <p>// NO PROTOTYPE FOUND IN THE ARCHIVE //</p>
                            <p class="mt-4">Click "[ + FILE NEW PROTOTYPE ]" to begin cataloging your work.</p>
                        </div>
                        @else
                        <div class="hidden sm:block">
                            {{-- Table container with better responsive design --}}
                            <div class="rounded-lg border border-gray-700 overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full font-mono divide-y divide-gray-700 table-fixed">
                                        <thead class="bg-gray-800">
                                            <tr>
                                                <th
                                                    class="px-3 py-3 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider w-12">
                                                    #</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider w-16">
                                                    Icon</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider w-80">
                                                    Codename</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider w-40">
                                                    Develop By</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider w-32">
                                                    Project Type</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider w-24">
                                                    Status</th>
                                                <th
                                                    class="px-4 py-3 text-center text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider w-32">
                                                    Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-gray-900 divide-y divide-gray-700">
                                            @foreach ($prototypes as $prototype)
                                            <tr class="hover:bg-gray-800 transition-colors">
                                                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-400">
                                                    {{ ($prototypes->currentPage() - 1) * $prototypes->perPage() +
                                                    $loop->iteration }}
                                                </td>
                                                {{-- Icon column with consistent sizing --}}
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        @if($prototype->icon_path)
                                                        <img class="h-10 w-10 rounded-md object-cover"
                                                            src="{{ asset($prototype->icon_path) }}"
                                                            alt="{{ $prototype->codename }} icon">
                                                        @else
                                                        {{-- Placeholder jika tidak ada ikon --}}
                                                        <div
                                                            class="h-10 w-10 rounded-md bg-gray-700 flex items-center justify-center text-primary font-bold text-lg">
                                                            {{ substr($prototype->name, 0, 1) }}
                                                        </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                {{-- Codename column with text truncation --}}
                                                <td class="px-4 py-4">
                                                    <div class="text-sm leading-5 text-primary font-semibold truncate"
                                                        title="{{ $prototype->codename }}">
                                                        {{ $prototype->codename }}
                                                    </div>
                                                    <div class="text-xs leading-5 text-gray-400 truncate"
                                                        title="{{ $prototype->name }}">
                                                        {{ Str::limit($prototype->name, 25, '...') }}
                                                    </div>
                                                </td>
                                                {{-- Developer column with truncation --}}
                                                <td class="px-4 py-4 whitespace-nowrap text-sm leading-5 text-gray-300">
                                                    <div class="truncate" title="{{ $prototype->user->name }}">
                                                        {{ Str::limit($prototype->user->name, 20, '...') }}
                                                    </div>
                                                </td>
                                                {{-- Project type with truncation --}}
                                                <td class="px-4 py-4 whitespace-nowrap text-sm leading-5 text-gray-300">
                                                    <div class="truncate" title="{{ $prototype->project_type }}">
                                                        {{ Str::limit($prototype->project_type, 20, '...') }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-cyan-900 text-cyan-300">
                                                        {{ $prototype->status }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                    <div class="flex justify-center gap-1">

                                                        {{-- View Button --}}
                                                        {{-- Menggunakan variant 'text' custom untuk tabel --}}
                                                        <a href="{{ route('prototypes.show', $prototype) }}"
                                                            class="text-indigo-400 hover:text-indigo-300 transition text-xs px-1 font-bold font-mono">
                                                            VIEW
                                                        </a>

                                                        {{-- Edit Button (Trigger AlpineJS) --}}
                                                        <button type="button"
                                                            @click="openEditModal({{ json_encode($prototype) }})"
                                                            class="text-yellow-500 hover:text-yellow-400 transition bg-transparent border-none cursor-pointer text-xs font-bold font-mono mr-1">
                                                            EDIT
                                                        </button>

                                                        {{-- Delete Button (Using x-button.delete) --}}
                                                        <x-button.delete
                                                            :action="route('prototypes.destroy', $prototype)"
                                                            title="SCRAP PROTOTYPE?"
                                                            message="Confirm deletion of prototype {{ $prototype->codename }}? All associated data will be purged."
                                                            target="{{ $prototype->codename }}" class="text-xs">
                                                            DELETE
                                                        </x-button.delete>

                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Professional Mobile Card UI --}}
                        <div class="sm:hidden space-y-3">
                            @foreach ($prototypes as $prototype)
                            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden shadow-lg">
                                {{-- Card Header with Gradient Background --}}
                                <div
                                    class="bg-gradient-to-r from-gray-800 to-gray-750 px-4 py-3 border-b border-gray-600">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            {{-- Icon with better styling --}}
                                            <div class="flex-shrink-0">
                                                @if($prototype->icon_path)
                                                <img class="h-10 w-10 rounded-lg object-cover ring-2 ring-primary/20"
                                                    src="{{ asset($prototype->icon_path) }}"
                                                    alt="{{ $prototype->codename }} icon">
                                                @else
                                                <div
                                                    class="h-10 w-10 rounded-lg bg-gradient-to-br from-primary to-primary-hover flex items-center justify-center text-white font-bold text-sm shadow-md">
                                                    {{ strtoupper(substr($prototype->codename, 0, 2)) }}
                                                </div>
                                                @endif
                                            </div>

                                            {{-- Project Number --}}
                                            <div class="text-xs text-gray-400 font-mono bg-gray-700 px-2 py-1 rounded">
                                                #{{ ($prototypes->currentPage() - 1) * $prototypes->perPage() +
                                                $loop->iteration }}
                                            </div>
                                        </div>

                                        {{-- Status Badge --}}
                                        <span
                                            class="px-3 py-1 text-xs font-bold rounded-full bg-cyan-500 text-cyan-50 shadow-sm">
                                            {{ strtoupper($prototype->status) }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Main Content --}}
                                <div class="px-4 py-4">
                                    {{-- Project Title Section --}}
                                    <div class="mb-4">
                                        <h3 class="text-lg font-bold text-primary leading-snug mb-2">
                                            {{ $prototype->codename }}
                                        </h3>
                                        <p class="text-sm text-gray-300 leading-relaxed line-clamp-2">
                                            {{ $prototype->name }}
                                        </p>
                                    </div>

                                    {{-- Project Details Grid --}}
                                    <div class="grid grid-cols-2 gap-3 mb-4">
                                        <div class="bg-gray-750 rounded-lg p-3">
                                            <div
                                                class="text-xs text-gray-400 uppercase tracking-wide font-semibold mb-1">
                                                Developer
                                            </div>
                                            <div class="text-sm text-gray-200 font-medium truncate">
                                                {{ $prototype->user->name }}
                                            </div>
                                        </div>

                                        <div class="bg-gray-750 rounded-lg p-3">
                                            <div
                                                class="text-xs text-gray-400 uppercase tracking-wide font-semibold mb-1">
                                                Type
                                            </div>
                                            <div class="text-sm text-gray-200 font-medium truncate">
                                                {{ $prototype->project_type }}
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Last Updated --}}
                                    <div class="bg-gray-750 rounded-lg p-3 mb-4">
                                        <div class="text-xs text-gray-400 uppercase tracking-wide font-semibold mb-1">
                                            Last Updated
                                        </div>
                                        <div class="text-sm text-gray-200 font-mono">
                                            {{ $prototype->updated_at->format('M d, Y • H:i') }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="bg-gray-750 px-4 py-3 border-t border-gray-600">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('prototypes.show', $prototype) }}"
                                            class="inline-flex items-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            VIEW
                                        </a>

                                        <button type="button" @click="openEditModal({{ json_encode($prototype) }})"
                                            class="inline-flex items-center px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                            EDIT
                                        </button>

                                        <x-button.delete :action="route('prototypes.destroy', $prototype)"
                                            title="SCRAP PROTOTYPE?"
                                            icon=true
                                            message="Confirm deletion of prototype {{ $prototype->codename }}?"
                                            target="{{ $prototype->codename }}"
                                            class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                            DELETE
                                        </x-button.delete>
                                    </div>
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
        <x-prototype-form-modal :show-errors="$errors->any()" />

    </div>
</x-app-layout>