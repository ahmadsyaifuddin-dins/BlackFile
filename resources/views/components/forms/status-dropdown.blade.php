@props([
    'label' => '> STATUS:',
    'selected' => 'UNKNOWN'
])

<div>
    <label for="status" class="block text-primary mb-1">{{ $label }}</label>
    
    <div x-data="{ open: false, selected: '{{ $selected }}' }" class="relative font-mono">
        <input type="hidden" name="status" x-bind:value="selected">
        
        <button type="button" @click="open = !open"
            class="relative w-full bg-base border-2 border-border-color text-left text-white p-2 pr-10 focus:outline-none focus:border-primary">
            <span x-text="selected"></span>
            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 7.03 7.78a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l2.97-2.97a.75.75 0 111.06 1.06l-3.5 3.5a.75.75 0 01-1.06 0l-3.5-3.5a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
        </button>
        
        <div x-show="open" @click.away="open = false"
            class="absolute z-10 mt-1 w-full bg-black border border-border-color shadow-lg"
            style="display: none;">
            
            <a @click="selected = 'UNKNOWN'; open = false"
                class="block px-4 py-2 text-sm cursor-pointer hover:bg-primary hover:text-white"
                :class="{ 'text-white': selected === 'UNKNOWN'}">UNKNOWN</a>
            <a @click="selected = 'ACTIVE'; open = false"
                class="block px-4 py-2 text-sm cursor-pointer hover:bg-primary hover:text-red-600"
                :class="{ 'text-red-600': selected === 'ACTIVE'}">ACTIVE</a>
            <a @click="selected = 'CONTAINED'; open = false"
                class="block px-4 py-2 text-sm cursor-pointer hover:bg-primary hover:text-yellow-600"
                :class="{ 'text-yellow-600': selected === 'CONTAINED'}">CONTAINED</a>
            <a @click="selected = 'NEUTRALIZED'; open = false"
                class="block px-4 py-2 text-sm cursor-pointer hover:bg-primary hover:text-green-600"
                :class="{ 'text-green-600': selected === 'NEUTRALIZED'}">NEUTRALIZED</a>
        </div>
    </div>
</div>