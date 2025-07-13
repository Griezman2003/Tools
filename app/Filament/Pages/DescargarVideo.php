<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DescargarVideo extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static string $view = 'filament.pages.descargar-video';

    protected static ?string $title = 'Descargar Video';

    protected static ?string $navigationGroup = 'Multimedia';

    public $video_url;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('video_url')
                ->label('URL del video')
                ->required()
                ->url(),
        ];
    }

    public function descargar()
    {
        $data = $this->form->getState();
        $url = $data['video_url'];

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

        $downloadsFolder = getenv('USERPROFILE') . '\\Downloads\\';
        if (!file_exists($downloadsFolder)) {
            mkdir($downloadsFolder, 0777, true);
        }

        $timestamp = date('Ymd_His');
        $filename = "video_{$timestamp}_" . Str::random(6) . '.mp4';
        $outputPath = $downloadsFolder . $filename;

        $downloadCommand = "\"$ytDlpPath\" -o " . escapeshellarg($outputPath) . " " . escapeshellarg($url);

        try {
            shell_exec($downloadCommand . " > NUL 2>&1 &");
            sleep(5);
            if (!file_exists($outputPath)) {
                Notification::make()
                    ->title('Error')
                    ->body('No se encontró el archivo descargado en Descargas.')
                    ->danger()
                    ->send();
                Log::error("Archivo no encontrado en Descargas: {$outputPath}");
                return;
            }

            $storageFolder = storage_path('app/public/videos');
            if (!file_exists($storageFolder)) {
                mkdir($storageFolder, 0777, true);
            }
            $storagePath = $storageFolder . DIRECTORY_SEPARATOR . $filename;
            copy($outputPath, $storagePath);

            \App\Models\Video::create([
                'titulo' => "Video descargado {$timestamp}",
                'archivo' => $filename,
                'estado' => 'descargado',
            ]);

            Notification::make()
                ->title('Descarga Finalizada')
                ->body("El video se descargó en Descargas y se guardó en storage correctamente.")
                ->success()
                ->send();

            $this->form->fill(['video_url' => '']);
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Error al descargar el video')
                ->body($e->getMessage())
                ->danger()
                ->send();
            Log::error('Error descarga video: ' . $e->getMessage());
        }
    }
}
