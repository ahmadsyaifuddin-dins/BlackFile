<x-app-layout theme="terminal">
    <x-slot:title>
        {{ __('My Profile') }}
    </x-slot:title>

@php
    $genderMeta = [
        'male' => ['icon' => 'fa-mars', 'color' => 'text-blue-400', 'label' => __('Male')],
        'female' => ['icon' => 'fa-venus', 'color' => 'text-pink-400', 'label' => __('Female')],
        'other' => ['icon' => 'fa-user-secret', 'color' => 'text-purple-400', 'label' => __('Other')],
    ];
    $gm = $user->gender ? ($genderMeta[$user->gender] ?? null) : null;
@endphp

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-primary text-glow">> [ {{ __('PERSONAL AGENT') }} ]</h2>
</div>

@if(!empty($dossierIncomplete))
    <div class="mb-6 border-2 border-amber-500/50 bg-amber-500/10 px-4 py-3 rounded flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="flex items-center gap-3">
            <span class="w-2.5 h-2.5 bg-amber-400 animate-pulse rounded-full"></span>
            <div>
                <p class="text-sm font-bold text-amber-400 tracking-wider">> [ {{ __('DOSSIER INCOMPLETE') }} ]</p>
                <p class="text-xs text-secondary mt-0.5">{{ __('Fill in your gender & date of birth to complete this dossier.') }}</p>
            </div>
        </div>
        <a href="{{ route('profile.edit') }}"
            class="text-amber-400 border border-amber-500/60 hover:bg-amber-600 hover:text-black px-3 py-2 rounded text-xs font-bold tracking-widest whitespace-nowrap">
            > [ {{ __('COMPLETE PROFILE') }} ]
        </a>
    </div>
@endif

    <div class="bg-surface/50 border border-border-color rounded-lg p-6 text-glow flex flex-col sm:flex-row items-start gap-6">
        
        <!-- Avatar -->
        <div class="flex-shrink-0 mx-auto sm:mx-0">
            <img src="{{ $user->avatar ? asset($user->avatar) : 'https://blackfile.xo.je/agent-default.jpg' }}" 
                 alt="Avatar" class="w-32 h-32 object-cover rounded-full border-4 border-border-color shadow-lg">
        </div>
        
        <!-- Detail Teks -->
        <div class="space-y-4 w-full">
            <p><span class="text-primary/25">> {{ __('REAL NAME') }}:</span> {{ $user->name }}</p>
            <p><span class="text-primary/25">> {{ __('CODENAME') }}:</span> {{ $user->codename }}</p>
            <p><span class="text-primary/25">> {{ __('DESIGNATION') }}:</span> {{ $user->role->alias }}</p>
            <p><span class="text-primary/25">> {{ __('SPECIALIZATION') }}:</span> {{ $user->specialization ?? 'N/A' }}</p>
            <p><span class="text-primary/25">> {{ __('QUOTES') }}:</span> "{{ $user->quotes ?? '...' }}"</p>
            <p class="border-t border-border-color/50 pt-4 mt-4">
                <span class="text-primary/25">> {{ __('LOGIN ID') }}:</span> {{ $user->username }}
            </p>
            <p><span class="text-primary/25">> {{ __('HANDLER') }}:</span> {{ $user->parent->codename ?? '[ DIRECTORATE ]' }}</p>
            <p><span class="text-primary/25">> {{ __('LAST ACTIVITY') }}:</span> {{ $user->last_active_at ? $user->last_active_at->diffForHumans() : 'Never' }}</p>
            <p><span class="text-primary/25">> {{ __('RECOVERY EMAIL') }}:</span> {{ $user->email }}</p>
            <p><span class="text-primary/25">> {{ __('GENDER') }}:</span>
                @if ($gm)
                    <i class="fa-solid {{ $gm['icon'] }} {{ $gm['color'] }} mr-1"></i>{{ $gm['label'] }}
                @else
                    N/A
                @endif
            </p>
            <p><span class="text-primary/25">> {{ __('DATE OF BIRTH') }}:</span> {{ $user->date_of_birth?->format('Y-m-d') ?? 'N/A' }}</p>
            <p><span class="text-primary/25">> {{ __('REGISTERED ON') }}:</span> {{ $user->created_at->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>

    <div class="mt-6 border-t border-border-color pt-6 flex justify-end">
        <x-button href="{{ route('profile.edit') }}">
            [ EDIT PROFILE ]
        </x-button>
    </div>

    @push('scripts')
        <script>
            // Toast konfirmasi setelah update profile berhasil
            document.addEventListener('DOMContentLoaded', () => {
                @if (session('success'))
                    if (window.agentAlert) window.agentAlert('success', @js(__('PROFILE UPDATED')), @js(session('success')));
                @endif
            });
        </script>
    @endpush
</x-app-layout>