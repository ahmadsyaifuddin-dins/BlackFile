@props([
    'description' => '',
    'abilities' => '',
    'weaknesses' => ''
])

<div class="space-y-6">
    
    {{-- 1. Description (Default Style) --}}
    <x-forms.textarea 
        label="> DESCRIPTION_LOG:"
        name="description"
        id="description"
        rows="5"
        required
        placeholder="Begin data entry..."
        :value="$description"
    />

    {{-- 2. Abilities (Default Style) --}}
    <x-forms.textarea 
        label="> ABILITIES_ANALYSIS:"
        name="abilities"
        id="abilities"
        rows="3"
        placeholder="Document known capabilities..."
        :value="$abilities"
    />

    {{-- 3. Weaknesses (Danger Style - Merah) --}}
    <x-forms.textarea 
        label="> WEAKNESSES_EXPLOIT:"
        name="weaknesses"
        id="weaknesses"
        rows="3"
        variant="danger" 
        placeholder="Document known vulnerabilities..."
        :value="$weaknesses"
    />

</div>