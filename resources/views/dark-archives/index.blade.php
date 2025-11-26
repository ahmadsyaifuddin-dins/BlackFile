<x-app-layout title="Dark Archives">
    <div class="min-h-screen bg-black text-gray-300 font-mono p-4 md:p-8">

        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div
                class="border-b border-gray-800 pb-6 mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6 md:gap-4">
                <div class="space-y-2">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-100 tracking-widest uppercase glitch-effect">
                        The Dark Archives
                    </h1>
                    <div class="flex items-center gap-2 text-[10px] md:text-xs text-gray-600 font-mono">
                        <span class="w-2 h-2 bg-red-900 rounded-full animate-pulse"></span>
                        <p>SECURE DATABASE // AUTHORIZED PERSONNEL ONLY</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <a href="{{ route('dark-archives.index') }}" target="_blank"
                        class="text-center text-xs border border-gray-700 text-gray-500 px-4 py-2.5 hover:bg-gray-800 hover:text-gray-300 hover:border-gray-500 transition duration-300 group">
                        <span class="group-hover:hidden">[ VIEW PUBLIC SITE ]</span>
                        <span class="hidden group-hover:inline">[ ACCESS GRANTED ]</span>
                    </a>
                    <a href="{{ route('dark-archives.create') }}"
                        class="text-center text-xs border border-green-900 bg-green-900/10 text-green-500 px-4 py-2.5 hover:bg-green-900 hover:text-green-100 transition duration-300 flex items-center justify-center gap-2 shadow-[0_0_10px_rgba(0,255,0,0.1)] hover:shadow-[0_0_15px_rgba(0,255,0,0.2)]">
                        <span class="text-sm font-bold leading-none">+</span>
                        <span>NEW ENTRY</span>
                    </a>
                </div>
            </div>

            <!-- Alerts -->
            @if (session('success'))
                <div class="mb-6 bg-green-900/20 border border-green-900 text-green-400 px-4 py-3 rounded relative"
                    role="alert">
                    <span class="block sm:inline font-mono text-xs">> SYSTEM MSG: {{ session('success') }}</span>
                </div>
            @endif

            <!-- Grid Content -->
            @if ($archives->isEmpty())
                <div class="text-center py-20 border border-dashed border-gray-800 rounded bg-[#0a0a0a]">
                    <p class="text-gray-600 text-sm tracking-widest">[ DATABASE EMPTY - NO RECORDS FOUND ]</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($archives as $archive)
                        <div
                            class="group relative bg-[#0a0a0a] border border-gray-900 hover:border-gray-700 transition duration-300 flex flex-col h-full">

                            <!-- Status Badge (Draft/Public) -->
                            <div class="absolute top-2 right-2 z-10">
                                @if ($archive->status == 'draft')
                                    <span
                                        class="bg-yellow-900/80 text-yellow-200 text-[10px] px-2 py-1 font-bold border border-yellow-700">DRAFT</span>
                                @else
                                    <span
                                        class="bg-green-900/80 text-green-200 text-[10px] px-2 py-1 font-bold border border-green-700">PUBLIC</span>
                                @endif
                            </div>

                            <!-- Thumbnail (Click to View Public) -->
                            <a href="{{ route('dark-archives.show', $archive->slug) }}"
                                class="block h-48 overflow-hidden relative border-b border-gray-900">
                                @if ($archive->thumbnail)
                                    <img src="{{ asset($archive->thumbnail) }}"
                                        class="w-full h-full object-cover grayscale opacity-60 group-hover:opacity-100 group-hover:grayscale-0 transition duration-500">
                                @else
                                    <div class="w-full h-full bg-gray-900 flex items-center justify-center">
                                        <span class="text-gray-700 text-xs">[ NO IMAGE ]</span>
                                    </div>
                                @endif
                            </a>

                            <!-- Content Info -->
                            <div class="p-5 flex-grow">
                                <span
                                    class="text-[10px] text-gray-500 font-bold block mb-1">{{ $archive->case_code }}</span>
                                <h3 class="text-lg font-bold text-gray-200 font-mono leading-tight mb-3 truncate">
                                    {{ $archive->title }}
                                </h3>
                                <div class="text-[10px] text-gray-600 space-y-1 font-mono">
                                    <p>DATE: {{ $archive->formatted_date }}</p>
                                    <p>AUTHOR: {{ $archive->agent->name ?? 'Unknown' }}</p>
                                    <div class="flex gap-3 mt-2 text-gray-500">
                                        <span class="flex items-center"><svg class="w-3 h-3 mr-1" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg> {{ $archive->views }}</span>
                                        <span class="flex items-center"><svg class="w-3 h-3 mr-1" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" />
                                            </svg> {{ $archive->respects }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Footer (Tombol Edit Disini) -->
                            <div
                                class="px-5 py-3 border-t border-gray-900 bg-gray-900/30 flex justify-between items-center">
                                <a href="{{ route('dark-archives.show', $archive->slug) }}"
                                    class="text-[10px] text-gray-500 hover:text-white transition">
                                    [ PREVIEW ]
                                </a>

                                <!-- Pastikan route 'dark-archives.edit' sudah ada di web.php -->
                                <a href="{{ route('dark-archives.edit', $archive->slug) }}"
                                    class="text-[10px] bg-gray-800 hover:bg-blue-900 text-blue-400 hover:text-white px-3 py-1 border border-gray-700 hover:border-blue-500 transition">
                                    // EDIT FILE
                                </a>

                                <button x-data
                                    @click="$dispatch('open-delete-modal', { 
                                    url: '{{ route('dark-archives.destroy', $archive->id) }}',
                                    title: 'PURGE ARCHIVE',
                                    message: 'Are you sure you want to permanently delete case file {{ $archive->case_code }}? This action will wipe all evidence data and cannot be undone.',
                                    target: '{{ Str::limit($archive->title, 30) }}'
                                })"
                                    class="text-[10px] cursor-pointer bg-red-900/20 hover:bg-red-900 text-red-500 hover:text-white px-3 py-1 border border-red-900/50 hover:border-red-500 transition">
                                    [ DELETE ]
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
