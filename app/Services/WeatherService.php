<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService{ 
    protected $apiKey;

    public function __construct(){ 
        $this->apiKey = env('WEATHER_API_KEY'); 
    }

    public function getWeather($location, $forecastDayQuantity){
        
        $response = Http::get(
            'http://api.weatherapi.com/v1/forecast.json?', 
            [
                'key' => $this->apiKey,
                'q' => $location,
                'days' => $forecastDayQuantity,
                'aqi' => 'no',
                'alerts' => 'no',
            ]
        );
 
        if ($response->successful()) {
            return $response->json(); 
        }  
        else{
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'Failed to fetch weather data from external API.'
            ], 500);
        }
    } 
}
