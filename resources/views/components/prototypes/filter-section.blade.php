@props(['projectTypes','users'])

<div class="bg-gray-800 p-4 rounded-lg mb-4 border border-gray-700">
    <form action="{{ route('prototypes.index') }}" method="GET" class="font-mono">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Input Search --}}
            <div>
                <label for="search" class="block text-gray-400 text-sm mb-1">> Search Codename/Name</label>
                <input type="search" name="search" id="search" value="{{ request('search') }}" class="form-input-dark" placeholder="e.g., Project Titan">
            </div>

            {{-- Filter Status --}}
            <div>
                <label for="status" class="block text-gray-400 text-sm mb-1">> Filter by Status</label>
                <select name="status" id="status" class="form-input-dark">
                    <option value="">All Statuses</option>
                    @foreach (['PLANNING', 'IN_DEVELOPMENT', 'COMPLETED', 'ON_HOLD', 'ARCHIVED'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>
          
            {{-- Filter Dev by --}}
            <div>
                <label for="user_id" class="block text-gray-400 text-sm mb-1">> Filter by Developers</label>
                <select name="user_id" id="user_id" class="form-input-dark">
                    <option value="">All Developers</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            {{-- Filter Project Type --}}
            <div>
                <label for="project_type" class="block text-gray-400 text-sm mb-1">> Filter by Type</label>
                <select name="project_type" id="project_type" class="form-input-dark">
                    <option value="">All Types</option>
                    @foreach ($projectTypes as $type)
                        <option value="{{ $type }}" {{ request('project_type') == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-end gap-2">
                <button type="submit" class="cursor-pointer w-full bg-primary hover:bg-green-800 text-white font-bold py-2 px-4 rounded transition">
                    FILTER
                </button>
                <a href="{{ route('prototypes.index') }}" class="w-full text-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">
                    RESET
                </a>
            </div>
        </div>
    </form>
</div>