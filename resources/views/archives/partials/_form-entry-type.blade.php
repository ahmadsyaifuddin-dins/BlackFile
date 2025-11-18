<!-- 7. Entry Type & Content Input -->
<div class="space-y-6 pt-4 border-t border-border-color">

    <!-- Entry Type Selection -->
    <fieldset>
        <legend class="block text-sm font-medium text-primary mb-4">> Entry Type</legend>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- File Upload Option -->
            <label class="relative flex p-4 border rounded-md cursor-pointer transition-all"
                   :class="form.type === 'file' ? 'border-primary bg-primary/10' : 'border-border-color hover:border-primary/50'"
                   @click="form.type = 'file'">
                <input type="radio" 
                       name="type" 
                       value="file" 
                       x-model="form.type" 
                       class="sr-only">
                <div class="flex items-center gap-3">
                    <span class="font-bold text-white">File Upload</span>
                </div>
                @if($isEdit && $archive->type === 'file')
                    <span class="ml-auto text-xs text-secondary">(current)</span>
                @endif
            </label>

            <!-- URL Link Option -->
            <label class="relative flex p-4 border rounded-md cursor-pointer transition-all"
                   :class="form.type === 'url' ? 'border-primary bg-primary/10' : 'border-border-color hover:border-primary/50'"
                   @click="form.type = 'url'">
                <input type="radio" 
                       name="type" 
                       value="url" 
                       x-model="form.type" 
                       class="sr-only">
                <div class="flex items-center gap-3">
                    <span class="font-bold text-white">URL Link</span>
                </div>
                @if($isEdit && $archive->type === 'url')
                    <span class="ml-auto text-xs text-secondary">(current)</span>
                @endif
            </label>
        </div>
    </fieldset>

    <!-- A. File Upload -->
    <div x-show="form.type === 'file'" x-transition>
        @if(!$isEdit || $archive->type !== 'file')
            <label class="block text-sm font-medium text-white mb-2">> Select Source File</label>
            <input type="file" 
                   @change="handleFileChange"
                   class="block w-full text-sm text-secondary
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-full file:border-0
                          file:text-sm file:font-semibold
                          file:bg-primary file:text-black
                          hover:file:bg-primary-hover cursor-pointer"/>
            <template x-if="errors.archive_file">
                <p class="mt-1 text-xs text-red-500" x-text="errors.archive_file[0]"></p>
            </template>
        @else
            <!-- File sudah ada & tidak bisa diganti -->
            <div class="p-4 bg-surface-light border border-border-color rounded-md flex items-start gap-3">
                <div>
                    <p class="text-sm text-white font-bold">File Locked</p>
                    <p class="text-xs text-secondary">To replace the file, create a new entry.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- B. URL Links -->
    <div x-show="form.type === 'url'" x-transition x-cloak>
        <label class="block text-sm font-medium text-secondary mb-1">> URL Link(s)</label>
        <textarea x-model="form.links" 
                  rows="5" 
                  class="form-underline font-mono"
                  placeholder="https://example.com/document.pdf&#10;https://another-site.com/info"></textarea>
        <p class="text-xs text-secondary mt-1">One link per line.</p>
        <template x-if="errors.links">
            <p class="mt-1 text-xs text-red-500" x-text="errors.links[0]"></p>
        </template>
    </div>

</div>