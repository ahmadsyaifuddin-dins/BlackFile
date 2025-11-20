<x-app-layout title="OSINT ARSENAL // BLACKFILE">
    <div class="flex flex-col h-screen">

        <div
            class="px-8 py-6 border-b border-border-color bg-surface/50 backdrop-blur-sm flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold tracking-wider text-primary uppercase">
                    <i class="fa-solid fa-toolbox mr-3"></i>OSINT Arsenal
                </h1>
                <p class="text-secondary text-sm mt-1 font-mono">
                    // OPEN SOURCE INTELLIGENCE GATHERING MODULES
                </p>
            </div>
            <div class="text-right hidden md:block">
                <div class="text-xs text-secondary">SYSTEM STATUS</div>
                <div class="text-primary font-bold animate-pulse">ONLINE // SECURE</div>
            </div>
        </div>


        <div class="flex-1 overflow-y-auto p-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <a href="{{ route('tools.username') }}" class="group relative block h-full">
                    <div
                        class="absolute inset-0 border border-border-color bg-surface transition-all duration-300 group-hover:border-primary group-hover:shadow-[0_0_15px_rgba(46,160,67,0.15)]">
                    </div>
                    <div class="relative p-6 flex flex-col h-full">
                        <div class="flex justify-between items-start mb-4">
                            <div
                                class="p-3 bg-base border border-border-color rounded group-hover:text-primary group-hover:border-primary transition-colors">
                                <i class="fa-solid fa-user-secret text-2xl"></i>
                            </div>
                            <span class="px-2 py-1 text-[10px] font-bold border border-primary text-primary uppercase">
                                RECON
                            </span>
                        </div>

                        <h3 class="text-xl font-bold text-gray-200 mb-2 group-hover:text-primary transition-colors">
                            Identity Seeker
                        </h3>

                        <p class="text-secondary text-sm mb-6 flex-grow">
                            Melacak keberadaan target berdasarkan <span class="text-gray-400">Username</span> di 50+
                            platform media sosial populer secara real-time.
                        </p>

                        <div
                            class="mt-auto pt-4 border-t border-dashed border-gray-700 flex items-center justify-between text-xs">
                            <span class="text-secondary group-hover:text-primary">./launch_module.sh</span>
                            <i
                                class="fa-solid fa-arrow-right -rotate-45 group-hover:rotate-0 transition-transform duration-300 text-primary"></i>
                        </div>
                    </div>
                </a>

                <a href="{{ route('tools.comment') }}" class="group relative block h-full">
                    <div
                        class="absolute inset-0 border border-border-color bg-surface transition-all duration-300 group-hover:border-amber-500 group-hover:shadow-[0_0_15px_rgba(245,158,11,0.15)]">
                    </div>
                    <div class="relative p-6 flex flex-col h-full">
                        <div class="flex justify-between items-start mb-4">
                            <div
                                class="p-3 bg-base border border-border-color rounded group-hover:text-amber-500 group-hover:border-amber-500 transition-colors">
                                <i class="fa-solid fa-comments text-2xl"></i>
                            </div>
                            <span
                                class="px-2 py-1 text-[10px] font-bold border border-amber-500 text-amber-500 uppercase">
                                ANALYTICS
                            </span>
                        </div>

                        <h3 class="text-xl font-bold text-gray-200 mb-2 group-hover:text-amber-500 transition-colors">
                            Narrative Radar
                        </h3>

                        <p class="text-secondary text-sm mb-6 flex-grow">
                            Analisis sentimen komentar masal. Filter provokator, bot, atau keyword spesifik (e.g "antek
                            asing") dari data log komentar.
                        </p>

                        <div
                            class="mt-auto pt-4 border-t border-dashed border-gray-700 flex items-center justify-between text-xs">
                            <span class="text-secondary group-hover:text-amber-500">./launch_module.sh</span>
                            <i
                                class="fa-solid fa-arrow-right -rotate-45 group-hover:rotate-0 transition-transform duration-300 text-amber-500"></i>
                        </div>
                    </div>
                </a>

                <a href="{{ route('tools.exif') }}" class="group relative block h-full">
                    <div
                        class="absolute inset-0 border border-border-color bg-surface transition-all duration-300 group-hover:border-cyan-500 group-hover:shadow-[0_0_15px_rgba(6,182,212,0.15)]">
                    </div>
                    <div class="relative p-6 flex flex-col h-full">
                        <div class="flex justify-between items-start mb-4">
                            <div
                                class="p-3 bg-base border border-border-color rounded group-hover:text-cyan-500 group-hover:border-cyan-500 transition-colors">
                                <i class="fa-solid fa-file-image text-2xl"></i>
                            </div>
                            <span
                                class="px-2 py-1 text-[10px] font-bold border border-cyan-500 text-cyan-500 uppercase">
                                FORENSIC
                            </span>
                        </div>

                        <h3 class="text-xl font-bold text-gray-200 mb-2 group-hover:text-cyan-500 transition-colors">
                            Meta-Data Extractor
                        </h3>

                        <p class="text-secondary text-sm mb-6 flex-grow">
                            Ekstrak data tersembunyi dari gambar (EXIF). Dapatkan kordinat GPS, jenis device, dan
                            timestamp asli pengambilan foto.
                        </p>

                        <div
                            class="mt-auto pt-4 border-t border-dashed border-gray-700 flex items-center justify-between text-xs">
                            <span class="text-secondary group-hover:text-cyan-500">./launch_module.sh</span>
                            <i
                                class="fa-solid fa-arrow-right -rotate-45 group-hover:rotate-0 transition-transform duration-300 text-cyan-500"></i>
                        </div>
                    </div>
                </a>

                <div class="relative block h-full opacity-50 cursor-not-allowed">
                    <div class="absolute inset-0 border border-border-color border-dashed bg-transparent"></div>
                    <div class="relative p-6 flex flex-col h-full items-center justify-center text-center">
                        <i class="fa-solid fa-lock text-3xl text-secondary mb-3"></i>
                        <h3 class="text-lg font-bold text-secondary uppercase">Module Locked</h3>
                        <p class="text-xs text-gray-600 mt-1">Requires Higher Clearance</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
