<x-filament::page>
   <form wire:submit.prevent="buscar" class="space-y-6 w-full max-w-12xl mx-auto md:px-0">
    <div class="w-full max-w-12xl mx-auto">
        <label for="searchTerm" class="block text-sm font-medium text-gray-700 dark:text-gray-500 mb-2">
            Buscar anime
        </label>
        <input
            id="searchTerm"
            type="text"
            wire:model.defer="searchTerm"
            placeholder="Buscar anime... [Enter]"
            required
            class="block w-full rounded-md border border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 shadow-sm focus:border-primary-600 focus:ring focus:ring-primary-300 focus:ring-opacity-50 transition"
        />
        @error('searchTerm')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="max-w-4xl text-left">
        <x-filament::button type="submit" class="max-w-4xl">
            Buscar
        </x-filament::button>
    </div>
</form>

    @if($results->isNotEmpty())
        <div class="mt-6 w-full mx-auto px-4">
            <h2 class="text-2xl font-extrabold mb-6 text-gray-900 dark:text-gray-100">Resultados:</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($results as $anime)
                    <div
                        class="bg-white dark:bg-gray-900 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300
                            border border-gray-200 dark:border-gray-700 flex flex-col items-center text-center
                            overflow-hidden cursor-pointer"
                        style="height: 360px; width: 280px;"
                        onclick="window.location='{{ url('/ver-anime?id=' . $anime['id']) }}'"
                    >
                        <img
                            src="{{ $anime['image'] ?? '' }}"
                            alt="Imagen"
                            class="w-full h-48 object-cover rounded-t-xl"
                            loading="lazy"
                        />
                        <div class="p-4 flex flex-col flex-grow w-full justify-center">
                            <h3
                                class="font-semibold text-lg text-gray-900 dark:text-gray-100 truncate"
                                title="{{ $anime['title']['english'] ?? $anime['title']['romaji'] ?? 'Título desconocido' }}"
                            >
                                {{ $anime['title']['english'] ?? $anime['title']['romaji'] ?? 'Título desconocido' }}
                            </h3>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</x-filament::page>
