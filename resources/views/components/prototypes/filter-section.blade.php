@props(['projectTypes', 'users'])

<div class="bg-surface p-4 rounded-lg mb-4 border border-border-color font-mono">
    <form action="{{ route('prototypes.index') }}" method="GET">
        
        {{-- 
            [DATA PREPARATION]
            Kita siapkan array options di sini agar kode HTML lebih bersih
        --}}
        @php
            // 1. Status Options (Manual Array)
            $statusOptions = [
                'PLANNING'       => 'PLANNING',
                'IN_DEVELOPMENT' => 'IN_DEVELOPMENT',
                'COMPLETED'      => 'COMPLETED',
                'ON_HOLD'        => 'ON_HOLD',
                'ARCHIVED'       => 'ARCHIVED',
            ];

            // 2. Developer Options (User Collection -> Array)
            $devOptions = $users->pluck('name', 'id')->toArray();

            // 3. Project Type Options (Array Strings -> Array Key-Value)
            // Asumsi $projectTypes adalah array sederhana ['Type A', 'Type B']
            // Kita perlu ubah jadi ['Type A' => 'Type A']
            $typeOptions = collect($projectTypes)->mapWithKeys(fn($item) => [$item => $item])->toArray();
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 items-end">
            
            {{-- Input Search --}}
            <div class="sm:col-span-2 md:col-span-1">
                <label for="search" class="block text-primary text-sm mb-1">> Search</label>
                <x-forms.input 
                    type="search" 
                    name="search" 
                    id="search" 
                    value="{{ request('search') }}" 
                    placeholder="Codename..."
                >
                    {{-- Slot Icon --}}
                    <x-slot:icon>
                        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </x-slot:icon>
                </x-forms.input>
            </div>

            {{-- Filter Status --}}
            <div>
                <x-forms.select 
                    label="> Status"
                    name="status" 
                    id="status"
                    :options="$statusOptions" 
                    :selected="request('status')" 
                    placeholder="All Statuses" 
                />
            </div>
          
            {{-- Filter Developer --}}
            <div>
                <x-forms.select 
                    label="> Developer"
                    name="user_id" 
                    id="user_id"
                    :options="$devOptions" 
                    :selected="request('user_id')" 
                    placeholder="All Devs" 
                    :searchable="true"
                />
            </div>
            
            {{-- Filter Project Type --}}
            <div>
                <x-forms.select 
                    label="> Type"
                    name="project_type" 
                    id="project_type"
                    :options="$typeOptions" 
                    :selected="request('project_type')" 
                    placeholder="All Types" 
                />
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex gap-2 w-full sm:col-span-2 md:col-span-1">
                <x-button type="submit" class="w-full justify-center">
                    FILTER
                </x-button>
                
                <x-button href="{{ route('prototypes.index') }}" variant="outline" class="w-full justify-center">
                    RESET
                </x-button>
            </div>
        </div>
    </form>
</div>