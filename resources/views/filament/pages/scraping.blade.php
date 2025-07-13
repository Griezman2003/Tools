<x-filament::page class="max-w-full px-6">
    <div class="mb-4">
        <label for="animeSlug" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
            Ingrese el slug del anime (ejemplo: naruto)
        </label>
        <input
            wire:model.defer="animeSlug"
            id="animeSlug"
            type="text"
            class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
        />
        <button
            wire:click="loadEpisodes"
            class="mt-2 inline-block px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
        >
            Buscar
        </button>
    </div>

    <div wire:loading wire:target="loadEpisodes" class="mt-2 text-gray-600 dark:text-gray-400">
        Cargando episodios...
    </div>

    @if (count($episodes) > 0)
        <h3 class="mt-6 mb-2 text-lg font-semibold text-gray-900 dark:text-gray-100">Episodios encontrados:</h3>
        <ul class="list-disc list-inside space-y-1">
            @foreach ($episodes as $episode)
                <li>
                    <button
                        wire:click="loadEpisodeVideo(@js($episode['href']))"
                        class="text-indigo-600 dark:text-indigo-400 underline hover:text-indigo-800 dark:hover:text-indigo-600 focus:outline-none"
                    >
                        {{ $episode['title'] }}
                    </button>
                </li>
            @endforeach
        </ul>
    @endif

    <div wire:loading wire:target="loadEpisodeVideo" class="mt-4 text-gray-600 dark:text-gray-400">
        Cargando video...
    </div>

    @if ($selectedEpisodeUrl)
        <div class="mt-6 w-full" style="aspect-ratio: 16 / 9;">
            <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-gray-100">Reproduciendo episodio:</h3>
            <iframe
                src="{{ $selectedEpisodeUrl }}"
                class="w-full h-full rounded border border-gray-300 dark:border-gray-700"
                frameborder="0"
                allowfullscreen
            ></iframe>
        </div>
    @endif
</x-filament::page>
