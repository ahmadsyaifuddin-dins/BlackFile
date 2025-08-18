{{--
Komponen Blade ini "bodoh" (dumb component).
Ia tidak memiliki logika sendiri dan sepenuhnya dikontrol oleh state Alpine.js
yang di-passing dari view induknya (prototypes.index.blade.php).
--}}

<div x-show="isModalOpen" @keydown.escape.window="isModalOpen = false; isDeleteModalOpen = false"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 px-4" x-cloak>
    {{-- Konten Modal --}}
    <div @click.outside="isModalOpen = false"
        class="bg-gray-900 border border-gray-700 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">

        {{-- Form Action di-binding secara dinamis dari Alpine.js --}}
        <form x-bind:action="formAction" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Jika dalam mode edit, tambahkan method PUT --}}
            <template x-if="isEditMode">
                @method('PUT')
            </template>

            <div class="p-6 font-mono">
                {{-- Judul Modal dinamis --}}
                <h3 class="text-xl text-green-400 border-b border-gray-700 pb-2 mb-4" x-text="formTitle"></h3>

                {{-- Baris 1: Name & Codename --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="name" class="block text-gray-400 text-sm mb-1">> Project Name</label>
                        {{-- Input di-binding ke formData.name di Alpine.js --}}
                        <input type="text" name="name" id="name" x-model="formData.name" class="form-input-dark"
                            required>
                    </div>
                    <div>
                        <label for="codename" class="block text-gray-400 text-sm mb-1">> Codename</label>
                        <input type="text" name="codename" id="codename" x-model="formData.codename"
                            class="form-input-dark" required>
                    </div>
                </div>

                {{-- Baris 2: Project Type & Status --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="status" class="block text-gray-400 text-sm mb-1">> Status</label>
                        <select name="status" id="status" x-model="formData.status" class="form-input-dark" required>
                            <option value="PLANNED">PLANNED</option>
                            <option value="IN_DEVELOPMENT">IN_DEVELOPMENT</option>
                            <option value="COMPLETED">COMPLETED</option>
                            <option value="ON_HOLD">ON_HOLD</option>
                            <option value="ARCHIVED">ARCHIVED</option>
                        </select>
                    </div>
                    <div>
                        <label for="project_type" class="block text-gray-400 text-sm mb-1">> Project Type</label>
                        <select id="project_type" name="project_type" x-model="formData.project_type"
                            class="form-input-dark">
                            <option value="">-- Select Type --</option>
                            @foreach(config('blackfile.project_types') as $project_type)
                            <option value="{{ $project_type }}">{{ $project_type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-4">
                    <label for="description" class="block text-gray-400 text-sm mb-1">> Description</label>
                    <textarea name="description" id="description" rows="3" x-model="formData.description"
                        class="form-input-dark" required></textarea>
                </div>

                {{-- Tech Stack --}}
                <div class="mb-4">
                    <label for="tech_stack" class="block text-gray-400 text-sm mb-1">> Tech Stack <span
                            class="text-gray-500">(comma-separated)</span></label>
                    <input type="text" name="tech_stack" id="tech_stack" x-model="formData.tech_stack"
                        class="form-input-dark" placeholder="e.g., Laravel, Vue, MySQL">
                </div>

                {{-- URL --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="repository_url" class="block text-gray-400 text-sm mb-1">> Repository URL</label>
                        <input type="url" name="repository_url" id="repository_url" x-model="formData.repository_url"
                            class="form-input-dark" placeholder="https://">
                    </div>
                    <div>
                        <label for="live_url" class="block text-gray-400 text-sm mb-1">> Live URL</label>
                        <input type="url" name="live_url" id="live_url" x-model="formData.live_url"
                            class="form-input-dark" placeholder="https://">
                    </div>
                </div>

                {{-- [BARU] Tambahkan blok ini setelah input URL --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="start_date" class="block text-gray-400 text-sm mb-1">> Start Date</label>
                        <input type="datetime-local" name="start_date" id="start_date" x-model="formData.start_date"
                            class="form-input-dark">
                    </div>
                    <div>
                        <label for="completed_date" class="block text-gray-400 text-sm mb-1">> Completed Date</label>
                        <input type="datetime-local" name="completed_date" id="completed_date"
                            x-model="formData.completed_date" class="form-input-dark">
                    </div>
                </div>

                {{-- [BARU] Input untuk upload gambar --}}
                <div class="mb-4">
                    <label for="cover_image" class="block text-gray-400 text-sm mb-1">> Cover Image</label>
                    <input type="file" name="cover_image" id="cover_image"
                        class="form-input-dark file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-800 file:text-green-300 hover:file:bg-green-700">
                    {{-- Tampilkan gambar saat ini jika sedang mode edit --}}
                    <template x-if="isEditMode && formData.cover_image_path">
                        <div class="mt-2">
                            <img x-bind:src="'/' + formData.cover_image_path" alt="Current Cover"
                                class="max-h-32 rounded">
                            <p class="text-xs text-gray-500 mt-1">Current image. Upload a new file to replace it.</p>
                        </div>
                    </template>
                </div>

            </div>

            {{-- Tombol Aksi Modal --}}
            <div class="bg-gray-800 px-6 py-3 flex justify-end items-center gap-4">
                <button type="button" @click="isModalOpen = false"
                    class="text-gray-400 hover:text-white transition">CANCEL</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    {{-- Teks tombol dinamis --}}
                    <span x-text="formSubmitButton"></span>
                </button>
            </div>
        </form>
    </div>
</div>