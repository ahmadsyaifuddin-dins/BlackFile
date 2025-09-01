<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Default Music Library
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Form Tambah Musik -->
            <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-white mb-4">Add New Default Music</h3>
                <form action="{{ route('default-music.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input type="text" name="name" placeholder="Music Name" class="bg-gray-900 text-white rounded-md border-gray-700" required>
                        <input type="file" name="music_file" class="text-gray-400 file:bg-primary file:border-0 file:text-white file:rounded-md file:px-4 file:py-2" required>
                        <button type="submit" class="bg-primary hover:bg-primary-hover text-white font-bold py-2 px-4 rounded">Upload</button>
                    </div>
                </form>
            </div>

            <!-- Daftar Musik -->
            <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg">
                 <h3 class="text-lg font-medium text-white mb-4">Current Library</h3>
                <div class="space-y-3">
                    @foreach($musics as $music)
                        <div class="flex justify-between items-center p-3 bg-gray-900/50 rounded-md">
                            <span class="text-white">{{ $music->name }}</span>
                            <form action="{{ route('default-music.destroy', $music) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-400">Remove</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>