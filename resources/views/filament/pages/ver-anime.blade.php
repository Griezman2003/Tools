<x-filament::page>
    @if ($anime)
        <div class="w-full mx-auto p-6 bg-white dark:bg-gray-900 rounded-xl shadow-lg transition-colors duration-300">
            <h1 class="text-3xl font-extrabold mb-6 text-gray-900 dark:text-gray-100 mb-5">
                {{ $anime['title']['english'] ?? $anime['title']['romaji'] }}
            </h1>

            <div class="flex flex-col md:flex-row items-start md:items-center gap-6 mb-8">
                <img
                    src="{{ $anime['image'] }}"
                    alt="{{ $anime['title']['english'] ?? 'Imagen del anime' }}"
                    class="w-40 h-auto rounded-lg shadow-lg flex-shrink-0"
                    loading="lazy"
                />
                <p class="text-gray-500 dark:text-gray-300 text-base leading-relaxed  pl-16">
                    {{ strip_tags($anime['description'] ?? 'Sin descripción disponible.') }}
                </p>

            </div>

            <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700 pb-2">
                Episodios
            </h2>

            <ul class="space-y-4">
                @foreach ($episodes as $ep)
                    <li
                        class="bg-gray-50 dark:bg-gray-800 rounded-lg shadow-md p-4 flex justify-between items-center
                        hover:bg-indigo-50 dark:hover:bg-indigo-900 transition-colors duration-300"
                    >
                        <span class="text-gray-900 dark:text-gray-100 font-medium">
                            Episodio {{ $ep['number'] }} {{ $ep['title'] ? '- ' . $ep['title'] : '' }}
                        </span>
                        <a
                            href="{{ $ep['url'] }}"
                            target="_blank"
                            class="inline-block px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600
                                   text-white rounded-lg font-semibold text-sm shadow-md
                                   hover:from-indigo-700 hover:to-purple-700
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
                                   transition-colors duration-300"
                        >
                            Reproducir
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <p class="text-red-500 text-center font-semibold mt-10">No se pudo cargar el anime.</p>
    @endif
</x-filament::page>
