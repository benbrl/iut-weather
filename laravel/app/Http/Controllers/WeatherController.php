<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AddFavoriteCity;
use App\Models\PlaceUser;
use Illuminate\Support\Facades\Auth;
use App\Notifications\DailyReportEmail;


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
            return view('weather', ['error' => 'No city specified.']);
        }
    
    
        $response = Http::get("$this->baseUrl/data/2.5/weather", [
            'q' => $city,
            'appid' => $this->apiKey,
            'units' => 'metric',
            'lang' => 'fr'
        ]);
    
        if ($response->successful()) {
            $weatherData = $response->json();
    
        
            $user_id = Auth::id();
            $isFavorite = false;
            $isSaved = false;
            $cityId = null;
    
            if (isset($weatherData['name'])) {
                $existingPlace = AddFavoriteCity::where('name', strtolower($weatherData['name']))->first();
    
                if ($existingPlace) {
                    $cityId = $existingPlace->id; 
    
                    // check if cities is in favorite
                    $isFavorite = PlaceUser::where('user_id', $user_id)
                        ->where('place_id', $existingPlace->id)
                        ->where('is_favorite', true)
                        ->exists();
    
                    // Vérification si la ville est enregistrée pour l'utilisateur
                    $isSaved = PlaceUser::where('user_id', $user_id)
                        ->where('place_id', $existingPlace->id)
                        ->exists();
                }
            }
    
            // Return view with data
            return view('weather', [
                'weather' => $weatherData,
                'isFavorite' => $isFavorite,
                'isSaved' => $isSaved,
                'cityId' => $cityId,  
            ]);
        } else {
            return view('weather', ['error' => 'Impossible to retrieve weather data']);
        }
    }
    



    

    public function GetCitySaved()
    {
        $user_id = Auth::id();

        // find city saved by user
        $cities = PlaceUser::with('place')
            ->where('user_id', $user_id)
            ->get();

        return view('saved', ['cities' => $cities]);
    }

    public function subscribeReport($city_id)
    {
        $user_id = Auth::id();

        PlaceUser::where('user_id', $user_id)
            ->where('place_id', $city_id)
            ->update(['send_forecast' => true]);

        // send notification
        $user = Auth::user();
        $user->notify(new DailyReportEmail($city_id));

        return redirect()->route('saved')->with('status', 'Successful registration for the daily report');
    }

}
