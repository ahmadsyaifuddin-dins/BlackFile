@props([
    'name',
    'label' => null,
    'id' => null,
    'rows' => 4,
    'placeholder' => '',
    'value' => '',
    'variant' => 'default', // Opsi: 'default', 'danger'
])

@php
    $id = $id ?? $name;
    
    // Tentukan class berdasarkan variant
    $isDanger = $variant === 'danger';
    
    $inputClasses = $isDanger 
        ? 'form-control form-danger' 
        : 'form-control';
        
    $labelClasses = $isDanger 
        ? 'label-danger' 
        : 'text-primary';
@endphp

<div class="w-full">
    @if($label)
        <label for="{{ $id }}" class="block mb-1 font-mono {{ $labelClasses }}">
            {{ $label }}
        </label>
    @endif

    <textarea 
        name="{{ $name }}" 
        id="{{ $id }}" 
        rows="{{ $rows }}" 
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => $inputClasses]) }}
    >{{ $value }}</textarea>
</div>