<div x-data="{ time: new Date().toLocaleTimeString('en-GB') }" x-init="setInterval(() => { time = new Date().toLocaleTimeString('en-GB') }, 1000)"
    class="mb-6 font-mono border-b-2 border-border-color pb-4">
    <h1 class="text-xl sm:text-2xl font-bold text-primary tracking-wider">
        <span class="text-glow">> {{ __('Welcome') }}, </span><span class="text-white">{{ Auth::user()->codename }}</span> <span class="text-primary animate-pulse">_</span>
    </h1>
    <p class="text-sm text-secondary">
        {{ __('SYSTEM TIME') }}: <span x-text="time" class="text-white"></span> // STATUS: <span class="text-green-400">NORMAL</span>
    </p>
</div>