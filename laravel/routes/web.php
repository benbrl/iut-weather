<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\CityCoordinatesController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('test');
});

Route::get('/weather', [WeatherController::class, 'getWeather'])->name('weather');

Route::get('/forecast/{city?}', [ForecastController::class, 'getForecastWeather'])->name('forecast');

Route::get('/city-coordinates', [CityCoordinatesController::class, 'getCityCoordinates'])->name('citycoordinates');

Route::get('/favorite-city', function () {
    return view('favorite-city')->name('favorite-city');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
