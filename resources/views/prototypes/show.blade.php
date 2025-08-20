<x-app-layout>
    <x-slot:title>
        Prototipe: {{ $prototype->codename }}
    </x-slot:title>

    <x-slot name="header">

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-3">
                <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                    <span class="text-gray-500">Prototipe //</span> {{ $prototype->codename }}
                </h2>
                <a href="{{ route('prototypes.index') }}"
                    class="text-gray-400 hover:text-white transition duration-300 font-mono text-sm">
                    &lt;-- [ RETURN TO PROJECTS ]
                </a>
            </div>
            <div class="bg-gray-900 border border-gray-700 shadow-sm sm:rounded-lg overflow-hidden">
                {{-- Bagian Gambar Sampul (jika ada) --}}
                @if($prototype->cover_image_path)
                {{-- Menggunakan aspect ratio 16:9 --}}
                <div class="aspect-w-16 aspect-h-9 bg-black">
                    <img src="{{ asset($prototype->cover_image_path) }}" alt="Cover image for {{ $prototype->name }}"
                        class="w-full h-full object-cover object-center">
                </div>
                @endif

                <div class="p-6 md:p-8 font-mono grid grid-cols-1 md:grid-cols-3 gap-8">

                    {{-- Kolom Kiri: Deskripsi & Briefing --}}
                    <div class="md:col-span-2 text-gray-300">
                        <h3 class="text-2xl text-green-400 mb-2">[ {{ $prototype->name }} ]</h3>

                        <div class="prose prose-invert prose-sm max-w-none text-gray-400">
                            <p>
                                {{ $prototype->description }}
                            </p>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-700">
                            <h4 class="text-lg text-cyan-400 mb-3">> TECH ARSENAL</h4>
                            <div class="flex flex-wrap gap-2">
                                @forelse($prototype->tech_stack as $tech)
                                <span
                                    class="bg-gray-700 text-gray-300 text-xs font-semibold px-2.5 py-1 rounded-full">{{
                                    $tech }}</span>
                                @empty
                                <span class="text-gray-500 text-sm">// No tech stack specified</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Data Sheet --}}
                    <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 h-fit">
                        <h4 class="text-lg text-cyan-400 mb-3 border-b border-gray-600 pb-2">> DATA SHEET</h4>
                        <div class="space-y-3 text-sm">
                            <div>
                                <p class="text-gray-500">> STATUS</p>
                                <p class="text-yellow-400 font-semibold">{{ $prototype->status }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">> PROJECT BY</p>
                                <p class="text-green-600 font-semibold">{{ $prototype->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">> PROJECT TYPE</p>
                                <p>{{ $prototype->project_type }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">> DEPLOYMENT START</p>
                                <p>{{ $prototype->start_date ? $prototype->start_date->locale('id')->translatedFormat('d
                                    M Y, H:i') : 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">> DEPLOYMENT END</p>
                                <p>{{ $prototype->completed_date ?
                                    $prototype->completed_date->locale('id')->translatedFormat('d M Y, H:i') : 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500">> CODE VAULT</p>
                                @if($prototype->repository_url)
                                <a href="{{ $prototype->repository_url }}" target="_blank"
                                    class="text-indigo-400 hover:underline break-all">
                                    ACCESS REPOSITORY &rarr;
                                </a>
                                @else
                                <p>N/A</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-gray-500">> LIVE DEPLOYMENT</p>
                                @if($prototype->live_url)
                                <a href="{{ $prototype->live_url }}" target="_blank"
                                    class="text-indigo-400 hover:underline break-all">
                                    ACCESS LIVE URL &rarr;
                                </a>
                                @else
                                <p>N/A</p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>