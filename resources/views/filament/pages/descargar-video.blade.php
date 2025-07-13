<x-filament::page>
<form wire:submit.prevent="descargar" class="space-y-6 w-full max-w-12xl mx-auto md:px-0">
    <div class="w-full max-w-12xl mx-auto">
        <label for="video_url" class="block text-sm font-medium text-gray-700 dark:text-gray-500 mb-2">
            URL del video (YouTube)
        </label>
        <input
            id="video_url"
            type="url"
            wire:model.defer="video_url"
            placeholder="https://www.youtube.com/watch?v=..."
            required
            class="block w-full rounded-md border border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 shadow-sm focus:border-primary-600 focus:ring focus:ring-primary-300 focus:ring-opacity-50 transition"
        />
        @error('video_url')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="max-w-4xl text-left">
        <x-filament::button type="submit" class="max-w-4xl">
            Descargar Video
        </x-filament::button>
    </div>
</form>


</x-filament::page>
