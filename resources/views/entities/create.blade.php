<x-app-layout title="Register New Entity">
    <h2 class="text-2xl font-bold text-primary mb-6">Register New Entity // Data Input</h2>

    <div class="bg-surface border border-border-color rounded-lg p-6">
        <form action="{{ route('entities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            {{-- Name & Codename --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-secondary">> NAME</label>
                    <input type="text" name="name" id="name" required class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="codename" class="block text-sm font-medium text-secondary">> CODENAME (Optional)</label>
                    <input type="text" name="codename" id="codename" class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>
            </div>

            {{-- Category, Rank, Origin, Status --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="category" class="block text-sm font-medium text-secondary">> CATEGORY</label>
                    <input type="text" name="category" id="category" required class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>
                 <div>
                    <label for="rank" class="block text-sm font-medium text-secondary">> RANK</label>
                    <input type="text" name="rank" id="rank" class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="origin" class="block text-sm font-medium text-secondary">> ORIGIN</label>
                    <input type="text" name="origin" id="origin" class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-secondary">> STATUS</label>
                    <select name="status" id="status" class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">
                        <option>UNKNOWN</option>
                        <option>ACTIVE</option>
                        <option>CONTAINED</option>
                        <option>NEUTRALIZED</option>
                    </select>
                </div>
            </div>

            {{-- Descriptions --}}
            <div>
                <label for="description" class="block text-sm font-medium text-secondary">> DESCRIPTION</label>
                <textarea name="description" id="description" rows="5" required class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary"></textarea>
            </div>
            <div>
                <label for="abilities" class="block text-sm font-medium text-secondary">> ABILITIES</label>
                <textarea name="abilities" id="abilities" rows="3" class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary"></textarea>
            </div>
            <div>
                <label for="weaknesses" class="block text-sm font-medium text-secondary">> WEAKNESSES</label>
                <textarea name="weaknesses" id="weaknesses" rows="3" class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary"></textarea>
            </div>

            {{-- Image Upload --}}
            <div>
                 <label class="block text-sm font-medium text-secondary">> IMAGE ATTACHMENTS (Multi-upload)</label>
                 <input type="file" name="images[]" multiple class="mt-1 block w-full text-sm text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-black hover:file:bg-primary-hover">
            </div>
            {{-- Note: We simplify by not having per-image captions on create form --}}

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-primary text-black font-bold py-2 px-6 rounded hover:bg-primary-hover transition-colors">
                    > Save Entity
                </button>
            </div>
        </form>
    </div>
</x-app-layout>