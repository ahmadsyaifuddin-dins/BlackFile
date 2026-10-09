{{-- Adsterra (Social Bar) --}}
@php
    $adEnabled = config('blackfile.adsterra.enabled', false);
    $scriptUrl = config('blackfile.adsterra.script_url', '');
@endphp

@if ($adEnabled && $scriptUrl !== '')
    <script data-cfasync="false" src="{{ $scriptUrl }}"></script>
@endif