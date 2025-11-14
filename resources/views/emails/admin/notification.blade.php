<x-mail::message>
<p style="text-align: center;">
    <img src="{{ $message->embed(public_path('app-icon.png')) }}" alt="{{ config('app.name') }} Logo" width="100">
</p>

# Notification from Command

{{-- Ini akan merender Markdown/HTML dari inputan Director --}}
{!! $content !!}

<br>
Regards,<br>
{{ config('app.name') }}
</x-mail::message>