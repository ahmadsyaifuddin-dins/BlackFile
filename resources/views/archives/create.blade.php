<x-app-layout title="Add New Archive">
    <div class="max-w-3xl mx-auto">

        {{-- Header Halaman --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-lg sm:text-2xl font-bold text-primary">[ ADD_NEW_ENTRY ]</h1>
            <a href="{{ route('archives.index') }}"
                class="text-sm text-secondary hover:text-primary transition-colors duration-200">
                &lt;-- Back to Vault
            </a>
        </div>

        {{-- Kontainer Form Utama --}}
        <div class="bg-surface border border-border rounded-md shadow-lg" x-data="archiveForm()">
            <form @submit.prevent="submitForm">

                <div class="p-6 space-y-6">
                    {{-- Input Nama --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-secondary">> Entry Name</label>
                        <input type="text" name="name" id="name" x-model="formData.name"
                            class="mt-1 block w-full bg-base border-border rounded-md shadow-sm focus:ring-primary focus:border-primary text-secondary"
                            required>
                        <template x-if="errors.name"><p class="mt-1 text-xs text-red-500" x-text="errors.name[0]"></p></template>
                    </div>

                    {{-- Input Deskripsi --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-secondary">> Description (Optional)</label>
                        <textarea name="description" id="description" rows="4" x-model="formData.description"
                            class="mt-1 block w-full bg-base border-border rounded-md shadow-sm focus:ring-primary focus:border-primary text-secondary"></textarea>
                        <template x-if="errors.description"><p class="mt-1 text-xs text-red-500" x-text="errors.description[0]"></p></template>
                    </div>

                    {{-- Input Visibilitas --}}
                    <div class="bg-gray-900 border border-border rounded-md p-4 flex items-center justify-between">
                        <div>
                            <label for="is_public" class="font-medium text-primary">Public Access</label>
                            <p class="text-xs text-secondary mt-1">Allow other agents to see this archive.</p>
                        </div>
                        <label for="is_public" class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_public" id="is_public" value="1" class="sr-only peer" x-model="formData.is_public">
                            <div class="w-11 h-6 bg-gray-800 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary peer-focus:ring-offset-2 peer-focus:ring-offset-surface rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                            </div>
                        </label>
                    </div>

                    {{-- Pilihan Tipe dan Input --}}
                    <div class="space-y-4">
                        <fieldset>
                            <legend class="block text-sm font-medium text-secondary mb-2">> Entry Type</legend>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Pilihan File Upload --}}
                                <label class="relative flex p-4 border rounded-md cursor-pointer transition-colors duration-200"
                                    :class="formData.type === 'file' ? 'border-primary bg-primary/10' : 'border-border hover:border-primary/50'">
                                    <input type="radio" name="type" value="file" x-model="formData.type" class="sr-only">
                                    <div class="flex items-center gap-4">
                                        <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        <div>
                                            <span class="font-medium text-primary">File Upload</span>
                                            <p class="text-xs text-secondary">PDF, DOCX, XLSX, CSV, ZIP, etc.</p>
                                        </div>
                                    </div>
                                </label>
                                {{-- Pilihan URL --}}
                                <label class="relative flex p-4 border rounded-md cursor-pointer transition-colors duration-200"
                                    :class="formData.type === 'url' ? 'border-primary bg-primary/10' : 'border-border hover:border-primary/50'">
                                    <input type="radio" name="type" value="url" x-model="formData.type" class="sr-only">
                                    <div class="flex items-center gap-4">
                                        <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                        <div>
                                            <span class="font-medium text-primary">URL Link(s)</span>
                                            <p class="text-xs text-secondary">Single or multi-part links.</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </fieldset>

                        {{-- Input untuk File --}}
                        <div x-show="formData.type === 'file'" x-transition>
                            <label for="archive_file" class="block text-sm font-medium text-secondary">> Select File</label>
                            <div class="mt-1 p-4 w-full bg-base border-2 border-dashed border-border rounded-md flex justify-center items-center">
                                <input type="file" name="archive_file" id="archive_file" @change="handleFileSelect($event)"
                                    class="block w-full text-sm text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-base hover:file:bg-primary-hover">
                            </div>
                            <template x-if="errors.archive_file"><p class="mt-1 text-xs text-red-500" x-text="errors.archive_file[0]"></p></template>
                        </div>

                        {{-- Input untuk URL --}}
                        <div x-show="formData.type === 'url'" x-transition>
                            <label for="links" class="block text-sm font-medium text-secondary">> URL(s)</label>
                            <textarea name="links" id="links" rows="7" x-model="formData.links"
                                class="mt-1 block w-full bg-base border-border rounded-md shadow-sm focus:ring-primary focus:border-primary text-secondary"
                                placeholder="Satu link per baris untuk tautan multi-bagian..."></textarea>
                            <p class="mt-1 text-xs text-secondary">For multi-part links, place each link on a new line.</p>
                            <template x-if="errors.links"><p class="mt-1 text-xs text-red-500" x-text="errors.links[0]"></p></template>
                        </div>
                    </div>
                </div>

                {{-- Progress Bar dan Success Message --}}
                <div class="px-6 pb-6 space-y-4">
                    <div x-show="isUploading" x-transition class="w-full bg-surface-light rounded-full">
                        <div class="bg-primary text-xs font-medium text-primary text-center p-0.5 leading-none rounded-full"
                            :style="`width: ${progress}%`" x-text="`${progress}%`">
                        </div>
                    </div>
                    <template x-if="successMessage">
                        <p class="text-sm text-green-400" x-text="successMessage"></p>
                    </template>
                </div>

                {{-- Footer dengan Submit Button --}}
                <div class="bg-surface-light border-t border-border px-6 py-4 text-right rounded-b-md">
                    <button type="submit" :disabled="isUploading" 
                        class="inline-flex items-center justify-center gap-2 py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-base bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary focus:ring-offset-surface-light cursor-pointer"
                        :class="{ 'opacity-50 cursor-not-allowed': isUploading }">
                        <span x-show="!isUploading">SAVE_ENTRY</span>
                        <span x-show="isUploading">UPLOADING...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Pass data to JavaScript --}}
    <script>
        // Pass Laravel data to JavaScript - harus dijalankan sebelum Alpine.start()
        window.archiveFormData = {
            name: @json(old('name', '')),
            description: @json(old('description', '')),
            is_public: @json(old('is_public', false)),
            type: @json(old('type', 'file')),
            links: @json(old('links', '')),
            csrf_token: @json(csrf_token())
        };
        
        window.archiveFormUrls = {
            store: @json(route('archives.store')),
            index: @json(route('archives.index'))
        };

        // Debug: log the data being passed
        console.log('Archive Form Data:', window.archiveFormData);
        console.log('Archive Form URLs:', window.archiveFormUrls);
    </script>
</x-app-layout>