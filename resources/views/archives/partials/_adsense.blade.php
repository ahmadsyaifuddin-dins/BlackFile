{{-- Google AdSense ad unit (responsive auto) --}}
@php
    $adEnabled = config('blackfile.adsense.enabled');
    $adClient = config('blackfile.adsense.client', '');
    $adSlot = config('blackfile.adsense.slot', '');
@endphp

@if ($adEnabled && $adClient !== '' && $adSlot !== '')
    <ins class="adsbygoogle"
        style="display:block"
        data-ad-client="{{ $adClient }}"
        data-ad-slot="{{ $adSlot }}"
        data-ad-format="auto"
        data-full-width-responsive="true"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
@endif