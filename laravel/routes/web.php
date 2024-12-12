<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\CityCoordinatesController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('test');
});

Route::get('/weather', [WeatherController::class, 'getWeather'])->name('weather');

Route::get('/saved', [WeatherController::class, 'GetCitySaved'])->name('saved');
Route::get('/forecast/{city?}', [ForecastController::class, 'getForecastWeather'])->name('forecast');
Route::get('/city-coordinates', [CityCoordinatesController::class, 'getCityCoordinates'])->name('citycoordinates');


//city controller
Route::post('/save-city', [CityController::class, 'saveCity'])->name('savecity');
;

Route::delete('/remove-favorite/{city_id}', [CityController::class, 'removeFavorite'])->name('remove_favorite');
Route::delete('/remove-city/{city_id}', [CityController::class, 'removeCity'])->name('removeCity');


Route::post('/add-favorite/{city_id}', [CityController::class, 'addFavorite'])->name('add_favorite');


//export controller
Route::get('/download-csv', [ExportController::class, 'downloadCSV'])->name('download.csv');



Route::post('/subscribe-report/{city_id}', [WeatherController::class, 'subscribeReport'])->name('subscribe_report');




// Route::get('/favorite', function () {
//     return view('favorite');
// })->name('favorite');


Route::get('/dashboard',[DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
