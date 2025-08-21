<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'BLACKFILE // SECURE SYSTEM' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('app-icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite('resources/js/pages/friends-index.js')

</head>
@props(['title', 'theme' => 'default']) {{-- [BARU] Menerima prop 'theme' dengan nilai default --}}

<body class="font-mono antialiased text-secondary bg-base @if($theme === 'terminal') theme-terminal @endif"
    data-theme="{{ session('theme', 'default') }}">

    {{-- [BARU] Div untuk efek scanline hanya muncul jika tema adalah 'terminal' --}}
    @if($theme === 'terminal')
    <div class="fixed top-0 left-0 h-full w-full scan-overlay pointer-events-none z-0"></div>
    @endif

    <div class="relative z-10">
        {{ $slot }}
    </div>
    @stack('scripts')
</body>

</html>