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
                    <p class="text-xs text-secondary mt-1 sm:mt-0">Applied:
                        {{ $applicant->created_at->diffForHumans() }}</p>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch gap-2 mt-3 pt-3 border-t border-border-color/50">
                    {{-- Tombol View memicu Modal via Alpine --}}
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
