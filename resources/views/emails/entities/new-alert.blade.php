<x-mail::message>

<x-slot name="header">
    <x-mail::header :logo="$message->embed(public_path('app-icon.png'))" :url="url('/')" />
</x-slot>

SYSTEM ALERT: New Entity Registered

Attention Operatives,

A new entity has been registered into the BlackFile database by Director {{ Auth::user()->codename }}.

Entitas Summary

Codename: {{ $entity->codename ?? 'N/A' }}

Name: {{ $entity->name ?? 'N/A' }}

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