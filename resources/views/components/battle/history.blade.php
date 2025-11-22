@props(['history'])

<div class="bg-surface/50 border border-gray-800 overflow-hidden rounded">
    <h3 class="text-primary text-xs font-bold tracking-widest p-4 uppercase border-b border-gray-800">
        Recent Conflict Archives
    </h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-400 min-w-[800px] md:min-w-0">
            <thead class="text-xs text-gray-500 uppercase bg-black/50 border-b border-gray-800">
                <tr>
                    <th class="px-6 py-3 whitespace-nowrap">Time</th>
                    <th class="px-6 py-3 whitespace-nowrap">Alpha</th>
                    <th class="px-6 py-3 whitespace-nowrap">Omega</th>
                    <th class="px-6 py-3 text-center whitespace-nowrap">Scenario</th>
                    <th class="px-6 py-3 text-right whitespace-nowrap">Outcome</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($history as $log)
                    <tr onclick="window.location='{{ route('battle.show', $log->id) }}'"
                        class="hover:bg-white/10 transition-colors cursor-pointer group border-l-2 border-transparent hover:border-primary">
                        <td class="px-6 py-4 font-mono text-xs whitespace-nowrap">
                            {{ $log->created_at->diffForHumans() }}
                        </td>
                        <td
                            class="px-6 py-4 font-bold whitespace-nowrap {{ $log->winner_id == $log->attacker_id ? 'text-primary' : 'text-gray-500' }}">
                            {{ $log->attacker->name }}
                        </td>
                        <td
                            class="px-6 py-4 font-bold whitespace-nowrap {{ $log->winner_id == $log->defender_id ? 'text-primary' : 'text-gray-500' }}">
                            {{ $log->defender->name }}
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <span
                                class="px-2 py-1 text-[10px] rounded border {{ $log->scenario_type === 'HAZARD_TEST' ? 'border-yellow-600 text-yellow-500' : 'border-blue-900 text-blue-400' }}">
                                {{ $log->scenario_type ?? 'SIMULATION' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end items-center gap-3">
                                <div>
                                    <div class="text-primary font-bold uppercase text-xs mb-1">
                                        WINNER: {{ $log->winner->name ?? 'UNKNOWN' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        PROB: <span class="text-white font-mono">{{ $log->win_probability }}%</span>
                                    </div>
                                </div>
                                {{-- Icon Panah (Muncul saat hover) --}}
                                <svg class="w-4 h-4 text-gray-500 opacity-0 group-hover:opacity-100 transition-all -translate-x-2 group-hover:translate-x-0"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-600 italic">
                            [ NO RECORDS ]
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
