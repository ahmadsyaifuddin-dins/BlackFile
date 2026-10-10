@props([
    'name' => 'gender',
    'value' => '',
    'required' => false,
])

@php
    $genders = [
        'male' => ['icon' => 'fa-mars', 'color' => 'text-blue-400', 'labelKey' => 'Male'],
        'female' => ['icon' => 'fa-venus', 'color' => 'text-pink-400', 'labelKey' => 'Female'],
    ];

    $current = old($name, $value ?? '');
@endphp

<div x-data="{ gender: @js($current) }" class="grid grid-cols-2 gap-2 mt-1">
    @foreach ($genders as $key => $meta)
        <label
            class="cursor-pointer flex flex-col items-center gap-1 border-2 px-3 py-2 rounded transition-colors hover:border-primary"
            :class="gender === '{{ $key }}' ? 'border-primary text-primary bg-black/40' : 'border-border-color text-secondary'">
            <input type="radio" name="{{ $name }}" value="{{ $key }}"
                class="sr-only" x-model="gender" @required($required) @checked($current === $key) />
            <i class="fa-solid {{ $meta['icon'] }} {{ $meta['color'] }}"></i>
            <span class="text-xs font-bold tracking-wider" data-i18n="{{ $meta['labelKey'] }}">{{ __($meta['labelKey']) }}</span>
        </label>
    @endforeach
</div>