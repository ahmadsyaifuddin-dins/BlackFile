<div class="mt-6 bg-surface border border-border-color rounded-lg p-4" x-data="{ target: 'selected' }">
    <h3 class="text-xl font-bold text-white mb-4 border-b border-border-color pb-2">> AGENT BROADCAST</h3>

    <form action="{{ route('admin.command.notify') }}" method="POST">
        @csrf

        {{-- Input Subject --}}
        <div class="mb-4">
            <label for="subject" class="block text-sm font-bold text-primary mb-2">> SUBJECT</label>
            <input type="text" id="subject" name="subject"
                class="w-full bg-base border border-border-color rounded-md px-3 py-2 text-glow focus:outline-none focus:border-primary"
                value="{{ old('subject') }}" required>
        </div>

        {{-- Input Message --}}
        <div class="mb-4">
            <label for="message" class="block text-sm font-bold text-primary mb-2">> MESSAGE (Markdown
                Enabled)</label>
            <textarea id="message" name="message" rows="8"
                class="w-full bg-base border border-border-color rounded-md px-3 py-2 text-glow focus:outline-none focus:border-primary font-mono"
                required>{{ old('message') }}</textarea>
            <p class="text-xs text-secondary mt-1">// Anda bisa menggunakan Markdown untuk format teks dan gambar,
                misal: `![Logo](url_gambar.png)`</p>
        </div>

        {{-- Target Selection --}}
        <div class="mb-4">
            <label class="block text-sm font-bold text-primary mb-2">> TARGET RECIPIENTS</label>
            <div class="flex items-center gap-6">
                <x-forms.radio name="target" value="selected" x-model="target" class="text-glow cursor-pointer">
                    Selected Agents
                </x-forms.radio>
                <x-forms.radio name="target" value="all" x-model="target" class="text-glow cursor-pointer">
                    All Active Agents ({{ $agents->count() }})
                </x-forms.radio>
            </div>
        </div>

        {{-- Agent Checkbox List (Conditional) --}}
        <div x-show="target === 'selected'" x-transition class="mb-4 border border-border-color bg-base rounded-md p-3">
            <label class="block text-sm font-bold text-primary mb-2">> SELECT AGENTS</label>
            <div class="max-h-60 overflow-y-auto pr-2 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
                @forelse($agents as $agent)
                    <label
                        class="flex items-center space-x-2 bg-surface/50 border border-border-color/50 px-3 py-2 rounded-md hover:bg-base cursor-pointer transition-colors text-sm">
                        <input type="checkbox" name="agents[]" value="{{ $agent->id }}" class="form-checkbox-themed">
                        <span class="text-glow truncate" title="{{ $agent->name }} ({{ $agent->email }})">
                            {{ $agent->codename }}
                        </span>
                    </label>

                @empty
                    <p class="text-secondary text-sm col-span-full">[ NO ACTIVE AGENTS FOUND ]</p>
                @endforelse
            </div>
        </div>

        <div class="mt-6 text-right border-t border-border-color pt-4">
            <x-button type="submit">
                [ DISPATCH BROADCAST ]
            </x-button>
        </div>

    </form>
</div>
