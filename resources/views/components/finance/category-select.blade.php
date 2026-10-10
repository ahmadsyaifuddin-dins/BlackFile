@props([
    'name' => 'category',
    'income' => [],
    'expense' => [],
    'selected' => '',
    'customName' => '',
    'defaultIn' => '',
    'defaultOut' => '',
    'type' => 'in',
])

@php
    $sentinel = \App\Http\Controllers\Finance\CashTransactionController::CUSTOM_CATEGORY;
    $icons = array_merge(config('finance.income_categories'), config('finance.expense_categories'));
    $fallback = config('finance.fallback');
    $noCategoryText = '-- ' . __('No Category') . ' --';
    $otherText = __('Other (type your own)');
    $noCategoryIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M5 5l14 14"/></svg>';
    $otherIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 4l3 3-9 9H8v-3l9-9z"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h7"/></svg>';
@endphp

<div
    x-data="{
        open: false,
        type: @js($type),
        sentinel: @js($sentinel),
        selected: @js($selected),
        customName: @js($customName),
        defaultIn: @js($defaultIn),
        defaultOut: @js($defaultOut),
        income: @js($income),
        expense: @js($expense),
        icons: @js($icons),
        fallbackIcon: @js($fallback),
        noCategoryIcon: @js($noCategoryIcon),
        otherIcon: @js($otherIcon),
        noCategoryText: @js($noCategoryText),
        otherText: @js($otherText),
        focusedIndex: -1,

        get options() { return this.type === 'out' ? this.expense : this.income; },
        defaultFor(type) { return type === 'out' ? this.defaultOut : this.defaultIn; },

        get items() { return [''].concat(this.options).concat([this.sentinel]); },

        iconFor(value) {
            if (value === '' || value === null) return this.noCategoryIcon;
            if (value === this.sentinel) return this.otherIcon;
            return this.icons[value] || this.fallbackIcon;
        },

        selectedLabel() {
            if (this.selected === '') return this.noCategoryText;
            if (this.selected === this.sentinel) return this.otherText;
            return this.selected;
        },

        toggle() {
            this.open = !this.open;
            if (this.open) this.focusedIndex = -1;
        },

        pick(value) {
            if (value === this.sentinel) {
                this.selected = this.sentinel;
                this.customName = '';
                this.$nextTick(() => this.$refs.customInput?.focus());
                return;
            }
            this.selected = value;
            this.open = false;
            this.focusedIndex = -1;
        },

        onTypeChanged(detail) {
            if (detail !== 'in' && detail !== 'out') return;
            if (detail === this.type) return;
            this.type = detail;
            this.selected = this.defaultFor(detail);
            this.customName = '';
            this.focusedIndex = -1;
        },

        focusNext() {
            if (!this.open) { this.open = true; return; }
            if (this.items.length === 0) return;
            this.focusedIndex = this.focusedIndex >= this.items.length - 1 ? 0 : this.focusedIndex + 1;
            this.scrollToFocused();
        },

        focusPrev() {
            if (!this.open) { this.open = true; return; }
            if (this.items.length === 0) return;
            this.focusedIndex = this.focusedIndex <= 0 ? this.items.length - 1 : this.focusedIndex - 1;
            this.scrollToFocused();
        },

        selectFocused() {
            if (!this.open || this.focusedIndex < 0) return;
            this.pick(this.items[this.focusedIndex]);
        },

        scrollToFocused() {
            this.$nextTick(() => {
                const el = this.$refs.listbox.children[this.focusedIndex];
                if (el) el.scrollIntoView({ block: 'nearest' });
            });
        },
    }"
    {{ $attributes->merge(['class' => 'relative font-mono w-full']) }}
    @select-changed.window="onTypeChanged($event.detail)"
    @click.outside="open = false"
    @keydown.escape="open = false"
    @keydown.arrow-down.prevent="focusNext()"
    @keydown.arrow-up.prevent="focusPrev()"
    @keydown.enter.prevent="selectFocused()">

    <input type="hidden" name="{{ $name }}" :value="selected === sentinel ? customName : selected">

    {{-- Trigger --}}
    <button type="button" @click="toggle()"
        class="form-control relative w-full text-left flex items-center gap-2 cursor-pointer transition-colors duration-200"
        :class="{ 'border-[var(--color-primary)] ring-1 ring-[var(--color-primary)]': open }">
        <span class="text-[var(--color-primary)] shrink-0" x-html="iconFor(selected)"></span>
        <span x-text="selectedLabel" class="truncate flex-1 block"></span>
        <span class="pointer-events-none flex items-center text-[var(--color-secondary)]">
            <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </span>
    </button>

    {{-- Dropdown --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100" style="display: none;"
        class="form-select-dropdown max-h-60 overflow-y-auto">
        <div x-ref="listbox">
            <div @click="pick('')" @mouseenter="focusedIndex = 0"
                class="form-select-option cursor-pointer select-none flex items-center gap-2 border-b border-[var(--color-border)] italic"
                :class="{
                    'bg-[var(--color-primary)] text-black font-bold': focusedIndex === 0,
                    'text-opacity-50': selected !== '' && focusedIndex !== 0
                }">
                <span class="shrink-0" x-html="iconFor('')"></span>
                <span x-text="noCategoryText"></span>
            </div>

            <template x-for="(opt, index) in options" :key="'cat-' + opt">
                <div @click="pick(opt)" @mouseenter="focusedIndex = index + 1"
                    class="form-select-option cursor-pointer select-none flex items-center gap-2"
                    :class="{
                        'bg-[var(--color-primary)] text-black font-bold': focusedIndex === index + 1,
                        'text-[var(--color-primary)]': selected === opt && focusedIndex !== index + 1,
                        'text-gray-300': selected !== opt && focusedIndex !== index + 1
                    }">
                    <span class="shrink-0" x-html="iconFor(opt)"></span>
                    <span x-text="opt"></span>
                </div>
            </template>

            <div @click="pick(sentinel)" @mouseenter="focusedIndex = options.length + 1"
                class="form-select-option cursor-pointer select-none flex items-center gap-2"
                :class="{
                    'bg-[var(--color-primary)] text-black font-bold': focusedIndex === options.length + 1,
                    'text-[var(--color-primary)]': selected === sentinel && focusedIndex !== options.length + 1,
                    'text-gray-300': selected !== sentinel && focusedIndex !== options.length + 1
                }">
                <span class="shrink-0" x-html="otherIcon"></span>
                <span x-text="otherText"></span>
            </div>
        </div>
    </div>

    {{-- Input custom saat memilih "Other" --}}
    <div x-show="selected === sentinel" x-cloak class="mt-3">
        <input type="text" x-ref="customInput" x-model="customName" class="form-control"
            placeholder="{{ __('e.g. Kebutuhan Rumah, THR, ...') }}">
    </div>
</div>