<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use GuzzleHttp\Client;

class Entretenimiento extends Page
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static string $view = 'filament.pages.entretenimiento';

    protected static ?string $navigationGroup = 'Entretenimiento';

    protected static ?string $title = 'Buscar Anime';

    public $searchTerm = '';
    public Collection $results;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('searchTerm')
                ->label('Buscar Anime')
                ->required()
                ->placeholder('Escribe el nombre del anime'),
        ];
    }

    public function mount(): void
    {
        $this->results = collect();
        $this->form->fill([
            'searchTerm' => '',
        ]);
    }

    public function buscar()
    {
        $query = $this->searchTerm;

        if (empty($query)) {
            Notification::make()
                ->title('Error')
                ->body('Debes ingresar un nombre para buscar.')
                ->danger()
                ->send();
            return;
        }

        $client = new Client(['base_uri' => 'http://localhost:3000/']);

        try {
            $response = $client->get('meta/anilist/' . urlencode($query));
            $data = json_decode($response->getBody()->getContents(), true);

            $this->results = collect($data['results'] ?? []);
            if ($this->results->isEmpty()) {
                Notification::make()
                    ->title('Sin resultados')
                    ->body('No se encontraron animes para: ' . $query)
                    ->warning()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error en la búsqueda')
                ->body('No se pudo realizar la búsqueda: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getViewData(): array
    {
        return [
            'results' => $this->results,
        ];
    }
}
