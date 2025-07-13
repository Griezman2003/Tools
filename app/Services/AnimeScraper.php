<?php

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Log;

class AnimeScraper
{
    protected Client $client;

    protected array $userAgents = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 13_4_1) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.5 Safari/605.1.15',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36',
    ];

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 15,
        ]);
    }

    protected function getRandomUserAgent(): string
    {
        return $this->userAgents[array_rand($this->userAgents)];
    }

    public function fetchEpisodes(string $slug): array
    {
        $slug = strtolower(trim($slug));
        $slug = str_replace(' ', '-', $slug);
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);

        $url = "https://animeav1.com/media/{$slug}";

        try {
            $response = $this->client->get($url, [
                'headers' => [
                    'User-Agent' => $this->getRandomUserAgent(),
                ],
            ]);

            $html = $response->getBody()->getContents();
            Log::info("AnimeScraper fetchEpisodes: contenido de {$url} obtenido, tamaño: " . strlen($html));

            $crawler = new Crawler($html);

            $episodes = $crawler->filter("section a[href^='/media/{$slug}/']")->each(function (Crawler $node) {
                $href = $node->attr('href');
                $episodeNumber = basename($href);

                return [
                    'title' => "Episodio {$episodeNumber}",
                    'href' => 'https://animeav1.com' . $href,
                ];
            });

            return array_filter(array_unique($episodes, SORT_REGULAR));

        } catch (\Exception $e) {
            Log::error('AnimeScraper fetchEpisodes error: ' . $e->getMessage());
            return [];
        }
    }

    public function fetchEpisodeVideoUrl(string $episodeUrl): ?string
    {
        try {
            $response = $this->client->get($episodeUrl, [
                'headers' => [
                    'User-Agent' => $this->getRandomUserAgent(),
                ],
            ]);

            $html = $response->getBody()->getContents();
            $crawler = new Crawler($html);

            $videoUrl = $crawler->filter('iframe')->attr('src') ?? null;

            return $videoUrl;

        } catch (\Exception $e) {
            Log::error('AnimeScraper fetchEpisodeVideoUrl error: ' . $e->getMessage());
            return null;
        }
    }
}
