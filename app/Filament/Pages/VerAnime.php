<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;

class VerAnime extends Page
{
    protected static ?string $navigationIcon = null;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.ver-anime';

    public $anime;

    public $episodes = [];

    public function mount(): void
    {
        $id = request('id');
        $client = new Client(['base_uri' => 'http://localhost:3000/']);

        try {
            $response = $client->get("meta/anilist/info/" . urlencode($id));
            $data = json_decode($response->getBody(), true);

            $this->anime = $data;
            $this->episodes = $data['episodes'] ?? [];

        } catch (\Exception $e) {
            $this->anime = null;
            $this->episodes = [];
        }
    }

    protected function getViewData(): array
    {
        return [
            'anime' => $this->anime,
            'episodes' => $this->episodes,
        ];
    }
}
