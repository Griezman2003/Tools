<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Forms;
use Filament\Pages\Page;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

class DescargarMusica extends Page
{
    protected static ?string $navigationIcon = 'heroicon-s-musical-note';

    protected static string $view = 'filament.pages.descargar-musica';

    protected static ?string $navigationGroup = 'Multimedia';

    public $musica_url;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('musica_url')
                ->label('URL del video')
                ->required()
                ->url(),
        ];
    }

    public function descargar()
    {
        $data = $this->form->getState();
        $url = $data['musica_url'];

        if (empty($url)) {
            Notification::make()
                ->title('Error')
                ->body('La URL del video es requerida.')
                ->danger()
                ->send();
            return;
        }

        $ytDlpPath = base_path('yt-dlp.exe');

        if (!file_exists($ytDlpPath)) {
            Notification::make()
                ->title('Error')
                ->body('No se encontró el ejecutable yt-dlp.exe en el servidor.')
                ->danger()
                ->send();
            Log::error("No se encontró yt-dlp.exe en: {$ytDlpPath}");
            return;
        }

        $timestamp = date('Ymd_His');
        $videoTitle = "audio_descargado_{$timestamp}";
        $safeTitle = Str::slug($videoTitle);

        $filename = $safeTitle . '.mp3';

        $downloadsFolder = getenv('USERPROFILE') . '\\Downloads\\';

        if (!file_exists($downloadsFolder)) {
            mkdir($downloadsFolder, 0777, true);
        }

        $outputPath = $downloadsFolder . $filename;

        $downloadCommand = "\"$ytDlpPath\" -x --audio-format mp3 -o " . escapeshellarg($outputPath) . " " . escapeshellarg($url);

        try {
            shell_exec($downloadCommand . " > NUL 2>&1 &");
            Notification::make()
                ->title('Descarga Finalizada')
                ->body("El audio se ha descargado como \"{$filename}\" en tu carpeta Descargas.")
                ->success()
                ->send();

            $this->form->fill(['musica_url' => '']);

        } catch (\Throwable $e) {
            Notification::make()
                ->title('Error al descargar el audio')
                ->body($e->getMessage())
                ->danger()
                ->send();
            Log::error('Error descarga audio: ' . $e->getMessage());
        }
    }
}
