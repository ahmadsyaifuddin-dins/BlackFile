<x-app-layout title="Register New Entity">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-3">
        <h2 class="text-2xl font-bold text-primary font-mono tracking-wider">
            > REGISTER NEW ENTITY
        </h2>
        <a href="{{ route('entities.index') }}" class="w-full sm:w-auto text-center bg-surface-light border border-border-color text-secondary font-bold py-2 px-4 hover:text-primary hover:border-primary transition-colors">
            &lt; ABORT MISSION
        </a>
    </div>

    {{-- Form Container --}}
    <div class="bg-surface border border-border-color p-6">
        <form action="{{ route('entities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 font-mono">
            @csrf

            {{-- Baris 1: Name & Codename --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="flex items-center gap-4">
                    <label for="name" class="flex-shrink-0 text-primary">> NAME:</label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="Input entity name..."
                           class="w-full bg-transparent border-0 border-b-2 border-border-color focus:border-primary focus:ring-0 text-white">
                </div>
                <div class="flex items-center gap-4">
                    <label for="codename" class="flex-shrink-0 text-primary">> CODENAME:</label>
                    <input type="text" name="codename" id="codename" value="{{ old('codename') }}" placeholder="e.g., SCP-173, Subject-Alpha..."
                           class="w-full bg-transparent border-0 border-b-2 border-border-color focus:border-primary focus:ring-0 text-white">
                </div>
            </div>

            {{-- Baris 2: Category, Rank, Origin, Status --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Category Dropdown --}}
                <div>
                    <label for="category" class="block text-primary mb-1">> CATEGORY:</label>
                    @php $categories = config('blackfile.entity_categories'); @endphp
                    <div x-data="{ open: false, selected: '{{ old('category', $categories[0] ?? '') }}' }" class="relative font-mono">
                        <input type="hidden" name="category" x-bind:value="selected">
                        <button type="button" @click="open = !open" class="relative w-full bg-base border-2 border-border-color text-left text-white p-2 pr-10 focus:outline-none focus:border-primary">
                            <span x-text="selected"></span>
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 7.03 7.78a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l2.97-2.97a.75.75 0 111.06 1.06l-3.5 3.5a.75.75 0 01-1.06 0l-3.5-3.5a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute z-10 mt-1 w-full max-h-60 overflow-y-auto bg-black border border-border-color shadow-lg" style="display: none;">
                            @foreach($categories as $category)
                                <a @click="selected = '{{ addslashes($category) }}'; open = false" class="block px-4 py-2 text-sm cursor-pointer hover:bg-primary hover:text-green-400" :class="{'bg-primary text-green-600': selected === '{{ addslashes($category) }}'}">{{ $category }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            
                {{-- Rank Dropdown --}}
                <div>
                    <label for="rank" class="block text-primary mb-1">> RANK:</label>
                    @php $ranks = config('blackfile.entity_ranks'); @endphp
                    <div x-data="{ open: false, selected: '{{ old('rank', $ranks[0] ?? '') }}' }" class="relative font-mono">
                        <input type="hidden" name="rank" x-bind:value="selected">
                        <button type="button" @click="open = !open" class="relative w-full bg-base border-2 border-border-color text-left text-white p-2 pr-10 focus:outline-none focus:border-primary">
                            <span x-text="selected"></span>
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 7.03 7.78a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l2.97-2.97a.75.75 0 111.06 1.06l-3.5 3.5a.75.75 0 01-1.06 0l-3.5-3.5a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute z-10 mt-1 w-full max-h-60 overflow-y-auto bg-black border border-border-color shadow-lg" style="display: none;">
                            @foreach($ranks as $rank)
                                <a @click="selected = '{{ addslashes($rank) }}'; open = false" class="block px-4 py-2 text-sm cursor-pointer hover:bg-primary hover:text-green-400" :class="{'bg-primary text-green-600': selected === '{{ addslashes($rank) }}'}">{{ $rank }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            
                {{-- Origin Dropdown --}}
                <div>
                    <label for="origin" class="block text-primary mb-1">> ORIGIN:</label>
                    @php $origins = config('blackfile.entity_origins'); @endphp
                    <div x-data="{ open: false, selected: '{{ old('origin', $origins[0] ?? '') }}' }" class="relative font-mono">
                        <input type="hidden" name="origin" x-bind:value="selected">
                        <button type="button" @click="open = !open" class="relative w-full bg-base border-2 border-border-color text-left text-white p-2 pr-10 focus:outline-none focus:border-primary">
                            <span x-text="selected"></span>
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 7.03 7.78a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l2.97-2.97a.75.75 0 111.06 1.06l-3.5 3.5a.75.75 0 01-1.06 0l-3.5-3.5a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute z-10 mt-1 w-full max-h-60 overflow-y-auto bg-black border border-border-color shadow-lg" style="display: none;">
                            @foreach($origins as $origin)
                                <a @click="selected = '{{ addslashes($origin) }}'; open = false" class="block px-4 py-2 text-sm cursor-pointer hover:bg-primary hover:text-green-400" :class="{'bg-primary text-green-600': selected === '{{ addslashes($origin) }}'}">{{ $origin }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            
                {{-- Status Dropdown (yang sudah ada) --}}
                <div>
                    <label for="status" class="block text-primary mb-1">> STATUS:</label>
                    <div x-data="{ open: false, selected: 'UNKNOWN' }" class="relative font-mono">
                        <input type="hidden" name="status" x-bind:value="selected">
                        <button type="button" @click="open = !open" class="relative w-full bg-base border-2 border-border-color text-left text-white p-2 pr-10 focus:outline-none focus:border-primary">
                            <span x-text="selected"></span>
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 7.03 7.78a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l2.97-2.97a.75.75 0 111.06 1.06l-3.5 3.5a.75.75 0 01-1.06 0l-3.5-3.5a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute z-10 mt-1 w-full bg-black border border-border-color shadow-lg" style="display: none;">
                            <a @click="selected = 'UNKNOWN'; open = false" class="block px-4 py-2 text-sm cursor-pointer hover:bg-primary hover:text-white" :class="{'bg-primary text-white': selected === 'UNKNOWN'}">UNKNOWN</a>
                            <a @click="selected = 'ACTIVE'; open = false" class="block px-4 py-2 text-sm cursor-pointer hover:bg-primary hover:text-red-600" :class="{'bg-primary text-red-600': selected === 'ACTIVE'}">ACTIVE</a>
                            <a @click="selected = 'CONTAINED'; open = false" class="block px-4 py-2 text-sm cursor-pointer hover:bg-primary hover:text-yellow-600" :class="{'bg-primary text-yellow-600': selected === 'CONTAINED'}">CONTAINED</a>
                            <a @click="selected = 'NEUTRALIZED'; open = false" class="block px-4 py-2 text-sm cursor-pointer hover:bg-primary hover:text-green-600" :class="{'bg-primary text-green-600': selected === 'NEUTRALIZED'}">NEUTRALIZED</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Textareas --}}
            <div class="space-y-6">
                <div>
                    <label for="description" class="text-primary">> DESCRIPTION_LOG:</label>
                    <textarea name="description" id="description" rows="5" required placeholder="Begin data entry..."
                              class="mt-1 w-full bg-base border border-border-color focus:border-primary focus:ring-primary focus:ring-opacity-50 text-secondary">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label for="abilities" class="text-primary">> ABILITIES_ANALYSIS:</label>
                    <textarea name="abilities" id="abilities" rows="3" placeholder="Document known capabilities..."
                              class="mt-1 w-full bg-base border border-border-color focus:border-primary focus:ring-primary focus:ring-opacity-50 text-secondary">{{ old('abilities') }}</textarea>
                </div>
                <div>
                    <label for="weaknesses" class="text-red-400">> WEAKNESSES_EXPLOIT:</label>
                    <textarea name="weaknesses" id="weaknesses" rows="3" placeholder="Document known vulnerabilities..."
                              class="mt-1 w-full bg-red-900/20 border border-red-500/30 focus:border-red-500 focus:ring-red-500 focus:ring-opacity-50 text-red-300/80">{{ old('weaknesses') }}</textarea>
                </div>
            </div>
            
            {{-- Image Upload --}}
            <div class="pt-6 border-t-2 border-dashed border-border-color">
                 <label class="block text-primary mb-2">> ATTACH_VISUAL_EVIDENCE (Multi-upload)</label>
                 <input type="file" name="images[]" multiple class="block w-full text-sm text-secondary file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-hover cursor-pointer">
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-primary text-primary font-bold py-3 px-8 rounded-none hover:bg-primary-hover transition-colors text-lg tracking-widest cursor-pointer">
                    > EXECUTE
                </button>
            </div>
        </form>
    </div>
</x-app-layout>