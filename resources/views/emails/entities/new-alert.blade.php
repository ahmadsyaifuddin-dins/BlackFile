<x-mail::message>

<p style="text-align: center;">
    <img src="{{ $message->embed(public_path('app-icon.png')) }}" alt="{{ config('app.name') }} Logo" width="100">
</p>

SYSTEM ALERT: New Entity Registered

Attention Operatives,

A new entity has been registered into the BlackFile database by Director <b>{{ Auth::user()->codename }}</b>.

Entitas Summary

Codename: {{ $entity->codename ?? 'N/A' }}

Name: {{ $entity->name ?? 'N/A' }}

Category: {{ $entity->category ?? 'N/A' }}

Rank / Classification: {{ $entity->rank ?? 'Unknown' }}

Origin: {{ $entity->origin ?? 'Unknown' }}

<x-mail::panel>
Description:

{{ $entity->description ?? 'No description provided.' }}
</x-mail::panel>

<x-mail::button :url="route('entities.show', $entity)">
View Full Entitas
</x-mail::button>

Secure the data. Maintain the veil.

// END TRANSMISSION
</x-mail::message>