<x-app-layout title="BlackFile Codex">
    {{-- Header Halaman --}}
    <div class="border-y-2 border-dashed border-primary/50 py-4 mb-8">
        <h1 class="text-4xl font-bold text-primary tracking-widest font-mono text-glow">
            > BLACKFILE CODEX
        </h1>
        <p class="text-sm text-secondary font-mono mt-1">A centralized glossary of classified terminology.</p>
    </div>

    {{-- Konten Codex --}}
    <div class="space-y-10 font-mono">
        {{-- Loop untuk setiap kategori --}}
        @forelse($groupedTerms as $category => $terms)
            <section>
                {{-- Judul Kategori --}}
                <h2 class="text-2xl font-bold text-primary border-b-2 border-border-color pb-2 mb-4">
                    // {{ strtoupper($category) }}
                </h2>

                {{-- Daftar Istilah dalam Kategori --}}
                <div class="space-y-6">
                    @foreach($terms as $term)
                        <article>
                            <h3 class="text-lg font-bold text-white">{{ $term['term'] }}</h3>
                            <p class="mt-1 text-secondary leading-relaxed">
                                {{ $term['definition'] }}
                            </p>
                        </article>
                    @endforeach
                </div>
            </section>
        @empty
            <div class="text-center py-12 border-2 border-dashed border-border-color">
                <p class="text-secondary font-mono text-lg">[ NO CODEX ENTRIES FOUND IN CONFIGURATION ]</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
