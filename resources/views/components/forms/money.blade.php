@props([
    'name',
    'id' => null,
    'value' => '',
    'placeholder' => '0',
])

@php
    $id = $id ?? $name;
    $initial = ($value === '' || $value === null) ? '' : number_format((float) $value, 0, ',', '.');
@endphp

<div class="relative w-full"
    x-data="{
        display: '{{ $initial }}',
        format(value) {
            const digits = String(value).replace(/[^0-9]/g, '');
            return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        },
        update(event) {
            this.display = this.format(event.target.value);
            event.target.value = this.display;
        },
    }">
    <input type="text" inputmode="numeric" autocomplete="off" id="{{ $id }}"
        value="{{ $initial }}" x-on:input="update($event)" placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'form-control pr-12 text-right']) }}>

    <input type="hidden" name="{{ $name }}" x-bind:value="display.replace(/\./g, '')">

    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-secondary text-xs">
        IDR
    </span>
</div>
