<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Pages\VerAnime;

Route::get('/', function () {
    return redirect('app');
});


Route::get('/ver-anime', VerAnime::class)->name('ver-anime');
