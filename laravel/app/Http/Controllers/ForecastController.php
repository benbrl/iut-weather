<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ForecastController extends Controller
{

    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openweather.api_key');
        $this->baseUrl = config('services.openweather.base_url');
    }
    
    public function getForecastWeather($city = null)
    {
        if ($city === null) {
            return view('weather', ['error' => 'No city specified.']);
        }

    

        $response = Http::get("$this->baseUrl/data/2.5/forecast", [
            'q' => $city,
            'appid' => $this->apiKey,
            'units' => 'metric',
            'lang' => 'fr'
        ]);

        if ($response->successful()) {
            $forecastData = $response->json();
            return view('forecast', ['forecast' => $forecastData]);
        } else {
            return view('forecast', ['error' => 'Impossible to get weather data']);
        }
    }
}
