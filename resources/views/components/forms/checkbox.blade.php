@props([
    'id' => null,
])

@php
    // Membuat ID unik jika tidak disediakan, penting untuk 'for' pada label
    $id = $id ?? 'checkbox-' . Str::uuid();
@endphp

<div class="flex items-center">
    <input type="checkbox" id="{{ $id }}"
        {{ $attributes->merge(['class' => 'form-checkbox-themed']) }}>

    {{-- Tampilkan label jika slot tidak kosong --}}
    @if(!$slot->isEmpty())
        <label for="{{ $id }}" class="ml-2 text-sm text-[var(--color-text-default)] select-none cursor-pointer">
            {{ $slot }}
        </label>
    @endif
</div>