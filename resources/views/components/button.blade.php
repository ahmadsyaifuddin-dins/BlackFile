@props([
    'variant' => 'primary', // default 'primary', bisa 'outline', 'secondary'
    'size' => null, // bisa 'sm'
    'href' => null, // Jika diisi, akan menjadi <a>, jika tidak, <button>
])

@php
    $baseClass = 'btn';
    
    $variantClass = match ($variant) {
        'outline' => 'btn-outline',
        'secondary' => 'btn-secondary',
        default => 'btn-primary',
    };

    $sizeClass = match ($size) {
        'sm' => 'btn-sm',
        default => '',
    };

    $classes = "$baseClass $variantClass $sizeClass";

    // Jika 'type' tidak disediakan, default ke 'button' untuk <button>
    // dan 'null' untuk <a>
    $defaultType = $href ? null : 'button';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes, 'type' => $defaultType]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes, 'type' => $defaultType]) }}>
        {{ $slot }}
    </button>
@endif