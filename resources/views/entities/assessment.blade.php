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
        {{-- PENTING: Gunakan single quote untuk x-data agar JSON object aman --}}
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

                {{-- SECTION 2: QUICK PRESETS --}}
                <div>
                    <label class="block text-xs text-primary mb-3 uppercase tracking-wider font-bold">>> LOAD TACTICAL
                        PRESET (QUICK FILL)</label>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" @click="applyPreset('human')"
                            class="px-3 py-1 text-xs border border-gray-600 hover:bg-gray-800 hover:text-white transition text-gray-400">HUMAN
                            STANDARD</button>
                        <button type="button" @click="applyPreset('soldier')"
                            class="px-3 py-1 text-xs border border-gray-600 hover:bg-gray-800 hover:text-white transition text-gray-400">ELITE
                            SOLDIER</button>
                        <button type="button" @click="applyPreset('monster')"
                            class="px-3 py-1 text-xs border border-gray-600 hover:bg-red-900/30 hover:text-red-400 hover:border-red-500 transition text-gray-400">MONSTER/KETER</button>
                        <button type="button" @click="applyPreset('god')"
                            class="px-3 py-1 text-xs border border-gray-600 hover:bg-yellow-900/30 hover:text-yellow-400 hover:border-yellow-500 transition text-gray-400">DEITY/ABSOLUTE</button>
                        <span class="text-gray-700">|</span>
                        <button type="button" @click="applyPreset('object')"
                            class="px-3 py-1 text-xs border border-gray-600 hover:bg-blue-900/30 hover:text-blue-400 hover:border-blue-500 transition text-gray-400">INANIMATE
                            HAZARD</button>
                    </div>
                </div>

                {{-- SECTION 3: STAT SLIDERS (Refactored to Alpine Loop) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                    {{-- Gunakan Alpine x-for untuk merender slider agar tidak ada konflik sintaks PHP/JS --}}
                    <template x-for="statKey in statKeys" :key="statKey">
                        <div>
                            <div class="flex justify-between mb-1">
                                {{-- Label Stat --}}
                                <label class="text-xs uppercase text-gray-400 tracking-wider"
                                    x-text="statKey.replace('_', ' ')"></label>
                                {{-- Angka Stat --}}
                                <span class="text-xs font-bold text-primary" x-text="stats[statKey]"></span>
                            </div>

                            {{-- Slider Input --}}
                            {{-- Name attribute using template literal for form submission --}}
                            <input type="range" :name="'stats[' + statKey + ']'" x-model="stats[statKey]"
                                min="0" max="100"
                                class="w-full h-2 bg-gray-800 rounded-lg appearance-none cursor-pointer accent-primary hover:accent-green-400">

                            {{-- Bar Visual --}}
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

    {{-- Alpine Logic --}}
    <script>
        function assessmentForm(entity) {
            return {
                // Data Awal
                tier: entity.power_tier || 10,
                type: entity.combat_type || 'AGGRESSOR',

                // Daftar Key Stats untuk Loop x-for
                statKeys: ['strength', 'speed', 'durability', 'intelligence', 'energy', 'combat_skill'],

                // Objek Stats
                stats: {
                    strength: entity.combat_stats?.strength || 0,
                    speed: entity.combat_stats?.speed || 0,
                    durability: entity.combat_stats?.durability || 0,
                    intelligence: entity.combat_stats?.intelligence || 0,
                    energy: entity.combat_stats?.energy || 0,
                    combat_skill: entity.combat_stats?.combat_skill || 0,
                },

                applyPreset(presetName) {
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
    </script>
</x-app-layout>
