<x-app-layout>
    <x-slot:title>
        Command Center
    </x-slot:title>

    <h2 class="text-2xl font-bold text-primary mb-6"> > [ COMMAND CENTER ] </h2>

    @if(session('success'))
    <div class="mb-4 bg-primary/10 border-l-4 border-primary text-primary-hover p-4 rounded-r-lg" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-data="{ isModalOpen: false, applicant: null }">
        <!-- Kolom Kiri: Pending Applicants -->
        <div class="bg-surface border border-border-color rounded-lg p-4">
            <h3 class="text-xl font-bold text-white mb-4 border-b border-border-color pb-2">> PENDING APPLICATIONS</h3>
            <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                @forelse($pendingApplicants as $applicant)
                <div class="bg-base p-3 rounded-md">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-bold text-primary-hover">{{ $applicant->codename }}</p>
                            <p class="text-xs text-secondary">{{ $applicant->name }} ({{ $applicant->email }})</p>
                        </div>
                        <p class="text-xs text-secondary mt-1 sm:mt-0">Applied: {{
                            $applicant->created_at->diffForHumans() }}</p>
                    </div>
                    {{-- [DIUBAH] Tombol sekarang responsif dan selalu di baris baru --}}
                    <div
                        class="flex flex-col sm:flex-row items-stretch gap-2 mt-3 pt-3 border-t border-border-color/50">
                        <button @click="isModalOpen = true; applicant = {{ $applicant }}"
                            class="cursor-pointer flex-1 text-center px-3 py-1 bg-gray-600/20 text-gray-400 hover:bg-gray-600 hover:text-white font-bold text-xs rounded transition-colors">[
                            VIEW ]</button>
                        <form action="{{ route('admin.users.approve', $applicant) }}" method="POST" class="flex-1">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="cursor-pointer w-full text-center px-3 py-1 bg-green-600/20 text-green-400 hover:bg-green-600 hover:text-white font-bold text-xs rounded transition-colors">[
                                APPROVE ]</button>
                        </form>
                        <form action="{{ route('admin.users.reject', $applicant) }}" method="POST"
                            onsubmit="return confirm('Confirm rejection?')" class="flex-1">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="cursor-pointer w-full text-center px-3 py-1 bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white font-bold text-xs rounded transition-colors">[
                                REJECT ]</button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-secondary text-center p-4">[ NO PENDING APPLICATIONS ]</p>
                @endforelse
            </div>
        </div>

        <!-- Kolom Kanan: Invite Tokens -->
        <div class="bg-surface border border-border-color rounded-lg p-4">
            <div class="flex items-center justify-between mb-4 border-b border-border-color pb-2">
                <p class="text-sm sm:text-xl font-bold text-white">> INVITE TOKENS</p>
                <form action="{{ route('admin.invites.generate') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-3 py-1 bg-primary whitespace-nowrap sm:text-base hover:bg-primary-hover font-bold tracking-widest rounded-md text-xs cursor-pointer">[
                        + GENERATE TOKEN ]</button>
                </form>
            </div>
            <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                @forelse($invites as $invite)
                {{-- [DIUBAH] Tambahkan x-data untuk state penyalinan --}}
                <div class="bg-base p-3 rounded-md" x-data="{ copied: false }">
                    <div class="flex items-center justify-between gap-4">
                        <p class="font-mono text-primary-hover break-all" x-ref="token_{{ $invite->id }}">{{
                            $invite->code }}</p>
                        {{-- [BARU] Tombol Salin --}}
                        <button @click="
                                navigator.clipboard.writeText($refs.token_{{ $invite->id }}.innerText);
                                copied = true;
                                setTimeout(() => { copied = false }, 2000);
                            " class="px-3 py-1 text-xs font-bold rounded transition-colors flex-shrink-0"
                            :class="copied ? 'bg-green-600 text-white' : 'bg-secondary/20 text-secondary hover:bg-secondary/40'">
                            <span x-show="!copied">[ COPY ]</span>
                            <span x-show="copied">COPIED!</span>
                        </button>
                    </div>
                    <div class="text-xs text-secondary mt-1 flex justify-between">
                        <span>Generated by: {{ $invite->creator->codename }}</span>
                        @if($invite->used)
                        <span class="text-red-500">USED</span>
                        @elseif($invite->expires_at && $invite->expires_at->isPast())
                        <span class="text-yellow-500">EXPIRED</span>
                        @else
                        <span class="text-green-500">AVAILABLE</span>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-secondary text-center p-4">[ NO INVITE TOKENS GENERATED ]</p>
                @endforelse
            </div>
        </div>
        <!-- [BARU] Modal untuk Detail Applicant -->
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
                    <button @click="isModalOpen = false"
                        class="text-secondary hover:text-white text-2xl">&times;</button>
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
    </div>
</x-app-layout>