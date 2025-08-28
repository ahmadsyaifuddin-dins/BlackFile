<x-app-layout>
    <x-slot:title>
        Edit Your End Credits
    </x-slot:title>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Edit Your End Credits
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ url()->previous() }}" class="text-sm text-gray-400 hover:text-gray-200"> <-- Back to Credits</a>
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('credits.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    @php
                        $formattedCredits = $credits->map(function($credit) {
                            return [
                                'id' => $credit->id,
                                'role' => $credit->role,
                                'names' => $credit->names,
                                'logos' => $credit->logos ?? [],
                            ];
                        });
                        // Ambil path musik dari credit pertama yang memilikinya
                        $musicPath = $credits->whereNotNull('music_path')->first()->music_path ?? null;
                    @endphp
                    @include('credits._form_dynamic', [
                        'credits' => $formattedCredits->toJson(),
                        'musicPath' => $musicPath
                    ])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
