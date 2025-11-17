<div x-show="isModalOpen" 
    @keydown.escape.window="isModalOpen = false; isDeleteModalOpen = false"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black/75 backdrop-blur-md flex items-center justify-center z-50 px-4" x-cloak>
    
    {{-- Konten Modal --}}
    <div class="bg-surface border border-border-color rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto font-mono">

        {{-- Form Action Binding --}}
        <form x-bind:action="formAction" method="POST" enctype="multipart/form-data">
            @csrf
            <template x-if="isEditMode">
                @method('PUT')
            </template>

            <div class="p-6 space-y-6">
                {{-- Judul Modal --}}
                <div class="border-b border-dashed border-border-color pb-4">
                    <h3 class="text-xl font-bold text-primary tracking-widest" x-text="formTitle"></h3>
                </div>

                {{-- Baris 1: Name & Codename --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-primary text-sm mb-1">> Project Name</label>
                        <input type="text" name="name" id="name" x-model="formData.name" 
                               class="form-control" required>
                    </div>
                    <div>
                        <label for="codename" class="block text-primary text-sm mb-1">> Codename</label>
                        <input type="text" name="codename" id="codename" x-model="formData.codename" 
                               class="form-control" required>
                    </div>
                </div>

                {{-- Baris 2: Status & Project Type --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status" class="block text-primary text-sm mb-1">> Status</label>
                        <select name="status" id="status" x-model="formData.status" class="form-control cursor-pointer" required>
                            <option value="PLANNED">PLANNED</option>
                            <option value="IN_DEVELOPMENT">IN_DEVELOPMENT</option>
                            <option value="COMPLETED">COMPLETED</option>
                            <option value="ON_HOLD">ON_HOLD</option>
                            <option value="ARCHIVED">ARCHIVED</option>
                        </select>
                    </div>
                    <div>
                        <label for="project_type" class="block text-primary text-sm mb-1">> Project Type</label>
                        <select id="project_type" name="project_type" x-model="formData.project_type" class="form-control cursor-pointer">
                            <option value="">-- Select Type --</option>
                            @foreach(config('blackfile.project_types') as $project_type)
                                <option value="{{ $project_type }}">{{ $project_type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="description" class="block text-primary text-sm mb-1">> Description</label>
                    <textarea name="description" id="description" rows="5" x-model="formData.description" 
                              class="form-control whitespace-pre-wrap resize-y" required></textarea>
                    <p class="text-xs text-secondary mt-1">// Supports multi-line descriptions.</p>
                </div>

                {{-- Tech Stack --}}
                <div>
                    <label for="tech_stack" class="block text-primary text-sm mb-1">> Tech Stack (Comma Separated)</label>
                    <input type="text" name="tech_stack" id="tech_stack" x-model="formData.tech_stack" 
                           class="form-control" placeholder="e.g., Laravel, Vue, MySQL">
                </div>

                {{-- URLs --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="repository_url" class="block text-primary text-sm mb-1">> Repository URL</label>
                        <input type="url" name="repository_url" id="repository_url" x-model="formData.repository_url" 
                               class="form-control" placeholder="https://...">
                    </div>
                    <div>
                        <label for="live_url" class="block text-primary text-sm mb-1">> Live URL</label>
                        <input type="url" name="live_url" id="live_url" x-model="formData.live_url" 
                               class="form-control" placeholder="https://...">
                    </div>
                </div>

                {{-- Dates --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_date" class="block text-primary text-sm mb-1">> Start Date</label>
                        <input type="datetime-local" name="start_date" id="start_date" x-model="formData.start_date" 
                               class="form-control">
                    </div>
                    <div>
                        <label for="completed_date" class="block text-primary text-sm mb-1">> Completed Date</label>
                        <input type="datetime-local" name="completed_date" id="completed_date" x-model="formData.completed_date" 
                               class="form-control">
                    </div>
                </div>

                {{-- ICON UPLOAD SECTION --}}
                <div>
                    <label class="block text-primary text-sm mb-2">> App Icon / Logo</label>
                    
                    {{-- Toggle --}}
                    <div class="flex border border-border-color rounded-md p-1 mb-3 w-full md:w-1/2">
                        <button type="button" @click="iconUploadMethod = 'file'"
                            :class="{ 'bg-primary text-black font-bold': iconUploadMethod === 'file', 'text-secondary hover:text-white': iconUploadMethod !== 'file' }"
                            class="flex-1 py-1 px-2 text-xs transition-colors rounded-sm">
                            Upload File
                        </button>
                        <button type="button" @click="iconUploadMethod = 'url'"
                            :class="{ 'bg-primary text-black font-bold': iconUploadMethod === 'url', 'text-secondary hover:text-white': iconUploadMethod !== 'url' }"
                            class="flex-1 py-1 px-2 text-xs transition-colors rounded-sm">
                            From URL
                        </button>
                    </div>

                    {{-- Input File --}}
                    <div x-show="iconUploadMethod === 'file'">
                        <div class="p-3 border border-border-color bg-base rounded-md">
                            <input type="file" name="icon" id="icon"
                                class="block w-full text-sm text-secondary
                                file:mr-4 file:py-2 file:px-4 file:border-0 file:rounded-sm
                                file:text-sm file:font-semibold file:bg-primary file:text-black 
                                hover:file:bg-primary-hover cursor-pointer">
                        </div>
                        
                        <template x-if="isEditMode && formData.icon_path && !formData.icon_path.startsWith('http')">
                            <div class="mt-2 flex items-center gap-3 bg-base p-2 border border-border-color rounded">
                                <img x-bind:src="'/' + formData.icon_path" class="h-10 w-10 rounded object-cover">
                                <span class="text-xs text-secondary">// Current Local Icon</span>
                            </div>
                        </template>
                    </div>

                    {{-- Input URL --}}
                    <div x-show="iconUploadMethod === 'url'">
                        <input type="url" name="icon_url" x-model="formData.icon_url" class="form-underline" placeholder="https://...">
                        
                        <template x-if="isEditMode && formData.icon_path && formData.icon_path.startsWith('http')">
                            <div class="mt-2 flex items-center gap-3 bg-base p-2 border border-border-color rounded">
                                <img x-bind:src="formData.icon_path" class="h-10 w-10 rounded object-cover">
                                <span class="text-xs text-secondary">// Current URL Icon</span>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- COVER IMAGE UPLOAD SECTION --}}
                <div>
                    <label class="block text-primary text-sm mb-2">> Cover Image</label>
                    
                    {{-- Toggle --}}
                    <div class="flex border border-border-color rounded-md p-1 mb-3 w-full md:w-1/2">
                        <button type="button" @click="uploadMethod = 'file'"
                            :class="{ 'bg-primary text-black font-bold': uploadMethod === 'file', 'text-secondary hover:text-white': uploadMethod !== 'file' }"
                            class="flex-1 py-1 px-2 text-xs transition-colors rounded-sm">
                            Upload File
                        </button>
                        <button type="button" @click="uploadMethod = 'url'"
                            :class="{ 'bg-primary text-black font-bold': uploadMethod === 'url', 'text-secondary hover:text-white': uploadMethod !== 'url' }"
                            class="flex-1 py-1 px-2 text-xs transition-colors rounded-sm">
                            From URL
                        </button>
                    </div>

                    {{-- Input File --}}
                    <div x-show="uploadMethod === 'file'">
                        <div class="p-3 border border-border-color bg-base rounded-md">
                            <input type="file" name="cover_image" id="cover_image"
                                class="block w-full text-sm text-secondary
                                file:mr-4 file:py-2 file:px-4 file:border-0 file:rounded-sm
                                file:text-sm file:font-semibold file:bg-primary file:text-black 
                                hover:file:bg-primary-hover cursor-pointer">
                        </div>

                        <template x-if="isEditMode && formData.cover_image_path && !formData.cover_image_path.startsWith('http')">
                            <div class="mt-2">
                                <img x-bind:src="'/' + formData.cover_image_path" class="max-h-24 rounded border border-border-color">
                                <p class="text-xs text-secondary mt-1">// Current Local Cover</p>
                            </div>
                        </template>
                    </div>

                    {{-- Input URL --}}
                    <div x-show="uploadMethod === 'url'">
                        <input type="url" name="cover_image_url" x-model="formData.cover_image_url" class="form-underline" placeholder="https://...">
                        
                        <template x-if="isEditMode && formData.cover_image_path && formData.cover_image_path.startsWith('http')">
                            <div class="mt-2">
                                <img x-bind:src="formData.cover_image_path" class="max-h-24 rounded border border-border-color">
                                <p class="text-xs text-secondary mt-1">// Current URL Cover</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Footer Modal --}}
            <div class="bg-surface-light border-t border-border-color px-6 py-4 flex justify-end items-center gap-4">
                <x-button variant="secondary" @click="isModalOpen = false">
                    CANCEL
                </x-button>
                
                <x-button type="submit">
                    <span x-text="formSubmitButton"></span>
                </x-button>
            </div>
        </form>
    </div>
</div>