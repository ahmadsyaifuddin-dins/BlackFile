{{-- Google AdSense loader — letakkan di <head> bila iklan diaktifkan --}}
@php($adClient = config('blackfile.adsense.client', ''))
@if (config('blackfile.adsense.enabled') && $adClient !== '')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $adClient }}"
        crossorigin="anonymous"></script>
@endif