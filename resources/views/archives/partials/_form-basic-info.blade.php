<!-- 1. Name -->
<div>
    <label for="name" class="block text-sm font-medium text-primary mb-1">> Entry Name</label>
    <input type="text" id="name" x-model="form.name" class="form-control" required>
    <template x-if="errors.name">
        <p class="mt-1 text-xs text-red-500" x-text="errors.name[0]"></p>
    </template>
</div>

<!-- 2. Description -->
<div>
    <label class="block text-sm font-medium text-primary mb-1">> Description (Optional)</label>
    <textarea x-model="form.description" rows="4" class="form-control"></textarea>
    <template x-if="errors.description">
        <p class="mt-1 text-xs text-red-500" x-text="errors.description[0]"></p>
    </template>
</div>

<!-- 3. Preview Image URL -->
<div>
    <label class="block text-sm font-medium text-secondary mb-1">> Preview Image URL</label>
    <input type="url" x-model="form.preview_image_url" class="form-underline" placeholder="https://example.com/image.jpg">
    <template x-if="errors.preview_image_url">
        <p class="mt-1 text-xs text-red-500" x-text="errors.preview_image_url[0]"></p>
    </template>
</div>