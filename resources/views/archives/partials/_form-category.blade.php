<!-- 4. Category -->
<div>
    <label class="block text-sm font-medium text-primary mb-1">> Category</label>
    <select x-model="form.category" @change="checkCategory" class="form-control cursor-pointer">
        <option value="">-- Select Category --</option>
        @foreach($categories as $cat)
            <option value="{{ $cat }}">{{ $cat }}</option>
        @endforeach
        <option value="Other">Other</option>
    </select>

    <div x-show="form.category === 'Other'" x-transition class="mt-4 pl-4 border-l-2 border-border-color">
        <label class="block text-sm font-medium text-secondary mb-1">> Specify Other Category</label>
        <input type="text" x-model="form.category_other" class="form-control" placeholder="e.g., Top Secret Protocol">
        <template x-if="errors.category_other">
            <p class="mt-1 text-xs text-red-500" x-text="errors.category_other[0]"></p>
        </template>
    </div>
    <template x-if="errors.category">
        <p class="mt-1 text-xs text-red-500" x-text="errors.category[0]"></p>
    </template>
</div>

<!-- 5. Tags -->
<div>
    <label class="block text-sm font-medium text-secondary mb-1">> Tags (Comma Separated)</label>
    <input type="text" x-model="form.tags" class="form-underline" placeholder="report, analysis, q4">
    <template x-if="errors.tags">
        <p class="mt-1 text-xs text-red-500" x-text="errors.tags[0]"></p>
    </template>
</div>

<!-- Visibility -->
<div class="bg-base border border-border-color rounded-md p-4 flex items-center justify-between">
    <div>
        <label class="font-medium text-primary">Public Access</label>
        <p class="text-xs text-secondary mt-1">Make this entry visible to other agents.</p>
        <!-- DEBUG: Hapus setelah testing -->
        <p class="text-xs text-yellow-400 mt-1" x-text="'Current value: ' + form.is_public"></p>
    </div>
    <label class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" x-model="form.is_public" class="sr-only peer">
        <div class="w-11 h-6 bg-gray-800 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
    </label>
</div>