@props([
    'action',               // URL tujuan delete
    'title' => null,        // Judul Modal
    'message' => null,      // Pesan Modal
    'target' => null,       // Target (misal nama entitas)
    'icon' => false,        // Opsi tampilkan icon (default: false)
    'variant' => 'text',    // Opsi gaya: 'text' atau 'button'
])

@php
    // 1. Kelas Dasar (selalu ada)
    $baseClasses = 'cursor-pointer transition-all duration-200 font-mono text-sm font-bold';

    // 2. Logika Varian Style
    if ($variant === 'button') {
        // Gaya Tombol (Kotak, ada background/border - Cocok untuk Modal/Halaman Detail)
        $variantClasses = 'w-full sm:w-auto px-4 py-2 bg-red-600/20 border border-red-600/50 text-red-400 hover:bg-red-600 hover:text-white rounded flex items-center justify-center gap-2';
    } else {
        // Gaya Teks (Link biasa - Cocok untuk Tabel/Footer Card)
        $variantClasses = 'text-red-500 hover:text-red-400 whitespace-nowrap inline-flex items-center gap-1';
    }

    // Gabungkan kelas
    $classes = "$baseClasses $variantClasses";
@endphp

<button type="button" 
    @click="$dispatch('open-delete-modal', { 
        url: '{{ $action }}',
        title: '{{ $title }}',
        message: '{{ $message }}',
        target: '{{ $target }}'
    })"
    {{-- $attributes->merge memungkinkan kamu menambah class manual jika perlu --}}
    {{ $attributes->merge(['class' => $classes]) }}>
    
    {{-- Tampilkan Icon HANYA jika prop icon="true" --}}
    @if($icon)
        <svg class="{{ $variant === 'button' ? 'w-4 h-4' : 'w-3 h-3' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
    @endif
    
    {{-- Slot untuk teks tombol (Default: DELETE) --}}
    {{ $slot->isEmpty() ? 'DELETE' : $slot }}
</button>