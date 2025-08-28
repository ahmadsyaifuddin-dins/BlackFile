<!-- resources/views/credits/_form_dynamic.blade.php -->

<div x-data="creditsForm({ initialCredits: {{ $credits ?? '[]' }} })" class="text-white">
    @if($errors->any())
    <div class="mb-4 bg-red-900/50 border-l-4 border-red-500 text-red-300 p-4 rounded-r-lg" role="alert">
        <p class="font-bold">> Data Input Anomaly Detected:</p>
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <!-- Daftar Section/Grup Credits -->
    <template x-for="(credit, creditIndex) in credits" :key="credit.uniqueKey">
        <div class="p-4 border border-gray-700 rounded-lg mb-6 bg-gray-900/50 relative">
            <input type="hidden" :name="'credits[' + creditIndex + '][id]'" x-model="credit.id">
            <button @click="removeCredit(creditIndex)" type="button" class="absolute top-2 right-2 text-gray-500 hover:text-red-500 p-1 leading-none text-2xl cursor-pointer">&times;</button>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300">Section Title / Role</label>
                <input type="text" :name="'credits[' + creditIndex + '][role]'" x-model="credit.role" required placeholder="e.g., Lead Analysts" class="mt-1 block w-full bg-gray-800 border-gray-600 rounded-md shadow-sm text-white focus:border-cyan-500 focus:ring-cyan-500">
            </div>

            <div class="pl-4 border-l-2 border-gray-700">
                <h4 class="text-sm text-gray-400 mb-2">Names</h4>
                <template x-for="(name, nameIndex) in credit.names" :key="nameIndex">
                    <!-- PERUBAHAN: Dibuat tumpuk di mobile, berdampingan di layar besar -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 mb-2">
                        <input type="text" :name="'credits[' + creditIndex + '][names][' + nameIndex + ']'" x-model="credit.names[nameIndex]" required placeholder="Full Name" class="flex-grow bg-gray-800 border-gray-600 rounded-md shadow-sm text-white">
                        <button @click="removeName(creditIndex, nameIndex)" type="button" class="text-red-500 text-xs px-2 py-1 bg-gray-700/50 rounded-md hover:bg-red-900/50 self-end sm:self-center cursor-pointer">Remove</button>
                    </div>
                </template>
                <button @click="addName(creditIndex)" type="button" class="text-xs text-primary hover:underline cursor-pointer ">+ Add Name</button>
            </div>

            <div class="mt-4 pl-4 border-l-2 border-gray-700">
                <h4 class="text-sm text-gray-400 mb-2">Logos for this Section</h4>
                 <template x-for="(logo, logoIndex) in credit.logos" :key="logoIndex">
                    <!-- PERUBAHAN: Dibuat tumpuk di mobile, berdampingan di layar besar -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 mb-2">
                        <select :name="'credits[' + creditIndex + '][logos][' + logoIndex + '][type]'" x-model="logo.type" class="bg-gray-800 border-gray-600 rounded-md text-sm">
                            <option value="file">File</option>
                            <option value="url">URL</option>
                        </select>
                        <div class="flex-grow">
                            <template x-if="logo.type === 'url'"><input type="url" :name="'credits[' + creditIndex + '][logos][' + logoIndex + '][path]'" x-model="logo.path" class="w-full bg-gray-800 border-gray-600 rounded-md"></template>
                            <template x-if="logo.type === 'file'">
                                <div>
                                    <input type="hidden" :name="'credits[' + creditIndex + '][logos][' + logoIndex + '][path]'" :value="logo.path">
                                     <div x-show="logo.path" class="my-2 flex items-center gap-2">
                                         <img :src="'/' + logo.path" class="h-8 w-8 object-contain bg-white p-1 rounded">
                                         <span x-text="logo.path.split('/').pop()" class="text-xs text-gray-400 truncate"></span>
                                     </div>
                                    <input type="file" :name="'credits[' + creditIndex + '][logos][' + logoIndex + '][file]'" class="cursor-pointer w-full text-sm text-gray-400 file:file-primary file:file-primary-hover file:border-0 file:rounded file:px-2 file:py-1 file:text-white">
                                </div>
                            </template>
                        </div>
                        <button @click="removeLogo(creditIndex, logoIndex)" type="button" class="text-red-500 text-xs px-2 py-1 bg-gray-700/50 rounded-md hover:bg-red-900/50 self-end sm:self-center cursor-pointer">Remove</button>
                    </div>
                </template>
                <button @click="addLogo(creditIndex)" type="button" class="text-xs text-primary hover:underline cursor-pointer ">+ Add Logo</button>
            </div>
        </div>
    </template>

    <button @click="addCredit" type="button" class="cursor-pointer mt-4 text-sm font-semibold text-white bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-md">+ Add Section</button>
    
    <!-- Bagian Musik dan Tombol Save -->
    <div class="mt-8 pt-6 border-t border-gray-700">
        <h3 class="text-lg font-medium text-gray-100 mb-4">Background Music</h3>
        <div>
            <label for="music" class="block text-sm font-medium text-gray-300">Upload New Music (MP3, WAV)</label>
            <input type="file" name="music" id="music"
                   class="cursor-pointer mt-1 block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-hover">
            @error('music')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror
            @if(isset($musicPath) && $musicPath)
                <div class="mt-4">
                    <p class="text-xs text-gray-400 mb-2">Current Music:</p>
                    <audio controls class="w-full"><source src="{{ url($musicPath) }}" type="audio/mpeg"></audio>
                    <p class="text-xs text-gray-500 mt-2">Uploading a new file will replace the existing one.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-700">
        <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-primary border rounded-md font-semibold text-xs text-white uppercase bg-primary-hover cursor-pointer">
            Save All Credits
        </button>
    </div>
</div>

<script>
    function creditsForm({ initialCredits }) {
        return {
            credits: initialCredits && initialCredits.length > 0 ? initialCredits.map((c, i) => ({
                ...c, // Salin semua properti lama (id, role, names, logos)
                uniqueKey: c.id || Date.now() + i, // PERBAIKAN: Tambahkan kunci unik
                names: c.names && c.names.length > 0 ? c.names : [''],
                logos: (c.logos || []).map(l => ({ type: l.startsWith('http') ? 'url' : 'file', path: l }))
            })) : [{ id: null, role: '', names: [''], logos: [], uniqueKey: Date.now() }], // PERBAIKAN: Tambahkan kunci unik

            addCredit() {
                // PERBAIKAN: Tambahkan kunci unik saat membuat item baru
                this.credits.push({ id: null, role: '', names: [''], logos: [], uniqueKey: Date.now() });
            },
            removeCredit(index) {
                if (this.credits.length > 1) {
                    this.credits.splice(index, 1);
                }
            },
            addName(creditIndex) {
                this.credits[creditIndex].names.push('');
            },
            removeName(creditIndex, nameIndex) {
                if (this.credits[creditIndex].names.length > 1) {
                    this.credits[creditIndex].names.splice(nameIndex, 1);
                }
            },
            addLogo(creditIndex) {
                this.credits[creditIndex].logos.push({ type: 'file', path: '' });
            },
            removeLogo(creditIndex, logoIndex) {
                this.credits[creditIndex].logos.splice(logoIndex, 1);
            }
        }
    }
</script>