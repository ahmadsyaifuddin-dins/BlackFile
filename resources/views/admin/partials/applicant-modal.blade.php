<div x-show="isModalOpen" @keydown.escape.window="isModalOpen = false"
    class="fixed inset-0 z-30 flex items-center justify-center p-4" style="display: none;">

    <div x-show="isModalOpen" x-transition.opacity class="absolute inset-0 bg-black/75"></div>

    <div x-show="isModalOpen" x-transition @click.outside="isModalOpen = false"
        class="relative w-full max-w-lg bg-surface border-2 border-border-color rounded-lg shadow-lg">
        <div class="flex items-start justify-between p-4 border-b border-border-color">
            <div>
                <h3 class="text-2xl font-bold text-primary" x-text="applicant?.codename || 'Loading...'"></h3>
                <p class="text-secondary" x-text="applicant?.name"></p>
            </div>
            <button @click="isModalOpen = false" class="text-secondary hover:text-white text-2xl">&times;</button>
        </div>
        <div class="p-4 space-y-2 text-glow">
            <p><strong class="text-primary">> Email:</strong> <span x-text="applicant?.email"></span></p>
            <p><strong class="text-primary">> Username:</strong> <span x-text="applicant?.username"></span></p>
            <p class="pt-2 border-t border-border-color/50 mt-2"><strong class="text-red-500">> Submitted
                    Passcode:</strong> <span class="font-mono bg-base p-1 rounded"
                    x-text="applicant?.temp_password"></span></p>
            <p class="text-xs text-red-500/70">// This passcode is temporary and will be cleared upon approval
                or rejection.</p>
        </div>
        <div class="p-4 border-t border-border-color flex justify-end">
            <button @click="isModalOpen = false"
                class="px-4 py-2 bg-secondary/20 text-secondary hover:bg-secondary/40 font-bold text-sm rounded">CLOSE</button>
        </div>
    </div>
</div>
