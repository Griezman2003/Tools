<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use App\Services\AnimeScraper;
use Illuminate\Support\Facades\Log;

class Scraping extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-play-circle';

    protected static ?string $navigationGroup = 'Entretenimiento';

    protected static ?string $title = 'Anime Online';

    protected static string $view = 'filament.pages.scraping';

    public $animeSlug;
    public $episodes = [];
    public $selectedEpisodeUrl = null;

    protected ?AnimeScraper $scraper;

    public function __construct()
    {
        $this->scraper = new AnimeScraper();
    }

    public function mount()
    {
        $this->animeSlug = null;
        $this->episodes = [];
        $this->selectedEpisodeUrl = null;
    }

    public function loadEpisodes()
    {
        if (!$this->animeSlug) {
            Log::info('loadEpisodes: No se proporcionó slug de anime.');
            $this->episodes = [];
            return;
        }

        $episodes = $this->scraper->fetchEpisodes($this->animeSlug);

        if (empty($episodes)) {
            Notification::make()
                ->title('Error al cargar el anime')
                ->body('No se encontraron episodios o hubo un error.')
                ->danger()
                ->send();
        }

        $this->episodes = $episodes;
        $this->selectedEpisodeUrl = null;

        Log::info('loadEpisodes: Número de episodios encontrados: ' . count($this->episodes));
    }

    public function loadEpisodeVideo($episodeUrl)
    {
        $videoUrl = $this->scraper->fetchEpisodeVideoUrl($episodeUrl);

        if (!$videoUrl) {
            Notification::make()
                ->title('Error al cargar el episodio')
                ->body('No se pudo cargar el video.')
                ->danger()
                ->send();
            $this->selectedEpisodeUrl = null;
            return;
        }

        $this->selectedEpisodeUrl = $videoUrl;
        Log::info("loadEpisodeVideo: URL del video cargada: {$videoUrl}");
    }
}
