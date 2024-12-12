<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExportController extends Controller

{

    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openweather.api_key');
        $this->baseUrl = config('services.openweather.base_url');
    }
    
    public function downloadCSV(Request $request)
{
    $request->validate([
        'city' => 'required|string|max:255',
    ]);

    $city = $request->input('city');


    $response = Http::get("$this->baseUrl/data/2.5/weather", [
        'q' => $city,
        'appid' => $this->apiKey,
        'units' => 'metric',
        'lang' => 'fr',
    ]);

    if ($response->failed()) {
        return redirect()->back()->with('error', 'Unable to get weather data to generate CSV');
    }

    $data = $response->json();

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"{$city}_weather.csv\"",
    ];

    // Generate CSV
    $callback = function () use ($data) {
         // fputcsv function to create a csv

        $file = fopen('php://output', 'w');
        // head csv
        fputcsv($file, ['City', 'Temperature (°C)', 'Description', 'Humidity (%)']);
        // data weather
   
        fputcsv($file, [
            $data['name'],
            $data['main']['temp'],
            $data['weather'][0]['description'],
            $data['main']['humidity'],
        ]);
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

}
