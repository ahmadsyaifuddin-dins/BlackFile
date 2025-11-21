@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => '',
    'placeholder' => '-- Select --',
    'searchable' => false,
])

<div class="w-full">
    {{-- Label --}}
    @if ($label)
        <label for="{{ $name }}" class="block text-primary mb-1 font-mono">{{ $label }}</label>
    @endif

    <div x-data="{
        open: false,
        selected: '{{ $selected }}',
        search: '',
        rawOptions: {{ json_encode($options) }},
        focusedIndex: -1, // Melacak posisi highlight keyboard
    
        // Getter ini mengubah semua input (array/object) menjadi format standar: [{value: 'x', label: 'y'}]
        // Ini memudahkan kita melakukan loop dan navigasi index
        get normalizeOptions() {
            let items = [];
    
            if (Array.isArray(this.rawOptions)) {
                items = this.rawOptions.map(opt => ({ value: opt, label: opt }));
            } else {
                items = Object.keys(this.rawOptions).map(key => ({ value: key, label: this.rawOptions[key] }));
            }
    
            // Filter logic (Pencarian)
            if ('{{ $searchable }}' && this.search !== '') {
                const lowerSearch = this.search.toLowerCase();
                items = items.filter(item => item.label.toLowerCase().includes(lowerSearch));
            }
    
            return items;
        },
    
        // Mendapatkan label dari item yang terpilih
        get selectedLabel() {
            if (!this.selected) return '{{ $placeholder }}';
    
            // Cari di raw options
            if (Array.isArray(this.rawOptions)) {
                return this.selected;
            }
            return this.rawOptions[this.selected] || this.selected;
        },
    
        select(value) {
            this.selected = value;
            this.open = false;
            this.search = '';
            this.focusedIndex = -1; // Reset focus
        },
    
        toggle() {
            if (this.open) {
                this.open = false;
            } else {
                this.open = true;
                this.focusedIndex = -1; // Reset saat dibuka
                if ('{{ $searchable }}') {
                    this.$nextTick(() => this.$refs.searchInput?.focus());
                }
            }
        },
    
        // [BARU] Logic Keyboard Navigation
        focusNext() {
            if (!this.open) { this.toggle(); return; }
            if (this.normalizeOptions.length === 0) return;
    
            // Loop ke awal jika mentok bawah
            if (this.focusedIndex >= this.normalizeOptions.length - 1) {
                this.focusedIndex = 0;
            } else {
                this.focusedIndex++;
            }
            this.scrollToFocused();
        },
    
        focusPrev() {
            if (!this.open) { this.toggle(); return; }
            if (this.normalizeOptions.length === 0) return;
    
            // Loop ke akhir jika mentok atas
            if (this.focusedIndex <= 0) {
                this.focusedIndex = this.normalizeOptions.length - 1;
            } else {
                this.focusedIndex--;
            }
            this.scrollToFocused();
        },
    
        selectFocused() {
            if (!this.open) return;
            if (this.focusedIndex >= 0 && this.focusedIndex < this.normalizeOptions.length) {
                this.select(this.normalizeOptions[this.focusedIndex].value);
            }
        },
    
        // Helper agar scrollbar mengikuti selection
        scrollToFocused() {
            this.$nextTick(() => {
                const el = this.$refs.listbox.children[this.focusedIndex];
                if (el) el.scrollIntoView({ block: 'nearest' });
            });
        }
    }" class="relative font-mono w-full" @click.outside="open = false"
        @keydown.escape="open = false" @keydown.arrow-down.prevent="focusNext()" @keydown.arrow-up.prevent="focusPrev()"
        @keydown.enter.prevent="selectFocused()">
        {{-- Input Hidden untuk Form Submission --}}
        <input type="hidden" name="{{ $name }}" x-model="selected">

        {{-- Tombol Trigger --}}
        <button type="button" @click="toggle()"
            class="form-control relative w-full text-left flex items-center justify-between cursor-pointer transition-colors duration-200"
            :class="{ 'border-[var(--color-primary)] ring-1 ring-[var(--color-primary)]': open }">

            <span x-text="selectedLabel" class="truncate mr-2 block"></span>

            <span class="pointer-events-none flex items-center text-[var(--color-secondary)]">
                <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </span>
        </button>

        {{-- Dropdown Body --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100" style="display: none;"
            class="form-select-dropdown max-h-60 overflow-y-auto"> {{-- Tambahkan max-h dan overflow --}}

            {{-- Input Pencarian --}}
            @if ($searchable)
                <div class="p-2 border-b border-[var(--color-border)] bg-[var(--color-surface)] sticky top-0 z-10">
                    <input type="text" x-model="search" x-ref="searchInput" @input="focusedIndex = 0"
                        class="w-full bg-black border border-[var(--color-border)] text-white px-2 py-1 text-sm focus:outline-none focus:border-[var(--color-primary)] placeholder-gray-600"
                        placeholder="Search options...">
                </div>
            @endif

            {{-- Container List Options (Diberi x-ref untuk scrolling) --}}
            <div x-ref="listbox">
                {{-- Opsi Reset / Placeholder --}}
                {{-- Kita sembunyikan ini jika user sedang searching agar tidak mengganggu navigasi index --}}
                <div x-show="!search" @click="select('')"
                    class="form-select-option border-b border-[var(--color-border)] italic text-opacity-50"
                    :class="{ 'active': selected === '' }">
                    {{ $placeholder }}
                </div>

                {{-- Loop Options --}}
                <template x-for="(item, index) in normalizeOptions" :key="item.value">
                    <div @click="select(item.value)" @mouseenter="focusedIndex = index"
                        class="form-select-option cursor-pointer select-none relative"
                        :class="{
                            'bg-[var(--color-primary)] text-black font-bold': focusedIndex === index,
                            'text-[var(--color-primary)]': selected === item.value && focusedIndex !== index,
                            'text-gray-300': selected !== item.value && focusedIndex !== index
                        }"
                        x-text="item.label">
                    </div>
                </template>

                {{-- Pesan jika tidak ada hasil --}}
                <div x-show="normalizeOptions.length === 0" class="px-4 py-2 text-sm text-gray-500 italic">
                    [NO DATA FOUND]
                </div>
            </div>
        </div>
    </div>
</div>
