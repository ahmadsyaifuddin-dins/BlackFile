@extends('layouts.app')

@section('title', 'System Dashboard')

@section('content')
    <div class="border-2 border-border-color bg-surface p-6 rounded-lg">
        <h2 class="text-2xl font-bold text-primary mb-4">AGENT STATUS REPORT</h2>
        <p class="text-lg">> IDENTIFICATION: <span class="text-white">{{ Auth::user()->name }}</span></p>
        <p class="text-lg">> CODENAME: <span class="text-white">{{ Auth::user()->codename }}</span></p>
        <p class="text-lg">> DESIGNATION: <span class="text-white">{{ Auth::user()->role->alias }}</span></p>
        <p class="mt-6 text-primary animate-pulse">SYSTEM NOMINAL. AWAITING DIRECTIVES...</p>
    </div>
@endsection