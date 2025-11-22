<div>
    <label for="name" class="block text-sm font-medium text-primary mb-1">> Entry Name</label>
    <input type="text" 
           id="name" 
           x-ref="nameInput"
           x-model="form.name" 
           class="form-control" 
           placeholder="Contoh: Doraemon the Movie: Nobita's Art World Tales"
           required>
    <template x-if="errors.name">
        <p class="mt-1 text-xs text-red-500" x-text="errors.name[0]"></p>
    </template>
</div>

<div class="mt-4">
    <div class="flex justify-between items-end mb-1">
        <label class="block text-sm font-medium text-primary">> Description (Optional)</label>
        
    <button type="button"
        @click="generateAiDescription()"
        :disabled="isGeneratingAi || !form.name"
        :class="{ 
            'opacity-50 cursor-not-allowed': isGeneratingAi || !form.name, 
            'cursor-pointer hover:bg-indigo-500': !(isGeneratingAi || !form.name) 
        }"
        class="text-xs bg-indigo-600 text-white px-3 py-1 rounded border border-indigo-400 flex items-center gap-1 transition-all shadow-sm">
        
        <svg x-show="isGeneratingAi" class="animate-spin h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        
        <svg x-show="!isGeneratingAi" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd" />
        </svg>

        <span x-text="isGeneratingAi ? 'Generating...' : 'Auto Describe'"></span>
    </button>
    </div>

    <textarea x-model="form.description" 
              rows="6" 
              class="form-control" 
              placeholder="Klik tombol Auto Describe untuk mengisi otomatis..."></textarea>
    
    <template x-if="errors.description">
        <p class="mt-1 text-xs text-red-500" x-text="errors.description[0]"></p>
    </template>
</div>

<div class="mt-4">
    <label class="block text-sm font-medium text-secondary mb-1">> Preview Image URL</label>
    <input type="url" x-model="form.preview_image_url" class="form-underline" placeholder="https://example.com/image.jpg">
    <template x-if="errors.preview_image_url">
        <p class="mt-1 text-xs text-red-500" x-text="errors.preview_image_url[0]"></p>
    </template>
</div>