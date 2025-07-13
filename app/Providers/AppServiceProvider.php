<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Filament\Pages\DescargarVideo;
use Filament\Facades\Filament;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Filament::registerPages([
        DescargarVideo::class,
        ]);
    }
}
