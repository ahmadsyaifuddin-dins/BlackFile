@props([
    'name',
    'id' => null,
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'icon' => false, // Opsi untuk menampilkan ikon
])

@php
    $id = $id ?? $name;
    // Padding kiri lebih besar jika ada ikon
    $paddingClass = $icon ? 'pl-10' : 'pl-3'; 
@endphp

<div class="relative w-full">
    @if($icon)
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            {{ $icon }}
        </div>
    @endif

    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $id }}" 
        value="{{ $value }}" 
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => "form-control $paddingClass"]) }}
    >
</div>