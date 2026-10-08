<x-app-layout theme="terminal">
    <x-slot:title>
        Agent: {{ $user->codename }}
    </x-slot:title>

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-primary text-glow"> > [ AGENT : {{ $user->codename }} ] </h2>
        <div class="mt-3 sm:mt-2 flex sm:justify-end">
            <a href="{{ route('agents.index') }}"
                class="text-secondary hover:text-primary transition-colors text-sm text-glow">&lt; Back to Directory</a>
        </div>
    </div>

    @if (session('success'))
    <div class="bg-green-900/50 border border-primary text-primary px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if (session('error'))
    <div class="bg-red-900/50 border border-primary text-primary px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    {{-- Agent sekarang memiliki layout flex untuk menampung avatar --}}
    <div class="bg-surface/50 border border-border-color rounded-lg p-6 text-glow flex flex-col sm:flex-row items-start gap-6">
        
        <div class="flex-shrink-0 mx-auto sm:mx-0">
            <img src="{{ $user->avatar ? asset($user->avatar) : 'https://blackfile.xo.je/agent-default.jpg' }}" 
                 alt="Avatar" class="w-32 h-32 object-cover rounded-full border-4 border-border-color shadow-lg">
        </div>
        
        <div class="space-y-4 w-full">
            <p class="text-red-500/80 text-xs">// READ-ONLY // FOR SITUATIONAL AWARENESS ONLY</p>
            <p><span class="text-primary/25">> REAL NAME:</span> {{ $user->name }}</p>
            <p><span class="text-primary/25">> CODENAME:</span> {{ $user->codename }}</p>
            <p><span class="text-primary/25">> DESIGNATION:</span> {{ $user->role->alias }}</p>
            <p><span class="text-primary/25">> SPECIALIZATION:</span> {{ $user->specialization ?? 'N/A' }}</p>
            <p><span class="text-primary/25">> QUOTES:</span> "{{ $user->quotes ?? '...' }}"</p>
            <p><span class="text-primary/25">> HANDLER:</span> {{ $user->parent->codename ?? '[ UNKNOWN ]' }}</p>
            <p><span class="text-primary/25">> LAST ACTIVITY:</span> {{ $user->last_active_at ? $user->last_active_at->diffForHumans() : 'Never' }}</p>
            <p><span class="text-primary/25">> AGENT SINCE:</span> {{ $user->created_at->format('Y-m-d H:i') }}</p>
        </div>
    </div>

    <div class="mt-6 bg-surface/50 border border-border-color rounded-lg p-6 text-glow">
        <h3 class="text-xl font-bold text-primary text-glow mb-4">> [ AGENT PREFERENCES ]</h3>
        
        @if ($user->settings)
            <div class="space-y-4">
                <p><span class="text-primary/25">> LANGUAGE:</span> {{ $localeName }}</p>
                <p><span class="text-primary/25">> DATA PER PAGE:</span> {{ $perPageName }}</p>
                <p><span class="text-primary/25">> TERMINAL THEME:</span> {{ $themeName }}</p>
            </div>
        @else
            <p class="text-green-600/80 text-xs">// NO CUSTOM PREFERENCES SET. USING SYSTEM DEFAULTS.</p>
        @endif
    </div>

    {{-- ============================================================
         [ DIREKTUR ONLY ] CREDENTIAL OVERRIDE
         Panel baru: akses password & one-click reset.
         Tidak menyentuh panel yang sudah ada di atas.
    ============================================================ --}}
    @if (strtolower(Auth::user()->role->name) === 'director')
    @php
        $resetPattern = 'password' . now()->format('dmY');
        $hasCredential = (bool) $user->temp_password;
    @endphp

    <div class="mt-6 bg-surface/50 border border-amber-500/40 rounded-lg p-6 text-glow"
        x-data="{
            revealed: {{ session('auto_reveal') ? 'true' : 'false' }},
            password: @js($user->temp_password),
            copied: false,

            async copyPassword() {
                if (!this.password) return;

                try {
                    await navigator.clipboard.writeText(this.password);
                } catch (err) {
                    const tmp = document.createElement('textarea');
                    tmp.value = this.password;
                    document.body.appendChild(tmp);
                    tmp.select();
                    document.execCommand('copy');
                    document.body.removeChild(tmp);
                }

                this.copied = true;
                if (window.agentAlert) window.agentAlert('success', 'CREDENTIAL COPIED', 'Password copied to clipboard.');
                setTimeout(() => { this.copied = false }, 2500);
            },

            async resetPassword() {
                const proceed = await window.agentConfirm(
                    @js('RESET AGENT PASSWORD'),
                    @js('The current password of ' . $user->codename . ' will be overwritten with today\'s default pattern: ' . $resetPattern . '. The old password stops working immediately. Execute?'),
                    @js('[ EXECUTE RESET ]'),
                    @js('[ ABORT ]')
                );

                if (proceed) this.$refs.resetForm.submit();
            }
        }">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <h3 class="text-xl font-bold text-amber-500 text-glow">> [ CREDENTIAL OVERRIDE ]</h3>
            <span
                class="self-start sm:self-auto text-[10px] font-bold tracking-widest uppercase text-amber-500/80 border border-amber-500/40 bg-amber-500/10 px-2 py-1 rounded">
                <i class="fa-solid fa-shield-halved mr-1"></i> CLEARANCE: DIRECTOR
            </span>
        </div>

        <p class="text-red-500/80 text-xs mb-4">// RESTRICTED // DIRECTOR AUTHORIZATION REQUIRED // HIGHEST AUTHORITY</p>

        <div class="space-y-3">
            <p><span class="text-primary/25">> LOGIN USERNAME:</span> {{ $user->username }}</p>

            @if ($hasCredential)
            <div>
                <p><span class="text-primary/25">> RECORDED PASSWORD:</span></p>

                <div class="flex flex-col sm:flex-row sm:items-center gap-2 mt-1">
                    <code
                        class="flex-1 min-w-0 bg-black/40 border border-amber-500/30 text-amber-400 px-3 py-2 rounded text-sm tracking-widest break-all select-all"
                        x-text="revealed ? password : '••••••••••••••'">••••••••••••••</code>

                    <div class="flex items-center gap-2 sm:flex-shrink-0">
                        <button type="button" @click="revealed = !revealed"
                            :title="revealed ? 'HIDE PASSWORD' : 'REVEAL PASSWORD'"
                            class="cursor-pointer px-3 py-2 bg-amber-600/20 border border-amber-500/50 text-amber-500 hover:bg-amber-600 hover:text-white rounded transition-all duration-200">
                            <i class="fa-solid" :class="revealed ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>

                        <button type="button" @click="copyPassword"
                            class="cursor-pointer px-3 py-2 bg-amber-600/20 border border-amber-500/50 text-amber-500 hover:bg-amber-600 hover:text-white rounded transition-all duration-200 font-mono text-xs font-bold whitespace-nowrap">
                            <span x-show="!copied">[ COPY ]</span>
                            <span x-show="copied" x-cloak>[ COPIED ]</span>
                        </button>
                    </div>
                </div>

                <p class="text-amber-500/50 text-xs mt-2">// REVEAL & COPY ARE DIRECTOR ONLY. RECORD FOLLOWS THE LATEST PASSWORD SET.</p>
            </div>
            @else
            <p class="text-red-500/80 text-xs">// NO CREDENTIAL RECORD ON FILE. EXECUTE RESET TO GENERATE ONE.</p>
            @endif
        </div>

        <div
            class="mt-6 border-t border-dashed border-amber-500/20 pt-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-sm text-amber-500 font-bold tracking-wider">> ONE-CLICK PASSWORD RESET</p>
                <p class="text-xs text-secondary mt-1">
                    Pattern: <span class="text-amber-400 font-bold">{{ $resetPattern }}</span>
                    <span class="opacity-60">(password + DDMMYYYY)</span>
                </p>
            </div>

            <button type="button" @click="resetPassword"
                class="cursor-pointer inline-flex items-center justify-center gap-2 w-full sm:w-auto px-4 py-2 bg-amber-600/20 border border-amber-500 text-amber-500 hover:bg-amber-600 hover:text-white rounded transition-all duration-200 font-mono text-sm font-bold uppercase tracking-wider group">
                <i class="fa-solid fa-rotate"></i>
                <span class="group-hover:animate-pulse">[ RESET NOW ]</span>
            </button>
        </div>

        <form x-ref="resetForm" method="POST" action="{{ route('agents.reset-password', $user) }}" class="hidden">
            @csrf
        </form>
    </div>
    @endif

</x-app-layout>