{{-- Card Container --}}
<div class="relative p-4 border border-border-color bg-surface rounded-sm group hover:border-primary transition-colors duration-300">
    
    {{-- Hidden ID --}}
    <input type="hidden" :name="'credits[' + creditIndex + '][id]'" x-model="credit.id">
    
    {{-- Hapus Section Button --}}
    <button @click="removeCredit(creditIndex)" type="button"
        class="absolute top-0 right-0 p-2 text-secondary hover:text-red-500 transition-colors"
        title="Remove Section">
        [X]
    </button>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        {{-- KOLOM KIRI: ROLE & LOGOS --}}
        <div class="lg:col-span-4 space-y-4 border-r border-border-color/30 pr-0 lg:pr-6">
            
            {{-- Input Role --}}
            <div>
                <label class="block text-primary mb-1">> SECTION TITLE / ROLE</label>
                <input type="text" :name="'credits[' + creditIndex + '][role]'" x-model="credit.role" required
                    placeholder="e.g., Lead Analysts"
                    class="form-control w-full">
            </div>

            {{-- Logos Section --}}
            <div>
                <div class="flex justify-between items-end mb-2">
                    <label class="block text-secondary text-xs">> SECTION LOGOS</label>
                    <button @click="addLogo(creditIndex)" type="button" class="text-xs text-primary hover:text-white hover:underline">
                        [+ ADD LOGO]
                    </button>
                </div>
                
                <div class="space-y-3">
                    <template x-for="(logo, logoIndex) in credit.logos" :key="logoIndex">
                        <div class="p-2 border border-dashed border-border-color bg-base/50 rounded relative">
                            <div class="flex gap-2 mb-2">
                                <select :name="'credits[' + creditIndex + '][logos][' + logoIndex + '][type]'"
                                    x-model="logo.type" 
                                    class="bg-base border border-border-color text-xs text-secondary p-1 focus:border-primary focus:outline-none">
                                    <option value="file">File</option>
                                    <option value="url">URL</option>
                                </select>
                                
                                <button @click="removeLogo(creditIndex, logoIndex)" type="button"
                                    class="ml-auto text-red-500 hover:text-red-400 text-xs">
                                    [RMV]
                                </button>
                            </div>

                            {{-- Input URL --}}
                            <template x-if="logo.type === 'url'">
                                <input type="url"
                                    :name="'credits[' + creditIndex + '][logos][' + logoIndex + '][path]'"
                                    x-model="logo.path"
                                    placeholder="https://..."
                                    class="w-full bg-transparent border-b border-border-color text-xs text-white focus:border-primary focus:outline-none placeholder-secondary/30">
                            </template>

                            {{-- Input File --}}
                            <template x-if="logo.type === 'file'">
                                <div>
                                    <input type="hidden"
                                        :name="'credits[' + creditIndex + '][logos][' + logoIndex + '][path]'"
                                        :value="logo.path">
                                    
                                    <div x-show="logo.path" class="mb-2 flex items-center gap-2 bg-black p-1 border border-border-color">
                                        <img :src="'/' + logo.path" class="h-6 w-6 object-contain">
                                        <span x-text="logo.path.split('/').pop()" class="text-[10px] text-gray-500 truncate w-20"></span>
                                    </div>
                                    
                                    <input type="file"
                                        :name="'credits[' + creditIndex + '][logos][' + logoIndex + '][file]'"
                                        class="block w-full text-[10px] text-secondary
                                        file:mr-2 file:py-1 file:px-2
                                        file:border-0 file:text-[10px]
                                        file:bg-primary file:text-black hover:file:bg-green-400 cursor-pointer">
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: NAMES LIST --}}
        <div class="lg:col-span-8" x-data="{ editMode: 'visual' }">
            <div class="flex justify-between items-center mb-2">
                <label class="block text-primary">> NAMES LIST</label>
                <div class="flex text-[10px] border border-border-color">
                    <button @click.prevent="editMode = 'visual'; switchToVisual(creditIndex)" 
                        :class="editMode === 'visual' ? 'bg-primary text-black font-bold' : 'bg-base text-secondary hover:text-white'" 
                        class="px-2 py-1 transition-colors">VISUAL</button>
                    <button @click.prevent="editMode = 'raw'; switchToRaw(creditIndex)" 
                        :class="editMode === 'raw' ? 'bg-primary text-black font-bold' : 'bg-base text-secondary hover:text-white'" 
                        class="px-2 py-1 transition-colors">RAW JSON</button>
                </div>
            </div>

            {{-- Mode Visual --}}
            <div x-show="editMode === 'visual'" class="space-y-2">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <template x-for="(name, nameIndex) in credit.names" :key="`${creditIndex}-${nameIndex}`">
                        <div class="flex items-center gap-1 group/item">
                            <span class="text-secondary select-none">></span>
                            <input type="text" 
                                :name="'credits[' + creditIndex + '][names][' + nameIndex + ']'" 
                                x-model="credit.names[nameIndex]" 
                                placeholder="Operative Name" 
                                class="flex-grow form-underline">
                            <button @click.prevent="removeName(creditIndex, nameIndex)" type="button" 
                                class="text-red-500 opacity-0 group-hover/item:opacity-100 transition-opacity text-xs px-1">
                                [x]
                            </button>
                        </div>
                    </template>
                </div>
                <button @click.prevent="addName(creditIndex)" type="button" 
                    class="cursor-pointer mt-3 text-xs text-secondary hover:text-primary border border-dashed border-border-color hover:border-primary w-full py-1 text-center transition-colors">
                    + APPEND NAME ENTRY
                </button>
            </div>

            {{-- Mode Raw JSON --}}
            <div x-show="editMode === 'raw'" style="display: none;">
                <textarea x-model="credit.namesJson" 
                    @input.debounce.500ms="updateNamesFromJson(creditIndex)"
                    class="w-full h-48 bg-black border border-border-color text-green-500 font-mono text-xs p-2 focus:border-primary focus:ring-0"
                    placeholder='["Name 1", "Name 2"]'></textarea>
                <div class="flex justify-between items-center mt-1">
                    <p class="text-[10px] text-secondary">// Edit array structure directly.</p>
                    <button @click.prevent="formatJson(creditIndex)" type="button" class="text-[10px] text-primary hover:underline">[FORMAT]</button>
                </div>
            </div>
        </div>
    </div>
</div>