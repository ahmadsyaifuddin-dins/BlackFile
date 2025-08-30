<x-app-layout>
    <x-slot:title>
        Access Log
    </x-slot:title>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Access Log: Digital Footprints') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <p class="text-gray-400 mb-6">Rekam jejak digital dari setiap akses ke halaman credit publik milik operatif.</p>

                    <!-- Container untuk tabel agar bisa scroll di mobile -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-gray-900/50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Profile Viewed
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider whitespace-nowrap">
                                        Visitor IP
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Timestamp
                                    </th>
                                    <!-- Kolom ini akan disembunyikan di layar kecil -->
                                    <th scope="col" class="sm:table-cell px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        User Agent
                                    </th>
                                    <th scope="col" class="md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider whitespace-nowrap">
                                        Visitor Identity
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800 divide-y divide-gray-700">
                                @forelse ($views as $view)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                                            {{ $view->owner->name ?? 'Unknown Operative' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ $view->ip_address }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                            {{ \Carbon\Carbon::parse($view->viewed_at)->format('d M Y, H:i:s') }}
                                        </td>
                                        <!-- Tampilkan data di kolom yang sesuai -->
                                        <td class="sm:table-cell px-6 py-4 text-sm text-gray-500 break-words">
                                            {{ $view->user_agent }}
                                        </td>
                                        <td class="md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            @if($view->visitor)
                                                <span class="text-cyan-400">{{ $view->visitor->name }}</span>
                                            @else
                                                <span class="text-gray-500 italic">Guest</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            No access records found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginasi -->
                    <div class="mt-8">
                        {{ $views->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
