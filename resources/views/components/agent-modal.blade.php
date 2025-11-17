<div x-data="{ 
        isModalOpen: false, 
        selectedNodeData: { id: '', label: '', name: '', role: '', category: '', specialization: '' },
        showSubAssetForm: false,
        isEditing: false // State baru untuk mode edit
    }" x-show="isModalOpen" @open-node-modal.window="
        isModalOpen = true; 
        selectedNodeData = $event.detail;
        showSubAssetForm = false;
        isEditing = false; // Selalu reset ke mode view
    " @keydown.escape.window="isModalOpen = false" class="fixed inset-0 z-30 flex items-center justify-center p-4"
    style="display: none;">
    <!-- Backdrop -->
    <div x-show="isModalOpen" x-transition.opacity class="absolute inset-0 bg-black/75"></div>

    <!-- Panel Modal -->
    <div x-show="isModalOpen" x-transition @click.outside="isModalOpen = false"
        class="relative w-full max-w-lg bg-surface border-2 border-border-color rounded-lg shadow-lg flex flex-col">

        <!-- == MODE VIEW (TAMPILAN AGENT BACA-SAJA) == -->
        <div x-show="!isEditing">
            <!-- Header Agent -->
            <div class="flex items-start justify-between p-4 border-b border-border-color">
                <div class="min-w-0">
                    <h3 class="text-2xl font-bold text-primary break-words" x-text="selectedNodeData.label"></h3>
                    <p class="text-secondary" x-text="selectedNodeData.role"></p>
                </div>
                <button @click="isModalOpen = false"
                    class="text-secondary hover:text-white text-2xl ml-4 flex-shrink-0">&times;</button>
            </div>

            <!-- Konten Agent -->
            <div class="p-4 overflow-y-auto">
                <p class="text-white"><strong class="text-primary">> ID:</strong> <span
                        x-text="selectedNodeData.id"></span></p>
                <p class="text-white mt-2"><strong class="text-primary">> REAL NAME:</strong> <span
                        x-text="selectedNodeData.name"></span></p>
                <p class="text-white mt-2"><strong class="text-primary">> CODENAME:</strong> <span
                        x-text="selectedNodeData.label"></span></p>
                {{-- [BARU] Tampilkan Specialization jika itu User --}}
                <template x-if="selectedNodeData.id.startsWith('u')">
                    <p class="text-white mt-2"><strong class="text-primary">> SPECIALIZATION:</strong> <span
                            x-text="selectedNodeData.specialization || 'N/A'"></span></p>
                </template>
                {{-- [BARU] Tampilkan Category jika itu Asset --}}
                <template x-if="selectedNodeData.id.startsWith('f')">
                    <p class="text-white mt-2"><strong class="text-primary">> CATEGORY:</strong> <span
                            x-text="selectedNodeData.category || 'Uncategorized'"></span></p>
                </template>

                <!-- Form Sub-Asset (tersembunyi) -->
                <div x-show="showSubAssetForm" x-transition
                    class="mt-4 pt-4 border-t border-dashed border-border-color">
                    <h4 class="text-primary font-bold mb-2">> Register New Sub-Asset</h4>
                    <form method="POST" action="{{ route('connections.store_sub_asset') }}" class="space-y-3">
                        @csrf
                        <input type="hidden" name="source_type" value="App\Models\Friend">
                        <input type="hidden" name="source_id" :value="selectedNodeData?.id.substring(1)">

                        <div>
                            <label for="sub_name" class="block text-secondary text-sm">> REAL NAME</label>
                            <input type="text" id="sub_name" name="name" required
                                class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary p-2 rounded">
                        </div>
                        <div>
                            <label for="sub_codename" class="block text-secondary text-sm">> CODENAME</label>
                            <input type="text" id="sub_codename" name="codename" required
                                class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary p-2 rounded">
                        </div>
                        {{-- [BARU] Dropdown untuk Kategori di form edit --}}
                        <div>
                            <label for="sub_category" class="block text-secondary text-sm">> ASSET CATEGORY</label>
                            <select id="sub_category" name="category"
                                    class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary p-2 rounded">
                                <option value="">-- Select Category --</option>
                                @foreach(config('blackfile.asset_categories') as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-right">
                            <x-button type="submit">[ ESTABLISH LINK ]</x-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Footer Aksi (View Mode) -->
            <div class="p-4 border-t border-border-color flex-shrink-0">
                <template x-if="selectedNodeData && selectedNodeData.id.startsWith('f')">
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-between sm:items-center gap-3">
                        <!-- Tombol Hapus -->
                        <div>
                            <form :action="'/friends/' + selectedNodeData.id.substring(1)" method="POST"
                                onsubmit="return confirm('Confirm asset termination?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full sm:w-auto px-4 py-2 bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white font-bold text-sm rounded transition-colors">[
                                    DELETE ]</button>
                            </form>
                        </div>
                        <!-- Tombol Aksi Kanan -->
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                            <button @click="showSubAssetForm = !showSubAssetForm"
                                class="w-full sm:w-auto px-4 py-2 bg-green-600/20 text-green-400 hover:bg-green-600 hover:text-white font-bold text-sm rounded transition-colors">[
                                + ADD SUB-ASSET ]</button>
                            <button @click="isEditing = true"
                                class="text-center w-full sm:w-auto px-4 py-2 bg-blue-600/80 text-white hover:bg-blue-600 font-bold text-sm rounded transition-colors">[
                                EDIT ]</button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- == MODE EDIT (FORM UPDATE ASET) -->
        <div x-show="isEditing">
            <form method="POST" :action="'/friends/' + selectedNodeData.id.substring(1)">
                @csrf
                @method('PUT')

                <!-- Header Edit -->
                <div class="p-4 border-b border-border-color">
                    <h3 class="text-2xl font-bold text-primary">EDITING AGENT</h3>
                    <p class="text-secondary" x-text="selectedNodeData.label"></p>
                </div>

                <!-- Konten Form Edit -->
                <div class="p-4 space-y-4">
                    <div>
                        <label for="edit_name" class="block text-primary text-sm">> REAL NAME</label>
                        <input type="text" id="edit_name" name="name" x-model="selectedNodeData.name" required
                            class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary p-2 rounded">
                    </div>
                    <div>
                        <label for="edit_codename" class="block text-primary text-sm">> CODENAME</label>
                        <input type="text" id="edit_codename" name="codename" x-model="selectedNodeData.label" required
                            class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary p-2 rounded">
                    </div>
                    {{-- Dropdown untuk Kategori di form edit --}}
                    <div>
                        <label for="edit_category" class="block text-primary text-sm">> ASSET CATEGORY</label>
                        <select id="edit_category" name="category" x-model="selectedNodeData.category"
                            class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary p-2 rounded">
                            <option value="">-- Select Category --</option>
                            {{-- Loop dari config file --}}
                            @foreach(config('blackfile.asset_categories') as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Footer Aksi (Edit Mode) -->
                <div class="p-4 border-t border-border-color flex items-center justify-end space-x-3">
                    <x-button variant="secondary" type="button" @click="isEditing = false">CANCEL</x-button>
                    <x-button type="submit">[ SAVE CHANGES ]</x-button>
                </div>
            </form>
        </div>
    </div>
</div>