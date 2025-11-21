@props(['side', 'color', 'label', 'modelId', 'modelData', 'entities', 'chartId'])

{{-- Tentukan Warna Border & Text berdasarkan props color --}}
@php
    $borderColor = $color === 'green' ? 'border-green-500' : 'border-red-500';
    $textColor = $color === 'green' ? 'text-primary' : 'text-red-500'; // Asumsi text-primary = green di tailwind config
    $ringColor = $color === 'green' ? 'focus:ring-primary' : 'focus:ring-red-500';
    $inputBorder = $color === 'green' ? 'focus:border-primary' : 'focus:border-red-500';
@endphp

<div
    class="bg-surface/80 border-2 {{ $color === 'green' ? 'border-green-500/30' : 'border-red-900/50' }} p-1 relative group hover:{{ $borderColor }} transition-all duration-500 h-full">
    {{-- Label Pojok --}}
    <div
        class="absolute -top-3 {{ $side === 'left' ? 'left-4' : 'right-4' }} bg-gray-900 px-2 text-xs {{ $textColor }} font-bold">
        {{ $label }}
    </div>

    {{-- Dropdown --}}
    <div class="p-4 {{ $side === 'right' ? 'text-right' : '' }}">
        <label class="text-xs text-gray-400 mb-1 block">SELECT ENTITY</label>
        <select x-model="{{ $modelId }}" @change="updatePreview('{{ $modelData }}')"
            class="w-full bg-gray-900 border border-gray-700 text-white p-2 rounded {{ $inputBorder }} {{ $ringColor }} text-sm {{ $side === 'right' ? 'text-right' : '' }}">
            <option value="">-- [NULL] --</option>
            @foreach ($entities as $ent)
                <option value="{{ $ent->id }}">
                    {{ $ent->name }} [{{ $ent->power_tier }}]
                </option>
            @endforeach
        </select>
    </div>

    {{-- Visual Card --}}
    <div
        class="relative aspect-[3/4] bg-black m-4 border border-gray-800 overflow-hidden flex items-center justify-center">
        {{-- Image --}}
        <template x-if="{{ $modelData }}.image">
            <img :src="{{ $modelData }}.image"
                class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity duration-500 {{ $color === 'red' ? 'grayscale group-hover:grayscale-0' : '' }}">
        </template>

        {{-- No Data Placeholder --}}
        <template x-if="!{{ $modelData }}.image">
            <div class="text-center opacity-30">
                <span class="text-xs tracking-widest block">NO VISUAL DATA</span>
            </div>
        </template>

        {{-- Tier Badge --}}
        <div x-show="{{ $modelData }}.tier"
            class="absolute top-2 {{ $side === 'left' ? 'right-2' : 'left-2' }} bg-black/80 border {{ $borderColor }} {{ $textColor }} px-2 py-1 text-xs font-bold backdrop-blur-sm">
            TIER <span x-text="{{ $modelData }}.tier"></span>
        </div>

        {{-- Name & Type Overlay --}}
        <div
            class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black to-transparent p-4 pt-12 {{ $side === 'right' ? 'text-right' : '' }}">
            <h3 class="text-xl font-bold text-white uppercase truncate" x-text="{{ $modelData }}.name || 'UNKNOWN'">
            </h3>
            <p class="text-xs {{ $textColor }}" x-text="{{ $modelData }}.type || 'N/A'"></p>
        </div>
    </div>

    {{-- Chart Canvas --}}
    <div class="px-4 pb-4" x-show="{{ $modelData }}.name">
        <div class="stat-radar-container">
            <canvas id="{{ $chartId }}"></canvas>
        </div>
    </div>
</div>
