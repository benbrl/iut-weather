<?php

namespace App\Http\Controllers;

use App\Models\PlaceUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();

        // Récupérer la ville favorite de l'utilisateur
        $favoriteCity = PlaceUser::with('place')
                            ->where('user_id', $user_id)
                            ->where('is_favorite', true)
                            ->first();

        $weatherData = null;

        // Si une ville favorite est trouvée, récupérer les informations météo
        if ($favoriteCity && $favoriteCity->place) {
            $cityName = $favoriteCity->place->name;
            
            // Appel à l'API météo
            $weatherResponse = Http::get(config('services.openweather.base_url') . '/data/2.5/weather', [
                'q' => $cityName,
                'appid' => config('services.openweather.api_key'),
                'units' => 'metric',
                'lang' => 'fr'
            ]);

            if ($weatherResponse->successful()) {
                $weatherData = $weatherResponse->json();
            }
        }

        // Passer les données à la vue du tableau de bord
        return view('dashboard', [
            'favoriteCity' => $favoriteCity,
            'weatherData' => $weatherData
        ]);
    }
}
