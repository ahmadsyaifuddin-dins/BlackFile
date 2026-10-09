<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>BLACKFILE // {{ $archive->name }}</title>
    @vite(['resources/css/app.css'])
    @include('archives.partials._adsense-loader')
    <style>
        [x-cloak] { display: none !important; }
        .scanlines::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            background: repeating-linear-gradient(
                to bottom,
                transparent 0px,
                transparent 2px,
                rgba(46, 160, 67, 0.06) 3px,
                rgba(46, 160, 67, 0.06) 4px
            );
            z-index: 50;
        }
        @keyframes blinkCursor {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }
        .cursor-blink { animation: blinkCursor 1s step-end infinite; }
        @keyframes glowPulse {
            0%, 100% { text-shadow: 0 0 12px rgba(46,160,67,.55); }
            50% { text-shadow: 0 0 26px rgba(46,160,67,.9); }
        }
        .title-glow { animation: glowPulse 2.4s ease-in-out infinite; }
    </style>
</head>
<body class="bg-base text-text-default min-h-screen scanlines" data-theme="default">

    <div class="max-w-4xl mx-auto px-4 py-8 font-mono">

        {{-- Terminal Header --}}
        <header class="flex items-center justify-between border-b border-green-500/30 pb-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse shadow-[0_0_8px_#2ea043]"></div>
                <span class="text-xs sm:text-sm tracking-[0.25em] text-secondary">BLACKFILE // PUBLIC_CHANNEL</span>
            </div>
            <span class="text-[10px] sm:text-xs uppercase tracking-widest text-green-400/80 border border-green-500/30 px-2 py-1 rounded">
                File_ID: {{ str_pad($archive->id, 4, '0', STR_PAD_LEFT) }}
            </span>
        </header>

        {{-- Title --}}
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-primary title-glow break-words leading-tight">
                {{ $archive->name }}
            </h1>
            <p class="text-xs text-secondary mt-2 tracking-widest">
                &gt; ACCESS: <span class="text-green-400">GRANTED</span>
                <span class="mx-2">//</span> STATUS: <span class="text-green-400">PUBLIC_ACCESS</span>
                <span class="cursor-blink">▊</span>
            </p>
        </div>

        {{-- Preview --}}
        @if ($archive->preview_image_url)
            <div class="overflow-hidden rounded border border-green-500/30 bg-black relative mb-8 group">
                <div class="absolute top-0 left-0 bg-green-500/90 text-black text-[10px] font-bold px-2 py-1 tracking-widest">IMG_PREVIEW</div>
                <img src="{{ $archive->preview_image_url }}" alt="{{ $archive->name }}" loading="lazy"
                    class="w-full h-auto object-cover opacity-85 group-hover:opacity-100 transition-opacity duration-500">
            </div>
        @endif

        {{-- Description --}}
        <section class="bg-surface/60 border border-green-500/30 rounded overflow-hidden mb-8">
            <div class="bg-black/40 px-4 py-2 border-b border-green-500/20 flex items-center justify-between">
                <h2 class="text-xs font-bold uppercase tracking-widest text-secondary">&gt; DESCRIPTION_DATA</h2>
                <div class="flex gap-1">
                    <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                    <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                </div>
            </div>
            <div class="p-5">
                <p class="text-green-100/90 font-sans leading-relaxed whitespace-pre-wrap break-words text-sm sm:text-base">
                    {{ $archive->description ?? '// No description data provided in this entry.' }}
                </p>
            </div>
        </section>

        {{-- Linked Resources (URL type) --}}
        @if ($archive->type === 'url' && count($archive->links ?? []) > 0)
            <section class="bg-surface/60 border border-green-500/30 rounded p-4 mb-8">
                <h3 class="text-xs font-bold uppercase tracking-widest text-secondary mb-3">&gt; LINKED_RESOURCES</h3>
                <ul class="space-y-2">
                    @foreach ($archive->links as $link)
                        <li class="flex items-start gap-3 bg-black/20 p-2 rounded border border-green-500/10">
                            <svg class="h-4 w-4 text-green-400 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            <a href="{{ $link }}" target="_blank" rel="noopener noreferrer"
                                class="text-blue-400 hover:text-blue-300 text-sm flex-grow break-all hover:underline">
                                {{ $link }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        {{-- Metadata --}}
        <section class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
            <div class="bg-surface/60 border border-green-500/30 rounded p-4">
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-[10px] uppercase text-secondary tracking-widest">Category</dt>
                        <dd class="text-green-300 text-right">{{ $archive->category === 'Other' ? $archive->category_other : $archive->category }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-[10px] uppercase text-secondary tracking-widest">Type</dt>
                        <dd class="text-green-300 uppercase">{{ $archive->type }}</dd>
                    </div>
                    @if ($archive->type === 'file')
                        <div class="flex justify-between gap-4">
                            <dt class="text-[10px] uppercase text-secondary tracking-widest">Size</dt>
                            <dd class="text-green-300">{{ \Illuminate\Support\Number::fileSize($archive->size, precision: 2) }}</dd>
                        </div>
                    @endif
                    <div class="flex justify-between gap-4">
                        <dt class="text-[10px] uppercase text-secondary tracking-widest">Date Logged</dt>
                        <dd class="text-green-300 text-right">{{ $archive->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-surface/60 border border-green-500/30 rounded p-4">
                <h3 class="text-[10px] uppercase text-secondary tracking-widest mb-2">Tags</h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($archive->tags as $tag)
                        <span class="px-2 py-0.5 text-[10px] uppercase font-mono rounded bg-black/30 border border-green-500/20 text-green-400">#{{ $tag->name }}</span>
                    @empty
                        <span class="text-xs text-secondary italic opacity-50">No tags attached</span>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- Primary Action --}}
        @if ($archive->type === 'file')
            <div class="mb-8">
                <a href="{{ asset('uploads/' . $archive->file_path) }}" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-green-500/90 text-black font-bold uppercase tracking-widest text-sm rounded hover:bg-green-400 transition-colors shadow-[0_0_20px_rgba(46,160,67,0.35)]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    DOWNLOAD_FILE
                </a>
            </div>
        @endif

        {{-- Ad Unit --}}
        <div class="border border-green-500/30 rounded-lg p-5 mb-8 bg-surface/40">
            <span class="block text-[9px] uppercase tracking-[0.25em] text-secondary/60 mb-3">// AD_SPACE</span>
            @include('archives.partials._adsense', ['label' => 'detail'])
        </div>

        {{-- Footer --}}
        <footer class="border-t border-green-500/20 pt-4 text-center text-[10px] text-secondary/60 uppercase tracking-widest">
            <p>&gt; SECURE PUBLIC CHANNEL // BLACKFILE // {{ now()->year }} &lt;</p>
        </footer>
    </div>

</body>
</html>