@props([
    'description' => '',
    'abilities' => '',
    'weaknesses' => ''
])

<div class="space-y-6">
    <div>
        <label for="description" class="text-primary">> DESCRIPTION_LOG:</label>
        <textarea name="description" id="description" rows="5" required placeholder="Begin data entry..."
            class="mt-1 w-full bg-base border border-border-color focus:border-primary focus:ring-primary focus:ring-opacity-50 text-secondary">{{ old('description', $description) }}</textarea>
    </div>
    <div>
        <label for="abilities" class="text-primary">> ABILITIES_ANALYSIS:</label>
        <textarea name="abilities" id="abilities" rows="3" placeholder="Document known capabilities..."
            class="mt-1 w-full bg-base border border-border-color focus:border-primary focus:ring-primary focus:ring-opacity-50 text-secondary">{{ old('abilities', $abilities) }}</textarea>
    </div>
    <div>
        <label for="weaknesses" class="text-red-400">> WEAKNESSES_EXPLOIT:</label>
        <textarea name="weaknesses" id="weaknesses" rows="3" placeholder="Document known vulnerabilities..."
            class="mt-1 w-full bg-red-900/20 border border-red-500/30 focus:border-red-500 focus:ring-red-500 focus:ring-opacity-50 text-red-300/80">{{ old('weaknesses', $weaknesses) }}</textarea>
    </div>
</div>