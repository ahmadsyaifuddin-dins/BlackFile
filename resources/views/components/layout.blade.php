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

<body class="font-inconsolata antialiased text-secondary bg-base">
    <div class="fixed top-0 left-0 h-full w-full scan-overlay pointer-events-none"></div>

    {{ $slot }}
    
    @stack('scripts')
</body>

</html>