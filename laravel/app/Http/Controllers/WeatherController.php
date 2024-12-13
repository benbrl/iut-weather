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
    
                    // Check if city is saved by the user
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
            return view('weather', ['error' => 'Impossible to  show weather data']);
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

    // public function subscribeReport($city_id)
    // {
    //     // Récupération de l'ID de l'utilisateur connecté
    //     $user_id = Auth::id();
    
    //     // Mise à jour de la colonne send_forecast à true pour cet utilisateur et cette ville
    //     $updated = PlaceUser::where('user_id', $user_id)
    //         ->where('place_id', $city_id)
    //         ->update(['send_forecast' => true]);
    
    //     // Vérification si la mise à jour a réussi
    //     if ($updated) {
    //         // Envoi de la notification
    //         $user = Auth::user();
    //         $user->notify(new DailyReportEmail($city_id));
    
    //         // Redirection avec un message de succès
    //         return redirect()->route('saved')->with('status', 'Successful registration for the daily report');
    //     } else {
    //         // Si la mise à jour échoue
    //         return redirect()->route('saved')->with('status', 'Failed to register for the daily report');
    //     }
    // }

//     public function subscribeReport($city_id)
// {
//     $user_id = Auth::id();

//     // Vérifier ou mettre à jour l'abonnement
//     $subscribe = PlaceUser::where('user_id', $user_id)
//         ->where('place_id', $city_id)
//         ->update(['send_forecast' => true]);

//     // Vérifier si la mise à jour a échoué (aucun enregistrement trouvé)
//     if (!$subscribe) {
//         // Créer une nouvelle entrée si elle n'existe pas
//         PlaceUser::create([
//             'user_id' => $user_id,
//             'place_id' => $city_id,
//             'send_forecast' => true,
//         ]);
//     }
//     // Récupérer l'utilisateur
//     $user = Auth::user();

//     // Envoyer la notification de façon synchrone
//     $user->notify(new DailyReportEmail($city_id));

//     return redirect()->back()->with('status', 'Successfully subscribed to daily reports.');
// }



public function subscribeReport($city_id)
{
    $user_id = Auth::id();

    // Vérifier ou mettre à jour l'abonnement
    $subscribe = PlaceUser::where('user_id', $user_id)
        ->where('place_id', $city_id)
        ->update(['send_forecast' => true]);

    // Vérifier si la mise à jour a échoué (aucun enregistrement trouvé)
    if (!$subscribe) {
        // Créer une nouvelle entrée si elle n'existe pas
        PlaceUser::create([
            'user_id' => $user_id,
            'place_id' => $city_id,
            'send_forecast' => true,
        ]);
    }

    // Récupérer l'utilisateur
    $user = Auth::user();

    // Envoyer immédiatement la notification par mail
    $user->notify(new DailyReportEmail($city_id));

    // Retourner un message de succès
    return redirect()->back()->with('status', 'Successfully subscribed to daily reports.');
}



public function unsubscribeReport($city_id)
{
    $user_id = Auth::id();

    // Mettre à jour l'abonnement pour désactiver les rapports
    $unsubscribe = PlaceUser::where('user_id', $user_id)
        ->where('place_id', $city_id)
        ->update(['send_forecast' => false]);

    // Vérifier si aucun enregistrement n'a été mis à jour
    if (!$unsubscribe) {
        return redirect()->back()->with('status', 'No subscription found to unsubscribe.');
    }

    return redirect()->back()->with('status', 'Successfully unsubscribed from daily reports.');
}



}
