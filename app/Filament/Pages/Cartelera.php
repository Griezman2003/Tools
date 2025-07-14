<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;

class Cartelera extends Page
{
    protected static ?string $navigationGroup = 'Entretenimiento';
    protected static string $view = 'filament.pages.cartelera';

    public $animes = [];
    public $searchTerm = '';

    public function mount()
    {
        $this->fetchCurrentSeasonAnime();
    }

    public function fetchCurrentSeasonAnime()
    {
        $response = Http::get('https://api.jikan.moe/v4/seasons/now');

        if ($response->successful()) {
            $this->animes = collect($response->json('data'))->map(function ($anime) {
                return [
                    'id' => $anime['mal_id'],
                    'title' => $anime['title'],
                    'image' => $anime['images']['jpg']['image_url'],
                    'synopsis' => $anime['synopsis'] ?? 'Sin sinopsis.',
                    'status' => $anime['status'] ?? 'Sin status.',
                    'url' => $anime['url'],
                ];
            })->toArray();
        }
    }

    public function search()
    {
        if (trim($this->searchTerm) === '') {
            $this->fetchCurrentSeasonAnime();
            return;
        }

        $response = Http::get('https://api.jikan.moe/v4/anime', [
            'q' => $this->searchTerm,
            'limit' => 20,
        ]);

        if ($response->successful()) {
            $this->animes = collect($response->json('data'))->map(function ($anime) {
                return [
                    'id' => $anime['mal_id'],
                    'title' => $anime['title'],
                    'image' => $anime['images']['jpg']['image_url'],
                    'synopsis' => $anime['synopsis'] ?? 'Sin sinopsis.',
                    'status' => $anime['status'] ?? 'Status',
                    'url' => $anime['url'],
                ];
            })->toArray();
        } else {
            $this->animes = [];
        }
    }

    public function clearSearch()
    {
        $this->searchTerm = '';
        $this->fetchCurrentSeasonAnime();
    }
}
