{{-- Google AdSense ad unit (responsive auto) + debug console --}}
@props(['label' => 'public'])

@php
    $adEnabled = config('blackfile.adsense.enabled');
    $adClient = config('blackfile.adsense.client', '');
    $adSlot = config('blackfile.adsense.slot', '');
    $adDebug = config('blackfile.adsense.debug', false);
@endphp

@if ($adEnabled && $adClient !== '' && $adSlot !== '')
    <ins class="adsbygoogle ad-unit-{{ $label }}"
        style="display:block"
        data-ad-client="{{ $adClient }}"
        data-ad-slot="{{ $adSlot }}"
        data-ad-format="auto"
        data-full-width-responsive="true"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
        @if ($adDebug)
        ;(function () {
            var el = document.querySelector('.ad-unit-{{ $label }}');
            var zone = {{ json_encode($label) }};
            if (!el) {
                console.error('[ADS:' + zone + '] Unit iklan TIDAK ditemukan di DOM');
                return;
            }
            var t0 = performance.now();
            var tries = 0;
            var timer = setInterval(function () {
                var status = el.getAttribute('data-adsbygoogle-status');
                var iframe = el.querySelector('iframe');

                if (status === 'done') {
                    // Unit sudah diproses google. Sekarang cek apakah benar adan iklan dirender.
                    if (iframe && iframe.offsetWidth > 0) {
                        console.log('[ADS:' + zone + '] OK - iklan TERMUAT '
                            + (performance.now() - t0).toFixed(0) + 'ms, w='
                            + iframe.offsetWidth + 'px, status=' + status);
                    } else {
                        console.warn('[ADS:' + zone + '] DIPROSES tapi TIDAK ada iklan '
                            + '(no fill / domain belum disetujui AdSense / viewport terlalu kecil) '
                            + 'status=' + status + ' hasIframe=' + !!iframe);
                    }
                    clearInterval(timer);
                    return;
                }

                tries++;
                if (tries > 24) { // +/- 12 detik batas maksimal
                    console.warn('[ADS:' + zone + '] TIMEOUT - status tidak pernah done. '
                        + 'Cek: script adsbygoogle.js termuat? network blocked? '
                        + 'status=' + status);
                    clearInterval(timer);
                }
            }, 500);
        })();
        @endif
    </script>
@endif