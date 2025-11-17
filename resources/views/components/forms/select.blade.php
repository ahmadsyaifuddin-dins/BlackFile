@props([
    'name',
    'label' => null, // Opsi label jika ingin di-render langsung
    'options' => [], // Bisa array ['A', 'B'] atau asosiatif ['key' => 'Label']
    'selected' => '', 
    'placeholder' => '-- Select --',
    'searchable' => false, // [BARU] Aktifkan fitur pencarian
])

<div class="w-full">
    {{-- Jika label disediakan via props --}}
    @if($label)
        <label for="{{ $name }}" class="block text-primary mb-1 font-mono">{{ $label }}</label>
    @endif

    <div x-data="{ 
            open: false, 
            selected: '{{ $selected }}',
            search: '',
            options: {{ json_encode($options) }},
            
            get label() {
                // Jika selected kosong, return placeholder
                if (!this.selected) return '{{ $placeholder }}';

                // Jika options array biasa (indeks numerik)
                if (Array.isArray(this.options) && this.options.includes(this.selected)) {
                    return this.selected;
                }
                
                // Jika options asosiatif (object), cari value berdasarkan key
                return this.options[this.selected] || this.selected;
            },

            get filteredOptions() {
                if (! '{{ $searchable }}' || this.search === '') {
                    return this.options;
                }

                const lowerSearch = this.search.toLowerCase();
                
                // Filter logika untuk Array vs Object
                if (Array.isArray(this.options)) {
                    return this.options.filter(opt => opt.toLowerCase().includes(lowerSearch));
                } else {
                    // Filter object (sedikit lebih kompleks)
                    return Object.keys(this.options)
                        .filter(key => this.options[key].toLowerCase().includes(lowerSearch))
                        .reduce((res, key) => (res[key] = this.options[key], res), {});
                }
            },

            select(value) {
                this.selected = value;
                this.open = false;
                this.search = ''; // Reset search setelah memilih
            },
            
            toggle() {
                if (this.open) {
                    this.open = false;
                } else {
                    this.open = true;
                    // Fokus ke input search jika searchable aktif
                    if ('{{ $searchable }}') {
                        this.$nextTick(() => this.$refs.searchInput?.focus());
                    }
                }
            }
        }" 
        class="relative font-mono w-full"
        @click.outside="open = false"
    >
        {{-- Input Hidden untuk Form --}}
        <input type="hidden" name="{{ $name }}" x-model="selected">

        {{-- Tombol Trigger --}}
        <button type="button" @click="toggle()"
            class="form-control relative w-full text-left flex items-center justify-between cursor-pointer"
            :class="{'border-[var(--color-primary)] ring-1 ring-[var(--color-primary)]': open}">
            
            <span x-text="label" class="truncate mr-2 block"></span>
            
            <span class="pointer-events-none flex items-center text-[var(--color-secondary)]">
                <svg class="h-4 w-4 transition-transform duration-200" :class="{'rotate-180': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </span>
        </button>

        {{-- Dropdown Body --}}
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             style="display: none;"
             class="form-select-dropdown">
            
            {{-- Input Pencarian (Hanya muncul jika searchable=true) --}}
            @if($searchable)
                <div class="p-2 border-b border-[var(--color-border)] bg-[var(--color-surface)] sticky top-0 z-10">
                    <input type="text" x-model="search" x-ref="searchInput" 
                           class="w-full bg-black border border-[var(--color-border)] text-white px-2 py-1 text-sm focus:outline-none focus:border-[var(--color-primary)]"
                           placeholder="Search options...">
                </div>
            @endif

            {{-- Opsi Reset --}}
            <div @click="select('')" 
                 class="form-select-option border-b border-[var(--color-border)] italic text-opacity-50"
                 :class="{'active': selected === ''}">
                 {{ $placeholder }}
            </div>

            {{-- Loop Options via Alpine --}}
            <template x-for="(label, key) in filteredOptions" :key="key">
                <div @click="select(Array.isArray(options) ? label : key)" 
                     class="form-select-option"
                     :class="{'active': selected === (Array.isArray(options) ? label : key)}"
                     x-text="label">
                </div>
            </template>
            
            {{-- Pesan jika tidak ada hasil --}}
            <div x-show="Object.keys(filteredOptions).length === 0" class="px-4 py-2 text-sm text-gray-500 italic">
                [NO MATCHES FOUND]
            </div>
        </div>
    </div>
</div>