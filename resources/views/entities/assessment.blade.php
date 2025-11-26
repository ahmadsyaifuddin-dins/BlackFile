<x-app-layout title="Tactical Assessment">
    <div class="max-w-4xl mx-auto py-8 font-mono text-gray-100">

        {{-- Header --}}
        <div class="mb-8 border-b border-gray-700 pb-4 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-black text-primary tracking-widest uppercase">TACTICAL ASSESSMENT</h1>
                <p class="text-xs text-gray-400 mt-1">ENTITY ID: {{ $entity->codename ?? $entity->name }}</p>
            </div>
            <a href="{{ route('entities.show', $entity) }}" class="text-xs text-gray-500 hover:text-white">[ RETURN TO
                FILE ]</a>
        </div>

        {{-- Form Area --}}
        <div x-data='assessmentForm(@json($entity))'
            class="bg-black border border-gray-800 p-6 rounded-lg shadow-2xl relative overflow-hidden">

            {{-- Background Grid --}}
            <div class="absolute inset-0 z-0 opacity-5 pointer-events-none"
                style="background-image: radial-gradient(#10b981 1px, transparent 1px); background-size: 20px 20px;">
            </div>

            <form action="{{ route('entities.update_assessment', $entity) }}" method="POST"
                class="relative z-10 space-y-8">
                @csrf
                @method('PUT')

                {{-- SECTION 1: CLASSIFICATION --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Power Tier --}}
                    <div>
                        <label class="block text-xs text-gray-400 mb-2 uppercase tracking-wider">Power Tier
                            (1-10)</label>
                        <select name="power_tier" x-model="tier"
                            class="w-full bg-gray-900 border border-gray-700 text-white p-2 focus:border-primary focus:ring-primary">
                            <option value="1">TIER 1 - OUTER GOD / OMNIPOTENT</option>
                            <option value="2">TIER 2 - MULTIVERSAL / ARCHANGEL</option>
                            <option value="3">TIER 3 - PLANETARY / DEITY</option>
                            <option value="4">TIER 4 - CONTINENTAL / KETER</option>
                            <option value="5">TIER 5 - CITY BLOCK / EUCLID</option>
                            <option value="6">TIER 6 - BUILDING / SUPERHUMAN</option>
                            <option value="7">TIER 7 - STREET / PEAK HUMAN</option>
                            <option value="8">TIER 8 - ATHLETE / SOLDIER</option>
                            <option value="9">TIER 9 - CIVILIAN / WEAK</option>
                            <option value="10">TIER 10 - INFANT / HARMLESS</option>
                        </select>
                        <p class="text-[10px] text-gray-500 mt-1">*Tier 1 = Absolute/God, Tier 10 = Human/Weak</p>
                    </div>

                    {{-- Combat Type --}}
                    <div>
                        <label class="block text-xs text-gray-400 mb-2 uppercase tracking-wider">Combat
                            Classification</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <x-forms.radio name="combat_type" value="AGGRESSOR" x-model="type"
                                    class="text-primary focus:ring-primary bg-gray-900 border-gray-700" />
                                <span class="text-sm"
                                    :class="type == 'AGGRESSOR' ? 'text-white font-bold' : 'text-gray-500'">AGGRESSOR</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <x-forms.radio name="combat_type" value="HAZARD" x-model="type"
                                    class="text-yellow-500 focus:ring-yellow-500 bg-gray-900 border-gray-700" />
                                <span class="text-sm"
                                    :class="type == 'HAZARD' ? 'text-white font-bold' : 'text-gray-500'">HAZARD
                                    (Object/Trap)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-800">

                {{-- SECTION 2: QUICK PRESETS & AI BUTTON --}}
                <div
                    class="flex flex-col md:flex-row justify-between items-end gap-6 mb-8 border-b border-gray-800 pb-6">

                    {{-- Manual Presets (Kiri) --}}
                    <div class="w-full md:w-auto">
                        <label class="block text-[10px] text-gray-500 mb-2 uppercase tracking-[0.2em] font-bold">
                            >> SELECT TACTICAL PRESET
                        </label>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" @click="applyPreset('human')"
                                class="px-3 py-1.5 text-[10px] font-mono border border-gray-700 bg-gray-900 hover:bg-gray-800 hover:border-gray-500 hover:text-white transition-all duration-200 text-gray-400 rounded-sm">
                                HUMAN STANDARD
                            </button>
                            <button type="button" @click="applyPreset('soldier')"
                                class="px-3 py-1.5 text-[10px] font-mono border border-gray-700 bg-gray-900 hover:bg-gray-800 hover:border-gray-500 hover:text-white transition-all duration-200 text-gray-400 rounded-sm">
                                ELITE SOLDIER
                            </button>
                            <button type="button" @click="applyPreset('monster')"
                                class="px-3 py-1.5 text-[10px] font-mono border border-red-900/30 bg-red-900/10 hover:bg-red-900/30 hover:text-red-400 hover:border-red-500 transition-all duration-200 text-red-700 rounded-sm">
                                MONSTER/KETER
                            </button>
                            <button type="button" @click="applyPreset('god')"
                                class="px-3 py-1.5 text-[10px] font-mono border border-yellow-900/30 bg-yellow-900/10 hover:bg-yellow-900/30 hover:text-yellow-400 hover:border-yellow-500 transition-all duration-200 text-yellow-700 rounded-sm">
                                DEITY/ABSOLUTE
                            </button>
                            <button type="button" @click="applyPreset('object')"
                                class="px-3 py-1.5 text-[10px] font-mono border border-blue-900/30 bg-blue-900/10 hover:bg-blue-900/30 hover:text-blue-400 hover:border-blue-500 transition-all duration-200 text-blue-700 rounded-sm">
                                INANIMATE HAZARD
                            </button>
                        </div>
                    </div>

                    {{-- AI GENERATE BUTTON (Kanan) --}}
                    <div class="w-full md:w-auto flex flex-col items-end">
                        <button type="button" @click="generateAiStats()" :disabled="isLoading"
                            class="relative group overflow-hidden flex items-center gap-3 px-6 py-3 rounded-md font-bold text-sm tracking-wider shadow-lg transition-all duration-300
                            bg-gradient-to-r from-indigo-900 via-purple-900 to-indigo-900 border border-indigo-500/50 text-indigo-100
                            hover:shadow-[0_0_20px_rgba(99,102,241,0.6)] hover:border-indigo-300 hover:text-white hover:scale-[1.02]
                            active:scale-95
                            cursor-pointer disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:shadow-none disabled:grayscale">

                            {{-- Background Shine Effect --}}
                            <div
                                class="absolute inset-0 bg-white/5 skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700 ease-in-out">
                            </div>

                            {{-- Loading Spinner --}}
                            <svg x-show="isLoading" class="animate-spin h-5 w-5 text-indigo-300"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>

                            {{-- Static Icon --}}
                            <svg x-show="!isLoading" xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 text-indigo-400 group-hover:text-yellow-300 transition-colors"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                                    clip-rule="evenodd" />
                            </svg>

                            {{-- Text --}}
                            <span class="relative z-10"
                                x-text="isLoading ? 'ESTABLISHING LINK...' : 'INITIATE AI ANALYSIS'"></span>
                        </button>

                        <div x-show="aiReason" x-transition.opacity
                            class="mt-3 text-[10px] font-mono text-indigo-300 text-right bg-indigo-900/30 px-2 py-1 border-l-2 border-indigo-500">
                            <span class="font-bold text-indigo-400">>> SYSTEM LOG:</span> <span
                                x-text="aiReason"></span>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: STAT SLIDERS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <template x-for="statKey in statKeys" :key="statKey">
                        <div>
                            <div class="flex justify-between mb-1">
                                <label class="text-xs uppercase text-gray-400 tracking-wider"
                                    x-text="statKey.replace('_', ' ')"></label>
                                <span class="text-xs font-bold text-primary" x-text="stats[statKey]"></span>
                            </div>

                            {{-- Slider --}}
                            <input type="range" :name="'stats[' + statKey + ']'" x-model="stats[statKey]"
                                min="0" max="100"
                                class="w-full h-2 bg-gray-800 rounded-lg appearance-none cursor-pointer accent-primary hover:accent-green-400">

                            {{-- Visual Bar --}}
                            <div class="w-full h-1 bg-gray-900 mt-1 rounded overflow-hidden">
                                <div class="h-full bg-primary transition-all duration-300"
                                    :style="'width: ' + stats[statKey] + '%'"></div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Submit Button --}}
                <div class="pt-6 border-t border-gray-800 flex justify-end">
                    <x-button type="submit"
                        class="bg-primary text-black font-bold px-6 py-3 hover:bg-green-400 transition-colors tracking-widest text-sm">
                        // SAVE CONFIGURATION
                    </x-button>
                </div>

            </form>
        </div>
    </div>

    {{-- SCRIPT DILETAKKAN DISINI SECARA INLINE UNTUK MENGHINDARI ERROR LINKING --}}
    @push('scripts')
        <script>
            // Menggunakan function biasa (bukan Alpine.data) agar sesuai dengan struktur x-data="assessmentForm(...)"
            function assessmentForm(entity) {
                return {
                    // Data Awal
                    tier: entity.power_tier || 10,
                    type: entity.combat_type || 'AGGRESSOR',
                    isLoading: false,
                    aiReason: null,

                    // Setup URL & Token
                    generateUrl: "{{ route('entities.generate_ai', ':id') }}".replace(':id', entity.id),
                    csrfToken: "{{ csrf_token() }}",

                    // Stat Configuration
                    statKeys: ['strength', 'speed', 'durability', 'intelligence', 'energy', 'combat_skill'],

                    // Pastikan object stats aman dari null value
                    stats: {
                        strength: (entity.combat_stats && entity.combat_stats.strength) || 0,
                        speed: (entity.combat_stats && entity.combat_stats.speed) || 0,
                        durability: (entity.combat_stats && entity.combat_stats.durability) || 0,
                        intelligence: (entity.combat_stats && entity.combat_stats.intelligence) || 0,
                        energy: (entity.combat_stats && entity.combat_stats.energy) || 0,
                        combat_skill: (entity.combat_stats && entity.combat_stats.combat_skill) || 0,
                    },

                    /**
                     * GENERATE AI STATS
                     */
                    async generateAiStats() {
                        // Cek apakah window.agentConfirm tersedia, jika tidak pakai confirm biasa
                        const confirmFunc = window.agentConfirm || confirm;

                        const confirmed = await confirmFunc(
                            'INITIATE AI ANALYSIS?',
                            'This action will overwrite current manual statistics with AI-predicted data. Proceed?',
                            'OVERWRITE & ANALYZE',
                            'CANCEL'
                        );

                        if (!confirmed) return;

                        this.isLoading = true;
                        this.aiReason = null;

                        try {
                            const response = await fetch(this.generateUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': this.csrfToken
                                },
                                body: JSON.stringify({})
                            });

                            if (!response.ok) throw new Error('Network response was not ok');

                            const data = await response.json();

                            // Update UI
                            this.tier = data.power_tier;
                            this.type = data.combat_type;
                            this.stats = data.combat_stats;
                            this.aiReason = "AI Analysis: " + data.reasoning;

                            if (window.agentAlert) {
                                window.agentAlert('success', 'ANALYSIS COMPLETE',
                                    'Entity tactical profile has been updated.');
                            }

                        } catch (error) {
                            console.error('Error:', error);
                            if (window.agentAlert) {
                                window.agentAlert('error', 'CONNECTION LOST', 'Failed to reach AI Server.');
                            } else {
                                alert('Connection Failed');
                            }
                        } finally {
                            this.isLoading = false;
                        }
                    },

                    /**
                     * APPLY PRESET
                     */
                    applyPreset(presetName) {
                        if (window.agentAlert) {
                            window.agentAlert('info', 'PRESET APPLIED', `Loading preset: ${presetName.toUpperCase()}`);
                        }

                        switch (presetName) {
                            case 'human':
                                this.tier = 10;
                                this.type = 'AGGRESSOR';
                                this.stats = {
                                    strength: 15,
                                    speed: 15,
                                    durability: 15,
                                    intelligence: 60,
                                    energy: 0,
                                    combat_skill: 10
                                };
                                break;
                            case 'soldier':
                                this.tier = 8;
                                this.type = 'AGGRESSOR';
                                this.stats = {
                                    strength: 40,
                                    speed: 40,
                                    durability: 40,
                                    intelligence: 70,
                                    energy: 0,
                                    combat_skill: 80
                                };
                                break;
                            case 'monster':
                                this.tier = 4;
                                this.type = 'AGGRESSOR';
                                this.stats = {
                                    strength: 90,
                                    speed: 70,
                                    durability: 90,
                                    intelligence: 50,
                                    energy: 20,
                                    combat_skill: 60
                                };
                                break;
                            case 'god':
                                this.tier = 2;
                                this.type = 'AGGRESSOR';
                                this.stats = {
                                    strength: 100,
                                    speed: 100,
                                    durability: 100,
                                    intelligence: 100,
                                    energy: 100,
                                    combat_skill: 100
                                };
                                break;
                            case 'object':
                                this.tier = 5;
                                this.type = 'HAZARD';
                                this.stats = {
                                    strength: 80,
                                    speed: 0,
                                    durability: 50,
                                    intelligence: 0,
                                    energy: 80,
                                    combat_skill: 0
                                };
                                break;
                        }
                    }
                }
            }

            // Handle Session Messages
            document.addEventListener('DOMContentLoaded', () => {
                @if (session('success'))
                    if (window.agentAlert) window.agentAlert('success', 'DATABASE UPDATED', "{{ session('success') }}");
                @endif
                @if ($errors->any())
                    if (window.agentAlert) window.agentAlert('warning', 'DATA CORRUPTION', "{{ $errors->first() }}");
                @endif
            });
        </script>
    @endpush
</x-app-layout>
