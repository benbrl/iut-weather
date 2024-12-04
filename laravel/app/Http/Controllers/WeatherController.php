<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AddFavoriteCity;
use App\Models\PlaceUser;
use Illuminate\Support\Facades\Auth;

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

    public function saveCity(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $name = $request->input('name');
        $user_id = Auth::id();

        $existingPlace = AddFavoriteCity::where('name', $name)->first();

        if ($existingPlace) {
            PlaceUser::create([
                'place_id' => $existingPlace->id,
                'user_id' => $user_id,
                'is_favorite' => false,
                'send_forecast' => false,
            ]);

            return redirect()->route('saved')->with([
                'status' => 'exists',
                'message' => 'La ville existe déjà dans la liste des favoris.',
                'user_id' => $user_id,
                'city_id' => $existingPlace->id
            ]);
        } else {
            $place = AddFavoriteCity::create([
                'name' => $name,
                'user_id' => $user_id,
            ]);

            PlaceUser::create([
                'place_id' => $place->id,
                'user_id' => $user_id,
                'is_favorite' => false,
                'send_forecast' => false,
            ]);

            if ($place && $place->id) {
                return redirect()->route('saved')->with([
                    'status' => 'success',
                    'message' => 'Ville enregistrée avec succès !',
                    'user_id' => $user_id,
                    'city_id' => $place->id
                ]);
            } else {
                return redirect()->route('saved')->with([
                    'status' => 'error',
                    'message' => 'Erreur lors de l\'enregistrement de la ville.'
                ]);
            }
        }
    }


    public function GetCitySaved()
    {
        // Récupérer l'ID de l'utilisateur connecté
        $user_id = Auth::id();

        // Rechercher toutes les villes enregistrées par cet utilisateur via PlaceUser
        $cities = PlaceUser::with('place')
            ->where('user_id', $user_id)
            ->get();

        // Passer les données des villes à la vue 'recorded'
        return view('saved', ['cities' => $cities]);
    }


    public function addFavorite($city_id)
    {
        $user_id = Auth::id();

        // Retire l'ancien favori, s'il y en a un
        PlaceUser::where('user_id', $user_id)
            ->where('is_favorite', true)
            ->update(['is_favorite' => false]);

        // Marque cette ville comme favorite
        PlaceUser::where('user_id', $user_id)
            ->where('place_id', $city_id)
            ->update(['is_favorite' => true]);

        return redirect()->route('saved')->with('status', 'Ville ajoutée aux favoris !');
    }

    public function removeFavorite($city_id)
    {
        $user_id = Auth::id();

        // Désactive la ville comme favorite
        PlaceUser::where('user_id', $user_id)
            ->where('place_id', $city_id)
            ->update(['is_favorite' => false]);

        return redirect()->route('saved')->with('status', 'Ville retirée des favoris.');
    }

    public function subscribeReport($city_id)
    {
        $user_id = Auth::id();

        // Active l'option d'envoi de prévisions quotidiennes
        PlaceUser::where('user_id', $user_id)
            ->where('place_id', $city_id)
            ->update(['send_forecast' => true]);

        return redirect()->route('saved')->with('status', 'Inscription au rapport journalier réussie.');
    }



    public function removeCity(Request $request)
    {
        $request->validate([
            'city_id' => 'required|integer|exists:place_user,place_id'
        ]);

        $user_id = Auth::id();
        $city_id = $request->input('city_id');

        // Supprimer l'enregistrement de la ville favorite de l'utilisateur
        $deleted = PlaceUser::where('user_id', $user_id)
            ->where('place_id', $city_id)
            ->delete();

        // Vérifier si la suppression a bien été effectuée
        if ($deleted) {
            return redirect()->route('saved')->with('success', 'La ville a été supprimée de vos favoris.');
        } else {
            return redirect()->route('saved')->with('error', 'Erreur lors de la suppression de la ville.');
        }
    }
}
