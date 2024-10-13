<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CityCoordinatesController extends Controller
{
    public function getCityCoordinates(Request $request)
    {
       
        $request->validate([
            'city' => 'required|string|max:255',
        ]);

        $city = $request->input('city');
        $apiKey = config('services.openweather.api_key');
        $baseUrl = config('services.openweather.base_url');

        // Faire la requête à l'API OpenWeather
        $response = Http::get("${baseUrl}/geo/1.0/direct", [
            'q' => $city,
            'limit' => 1,
            'appid' => $apiKey,
        ]);

        // Vérifier si la réponse est valide
        if ($response->successful()) {
            $data = $response->json();
            if (!empty($data)) {
                $latitude = $data[0]['lat'];
                $longitude = $data[0]['lon'];
                return response()->json([
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ]);
            }
            return response()->json(['message' => 'Aucune ville trouvée'], 404);
        }

        return response()->json(['message' => 'Erreur lors de la récupération des données'], $response->status());
    }
}
