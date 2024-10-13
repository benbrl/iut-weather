<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{

    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openweather.api_key');
        $this->baseUrl = config('services.openweather.base_url');
    }
    
    public function getWeather(Request $request)
    {
      

        $request->validate([
            'city' => 'string|max:255',
        ]);
        $city = $request->input('city');

        if (!$city) {
            return view('weather', ['error' => 'Aucune ville spécifiée.']);
        }

    

        $response = Http::get("$this->baseUrl/data/2.5/weather", [
            'q' => $city,
            'appid' => $this->apiKey,
            'units' => 'metric',
            'lang' => 'fr'
        ]);

        if ($response->successful()) {
            $weatherData = $response->json();
            return view('weather', ['weather' => $weatherData]);
        } else {
            return view('weather', ['error' => 'Impossible de récupérer les données météo']);
        }
    }
}
