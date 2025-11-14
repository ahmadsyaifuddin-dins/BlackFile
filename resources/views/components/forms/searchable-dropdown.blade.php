@props([
    'label',
    'name',
    'options',
    'selected' => ''
])

<div>
    <label for="{{ $name }}" class="block text-primary mb-1">{{ $label }}</label>
    
    <div x-data="searchableDropdown({
            options: {{ json_encode($options) }},
            selected: '{{ $selected }}'
        })" class="relative font-mono">
        
        <input type="hidden" name="{{ $name }}" x-model="selected">
        
        <button type="button" @click="toggle()" 
            class="relative w-full bg-base border-2 border-border-color text-left text-white p-2 pr-10 focus:outline-none focus:border-primary">
            <span x-text="selected || 'Select an option...'"></span>
            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 7.03 7.78a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l2.97-2.97a.75.75 0 111.06 1.06l-3.5 3.5a.75.75 0 01-1.06 0l-3.5-3.5a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
        </button>
        
        <div x-show="open" @click.away="open = false" 
             class="absolute z-10 mt-1 w-full bg-black border border-border-color shadow-lg" style="display: none;">
            
            <input type="text" x-model="search" x-ref="search" @keydown.escape.prevent="open = false" 
                   placeholder="Search..." class="w-full bg-surface border-b border-border-color p-2 focus:outline-none text-white">
            
            <div class="max-h-60 overflow-y-auto">
                <template x-for="option in filteredOptions" :key="option">
                    <a @click="select(option)" 
                       class="block px-4 py-2 text-sm cursor-pointer hover:bg-primary hover:text-green-400" 
                       :class="{'text-green-600': selected === option}" x-text="option">
                    </a>
                </template>
                <div x-show="filteredOptions.length === 0" class="px-4 py-2 text-sm text-secondary">
                    No results found.
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Skrip ini hanya akan dimuat SEKALI, bahkan jika Anda menggunakan komponen ini 10 kali --}}
@once
    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('searchableDropdown', (config) => ({
                open: false,
                search: '',
                options: config.options || [],
                selected: config.selected || '',
                toggle() {
                    this.open = !this.open;
                    if (this.open) {
                        this.$nextTick(() => this.$refs.search.focus());
                    }
                },
                select(option) {
                    this.selected = option;
                    this.open = false;
                },
                get filteredOptions() {
                    if (this.search === '') {
                        return this.options;
                    }
                    return this.options.filter(option => {
                        return option.toLowerCase().includes(this.search.toLowerCase());
                    });
                }
            }));
        });
    </script>
    @endpush
@endonce