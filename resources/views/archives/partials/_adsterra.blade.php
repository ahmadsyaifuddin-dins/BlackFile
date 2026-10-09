{{-- Adsterra --}}
@php
    $adEnabled = config('blackfile.adsterra.enabled', false);
    $type = config('blackfile.adsterra.type', 'socialbar');
    $scriptUrl = config('blackfile.adsterra.script_url', '');
    $popunderUrl = config('blackfile.adsterra.popunder_url', '');
    $nativeUrl = config('blackfile.adsterra.native_url', '');
    $banner468Key = config('blackfile.adsterra.banner468_key', '');
    $banner728Key = config('blackfile.adsterra.banner728_key', '');
@endphp

@if ($adEnabled)
    @if ($type === 'socialbar' && $scriptUrl !== '')
        <script data-cfasync="false" src="{{ $scriptUrl }}"></script>
    @elseif ($type === 'popunder' && $popunderUrl !== '')
        <script data-cfasync="false" src="{{ $popunderUrl }}"></script>
    @elseif ($type === 'native' && $nativeUrl !== '')
        <script async="async" data-cfasync="false" src="{{ $nativeUrl }}"></script>
        <div id="container-{{ $nativeUrl | basename ? '' : substr($nativeUrl, strrpos($nativeUrl,'/')+1) }}"></div>
    @elseif ($type === 'banner468' && $banner468Key !== '')
        <script>
          atOptions = {
            'key' : '{{ $banner468Key }}',
            'format' : 'iframe',
            'height' : 60,
            'width' : 468,
            'params' : {}
          };
        </script>
        <script src="https://bauval.org/22/{{ $banner468Key }}"></script>
    @elseif ($type === 'banner728' && $banner728Key !== '')
        <script>
          atOptions = {
            'key' : '{{ $banner728Key }}',
            'format' : 'iframe',
            'height' : 90,
            'width' : 728,
            'params' : {}
          };
        </script>
        <script src="https://bauval.org/22/{{ $banner728Key }}"></script>
    @endif
@endif