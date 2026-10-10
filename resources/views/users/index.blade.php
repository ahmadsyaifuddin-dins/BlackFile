<x-app-layout>
    <x-slot:title>Agent Directory</x-slot:title>

    <div class="space-y-6">
        {{-- ===== HEADER ===== --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4">
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-primary text-glow">> [ {{ __('AGENT DIRECTORY') }} ]</h2>
                <p class="text-secondary text-xs font-mono mt-1">{{ __('// PERSONNEL DATABASE // CLEARANCE-CONTROLLED ROSTER') }}</p>
            </div>

            @if(strtolower(Auth::user()->role->name) === 'director')
                <x-button href="{{ route('register.agent') }}">
                    > {{ __('Register New Agent') }}
                </x-button>
            @endif
        </div>

        {{-- ===== FLASH ===== --}}
        @if(session('success'))
            <div class="bg-green-900/50 border border-primary text-primary px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-900/50 border border-primary text-primary px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- ===== STATS ===== --}}
        @php
            $tiles = [
                ['label' => __('TOTAL AGENTS'), 'value' => $stats['total'], 'accent' => 'text-primary', 'icon' => 'fa-users'],
                ['label' => __('ONLINE NOW'), 'value' => $stats['online'], 'accent' => 'text-green-400', 'icon' => 'fa-signal'],
                ['label' => __('DIRECTORS'), 'value' => $stats['directors'], 'accent' => 'text-amber-400', 'icon' => 'fa-shield-halved'],
                ['label' => __('PENDING'), 'value' => $stats['pending'], 'accent' => 'text-yellow-400', 'icon' => 'fa-hourglass-half'],
            ];
        @endphp

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 font-mono">
            @foreach($tiles as $tile)
                <div class="relative bg-surface border border-border-color p-4 overflow-hidden">
                    <span class="absolute top-0 right-0 w-6 h-6 border-t border-r border-border-color"></span>
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-[10px] tracking-widest uppercase text-secondary">{{ $tile['label'] }}</p>
                        <i class="fa-solid {{ $tile['icon'] }} {{ $tile['accent'] }} opacity-70 text-sm"></i>
                    </div>
                    <p @if($tile['label'] === __('ONLINE NOW')) id="stat-online-value" @endif class="text-2xl md:text-3xl font-bold {{ $tile['accent'] }} text-glow mt-2">{{ str_pad($tile['value'], 2, '0', STR_PAD_LEFT) }}</p>
                </div>
            @endforeach
        </div>

        {{-- ===== FILTERS ===== --}}
        <div class="bg-surface border border-border-color p-4 font-mono">
            <p class="text-[10px] tracking-widest text-primary/60 mb-3">{{ __('// ACTIVE QUERY FILTER') }}</p>

            <form action="{{ route('agents.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="md:col-span-2">
                        <x-forms.input name="q" placeholder="{{ __('Search codename, name, username...') }}" value="{{ request('q') }}">
                            <x-slot:icon>
                                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </x-slot:icon>
                        </x-forms.input>
                    </div>

                    <div>
                        <x-forms.select name="role" :options="$roles" :searchable="true" :selected="request('role')" placeholder="{{ __('All Roles') }}" />
                    </div>

                    <div>
                        <x-forms.select name="status" :options="$statuses" :searchable="true" :selected="request('status')" placeholder="{{ __('All Statuses') }}" />
                    </div>

                    <div>
                        <x-forms.select name="sort" :options="$sortOptions" :selected="request('sort')" placeholder="{{ __('Default') }}" />
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 mt-4 pt-4 border-t border-border-color/30">
                    <x-button variant="outline" href="{{ route('agents.index') }}" class="w-full sm:w-auto justify-center">
                        {{ __('[ CLEAR FILTERS ]') }}
                    </x-button>
                    <x-button type="submit" class="w-full sm:w-auto justify-center">
                        {{ __('[ EXECUTE FILTER ]') }}
                    </x-button>
                </div>
            </form>
        </div>

        {{-- ===== ROSTER ===== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @forelse($users as $agent)
                @php
                    $isOnline = $agent->last_active_at && $agent->last_active_at->gte($onlineThreshold);
                @endphp

                <div data-agent-card data-agent-id="{{ $agent->id }}" data-seen="{{ $agent->last_active_at ? $agent->last_active_at->toIso8601String() : '' }}" class="group relative bg-surface border-2 border-border-color hover:border-primary transition-colors duration-300 flex flex-col">
                    {{-- corner brackets --}}
                    <span class="absolute -top-px -left-px w-3 h-3 border-t-2 border-l-2 border-primary opacity-0 group-hover:opacity-100 transition-opacity"></span>
                    <span class="absolute -top-px -right-px w-3 h-3 border-t-2 border-r-2 border-primary opacity-0 group-hover:opacity-100 transition-opacity"></span>
                    <span class="absolute -bottom-px -left-px w-3 h-3 border-b-2 border-l-2 border-primary opacity-0 group-hover:opacity-100 transition-opacity"></span>
                    <span class="absolute -bottom-px -right-px w-3 h-3 border-b-2 border-r-2 border-primary opacity-0 group-hover:opacity-100 transition-opacity"></span>

                    {{-- card header --}}
                    <div class="px-4 py-2 border-b border-border-color flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span data-status-dot class="w-2 h-2 rounded-full {{ $isOnline ? 'bg-green-500 animate-pulse' : 'bg-gray-600' }}"></span>
                            <span data-status-label class="text-[10px] tracking-widest {{ $isOnline ? 'text-green-400' : 'text-secondary' }}">
                                {{ $isOnline ? __('ONLINE') : __('OFFLINE') }}
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            @unless($agent->confirmed)
                                <span class="text-[9px] px-2 py-0.5 border border-yellow-600/70 text-yellow-500 tracking-widest">
                                    {{ __('PENDING') }}
                                </span>
                            @endunless
                            <span class="text-[10px] text-primary/40 tracking-widest">ID-{{ str_pad($agent->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>

                    {{-- identity --}}
                    <div class="p-4 flex items-center gap-4">
                        <div class="relative flex-shrink-0">
                            <img src="{{ $agent->avatar ? asset($agent->avatar) : 'https://blackfile.xo.je/agent-default.jpg' }}"
                                alt="{{ $agent->codename }}"
                                class="w-16 h-16 object-cover rounded-full border-2 border-border-color group-hover:border-primary transition-colors">
                            @if($isOnline)
                                <span data-status-badge class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 border-2 border-surface rounded-full animate-pulse"></span>
                            @endif
                        </div>

                        <div class="min-w-0">
                            <a href="{{ route('agents.show', $agent->id) }}" class="block">
                                <p class="font-bold text-white text-lg text-glow truncate group-hover:text-primary transition-colors">{{ $agent->codename }}</p>
                            </a>
                            <p class="text-primary text-xs font-mono tracking-wider">{{ $agent->role->alias ?? $agent->role->name }}</p>
                            <p class="text-secondary text-xs truncate">{{ $agent->specialization ?? '—' }}</p>
                        </div>
                    </div>

                    {{-- data --}}
                    <div class="px-4 pb-3 text-xs font-mono space-y-1 text-secondary/90 flex-grow">
                        <p class="truncate"><span class="text-primary/40">> {{ __('REAL NAME') }}:</span> {{ $agent->name }}</p>
                        <p class="truncate"><span class="text-primary/40">> {{ __('USERNAME') }}:</span> {{ $agent->username }}</p>
                        <p class="truncate"><span class="text-primary/40">> {{ __('LAST ACTIVITY') }}:</span> <span data-activity data-fallback="{{ $agent->last_active_at ? $agent->last_active_at->diffForHumans() : __('Never') }}">{{ $agent->last_active_at ? $agent->last_active_at->diffForHumans() : __('Never') }}</span></p>
                        <p class="truncate"><span class="text-primary/40">> {{ __('AGENT SINCE') }}:</span> {{ $agent->created_at->format('Y-m-d') }}</p>
                    </div>

                    {{-- actions --}}
                    <div class="px-4 py-2 border-t border-border-color flex items-center justify-between gap-3">
                        <a href="{{ route('agents.show', $agent->id) }}"
                            class="text-primary text-xs font-bold font-mono hover:text-white transition-colors whitespace-nowrap">
                            > {{ __('ACCESS DOSSIER') }}
                        </a>

                        @if(strtolower(Auth::user()->role->name) === 'director')
                            <div class="flex items-center gap-3">
                                <a href="{{ route('agents.edit', $agent->id) }}"
                                    class="text-blue-400 hover:text-blue-300 text-xs font-mono font-bold" title="Edit Agent">
                                    {{ __('[ EDIT ]') }}
                                </a>
                                <x-button.delete :action="route('agents.destroy', $agent->id)" title="TERMINATE AGENT?"
                                    message="Confirm termination of agent {{ $agent->codename }}? Access will be revoked immediately."
                                    target="{{ $agent->codename }}">
                                    {{ __('[ DELETE ]') }}
                                </x-button.delete>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="md:col-span-2 xl:col-span-3 text-center py-16 border-2 border-dashed border-border-color">
                    <p class="text-secondary font-mono text-lg">{{ __('[ NO AGENT RECORDS FOUND IN DATABASE ]') }}</p>
                </div>
            @endforelse
        </div>

        <div class="mt-2">
            {{ $users->links() }}
        </div>
    </div>

    @push('scripts')
    <script>
        (function () {
            const POLL_MS = 20000;
            const PRESENCE_URL = @json(route('agents.presence'));
            const RTF = new Intl.RelativeTimeFormat(@json(app()->getLocale()), { numeric: 'auto' });
            const LBL_ON = @json(__('ONLINE'));
            const LBL_OFF = @json(__('OFFLINE'));
            const ACT_LABEL = @json(__('LAST ACTIVITY'));

            const cards = Array.from(document.querySelectorAll('[data-agent-card]'));
            const onlineValueEl = document.getElementById('stat-online-value');
            if (!cards.length && !onlineValueEl) return;

            const seen = new Map();
            const fallbacks = new Map();

            cards.forEach(function (card) {
                const id = String(card.dataset.agentId);
                const iso = card.dataset.seen || null;
                if (iso) { seen.set(id, iso); } else { seen.set(id, null); }
                const act = card.querySelector('[data-activity]');
                fallbacks.set(id, act ? act.dataset.fallback : '');
            });

            function agoIso(iso) {
                if (!iso) return null;
                let diffMs = Date.now() - new Date(iso).getTime();
                if (diffMs < 0) diffMs = 0;
                const secs = Math.floor(diffMs / 1000);
                if (secs < 60) return RTF.format(-secs, 'second');
                const mins = Math.floor(secs / 60);
                if (mins < 60) return RTF.format(-mins, 'minute');
                const hours = Math.floor(mins / 60);
                if (hours < 24) return RTF.format(-hours, 'hour');
                return RTF.format(-Math.floor(hours / 24), 'day');
            }

            function applyState(card, online, iso) {
                const dot = card.querySelector('[data-status-dot]');
                const label = card.querySelector('[data-status-label]');
                const badge = card.querySelector('[data-status-badge]');
                const act = card.querySelector('[data-activity]');
                const id = String(card.dataset.agentId);

                if (dot) {
                    dot.classList.toggle('bg-green-500', online);
                    dot.classList.toggle('animate-pulse', online);
                    dot.classList.toggle('bg-gray-600', !online);
                }

                if (label) {
                    label.textContent = online ? LBL_ON : LBL_OFF;
                    label.classList.toggle('text-green-400', online);
                    label.classList.toggle('text-secondary', !online);
                }

                if (badge) badge.classList.toggle('hidden', !online);

                if (act) {
                    const rel = online ? agoIso(iso) : (iso ? agoIso(iso) : fallbacks.get(id));
                    if (rel) act.textContent = rel;
                }
            }

            function poll() {
                fetch(PRESENCE_URL, { headers: { 'Accept': 'application/json' }, cache: 'no-store' })
                    .then(function (res) {
                        if (!res.ok) throw new Error('bad status');
                        return res.json();
                    })
                    .then(function (data) {
                        if (!data || typeof data !== 'object') return;

                        if (data.agents && typeof data.agents === 'object') {
                            Object.keys(data.agents).forEach(function (idStr) {
                                const iso = data.agents[idStr];
                                if (iso && typeof iso === 'string') {
                                    seen.set(idStr, iso);
                                } else if (!seen.has(idStr)) {
                                    seen.set(idStr, null);
                                }
                            });
                        }

                        const onlineSet = new Set((data.online_ids || []).map(String));
                        cards.forEach(function (card) {
                            const id = String(card.dataset.agentId);
                            applyState(card, onlineSet.has(id), seen.get(id) || null);
                        });

                        if (onlineValueEl && typeof data.online_count === 'number') {
                            onlineValueEl.textContent = String(data.online_count).padStart(2, '0');
                        }
                    })
                    .catch(function () {});
            }

            cards.forEach(function (card) {
                const id = String(card.dataset.agentId);
                const iso = seen.get(id);
                const online = !!iso && (Date.now() - new Date(iso).getTime() <= 120000);
                applyState(card, online, iso);
            });

            poll();
            setInterval(poll, POLL_MS);
        })();
    </script>
    @endpush
</x-app-layout>
