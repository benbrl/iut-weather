<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\AddFavoriteCity;
use App\Models\PlaceUser;
use Illuminate\Http\Request;

class CityController extends Controller
{
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
                'message' => 'The city already exists in the list of favorites.',
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
                    'message' => 'City successfully saved',
                    'user_id' => $user_id,
                    'city_id' => $place->id
                ]);
            } else {
                return redirect()->route('saved')->with([
                    'status' => 'error',
                    'message' => 'Error when registering city.'
                ]);
            }
        }
    }

    
    public function removeCity(Request $request, $city_id)
    {
   
        $user_id = Auth::id();
    
        // Delete corresponding entry in place_user table
        $deleted = PlaceUser::where('user_id', $user_id)
                            ->where('place_id', $city_id)
                            ->delete();
    
        // Check if the deletion was successful
        if ($deleted) {
            return redirect()->back()->with('success', 'La ville a été supprimée de vos favoris.');
        } else {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression de la ville.');
        }
    }
    

    public function getCityState($cityName)
{
    $userId = Auth::id();
    $savedCity = AddFavoriteCity::where('name', $cityName)
        ->join('place_user', 'add_favorite_cities.id', '=', 'place_user.place_id')
        ->where('place_user.user_id', $userId)
        ->first();

    $isSaved = $savedCity ? true : false;
    $savedCityId = $savedCity->place_id ?? null;

    return compact('isSaved', 'savedCityId');
}



    public function addFavorite($city_id)
    {
        $user_id = Auth::id();

        // Remove from favorite any way
        PlaceUser::where('user_id', $user_id)
            ->where('is_favorite', true)
            ->update(['is_favorite' => false]);

        // add city to favorite
        PlaceUser::where('user_id', $user_id)
            ->where('place_id', $city_id)
            ->update(['is_favorite' => true]);

        return redirect()->route('saved')->with('status', 'City add to favorite');
    }





    public function removeFavorite($city_id)
    {
        $user_id = Auth::id();

        // remove city from favorite
        PlaceUser::where('user_id', $user_id)
            ->where('place_id', $city_id)
            ->update(['is_favorite' => false]);

        return redirect()->back()->with('status', 'city remove from favorite');
    }

}
