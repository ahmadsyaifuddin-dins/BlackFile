@props([
    'category' => null,
    'type' => 'in',
    'class' => 'h-4 w-4 shrink-0',
])

@php
    $icons = $type === 'out' ? config('finance.expense_categories') : config('finance.income_categories');
    $icon = ($category && isset($icons[$category])) ? $icons[$category] : config('finance.fallback');
    $icon = preg_replace('/<svg/', '<svg' . ($class ? ' class="' . $class . '"' : ''), $icon, 1);
@endphp

{!! $icon !!}