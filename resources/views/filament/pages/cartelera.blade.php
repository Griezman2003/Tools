<x-filament::page>
    <h1 class="text-3xl font-extrabold mb-8 bg-gradient-to-r from-purple-600 via-pink-500 to-red-500 bg-clip-text text-transparent">
        🎬 Cartelera
    </h1>

<form wire:submit.prevent="search" class="mb-10 flex flex-col md:flex-row items-center gap-4">
    <input 
        type="text" 
        wire:model.defer="searchTerm" 
        placeholder="Buscar tu anime favorito..." 
        class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
    />
    <button 
        type="submit" 
        class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-500 text-white font-semibold rounded-lg shadow-lg hover:from-pink-500 hover:to-purple-600 transition"
    >
        Buscar
    </button>
    @if ($searchTerm)
        <button 
            wire:click="clearSearch" 
            type="button" 
            class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold rounded-lg shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition"
        >
            Limpiar
        </button>
    @endif
</form>

    @if (count($animes) === 0)
        <p class="text-center text-gray-500 dark:text-gray-400 italic text-lg">No se encontraron animes.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach ($animes as $anime)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl dark:shadow-black/50 overflow-hidden transform hover:scale-105 hover:shadow-2xl dark:hover:shadow-purple-700 transition duration-300">
                    <a href="{{ $anime['url'] }}" target="_blank" class="block overflow-hidden">
                        <img 
                            src="{{ $anime['image'] }}" 
                            alt="{{ $anime['title'] }}" 
                            class="w-full h-60 object-cover object-center rounded-t-2xl"
                            loading="lazy"
                        />
                    </a>
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-2 text-gray-900 dark:text-gray-100 hover:text-purple-600 transition cursor-pointer">
                            {{ $anime['title'] }}
                        </h2>
                        <p class="text-sm font-medium text-purple-600 mb-3 capitalize tracking-wide">
                            {{ $anime['status'] ?? 'Estado desconocido' }}
                        </p>
                        <p class="text-gray-700 dark:text-gray-500 text-sm leading-relaxed line-clamp-4" title="{{ $anime['synopsis'] }}">
                            {{ $anime['synopsis'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-filament::page>
