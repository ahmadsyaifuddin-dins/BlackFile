<x-app-layout>
    <x-slot:title>
        System Dashboard
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <div class="border-2 border-border-color bg-surface p-6 rounded-lg">
            <h2 class="text-xl font-bold text-primary mb-4">AGENT STATUS</h2>
            <div class="space-y-2">
                <p>> ID: <span class="text-white">{{ Auth::user()->name }}</span></p>
                <p>> CODENAME: <span class="text-white">{{ Auth::user()->codename }}</span></p>
                <p>> DESIGNATION: <span class="text-white">{{ Auth::user()->role->alias }}</span></p>
            </div>
        </div>

        <div class="border-2 border-border-color bg-surface p-6 rounded-lg md:col-span-2">
            <h2 class="text-xl font-bold text-primary mb-4">PROJECT OVERVIEW</h2>
            <div class="space-y-2">
                <p>> Active Projects: <span class="text-white">3</span></p>
                <p>> Pending Review: <span class="text-white">1</span></p>
                <p>> Total Assets: <span class="text-white">14</span></p>
            </div>
        </div>

        <div class="border-2 border-yellow-500/50 bg-yellow-900/20 p-6 rounded-lg">
            <h2 class="text-xl font-bold text-yellow-400 mb-4">SYSTEM ALERT</h2>
            <p class="text-yellow-300">> Unidentified signal detected in Sector Gamma-7.</p>
        </div>

        <div class="border-2 border-border-color bg-surface p-6 rounded-lg">
            <h2 class="text-xl font-bold text-primary mb-4">RECENT ACTIVITY</h2>
            <p>> Logged in from IP: 127.0.0.1</p>
        </div>

    </div>
</x-app-layout>