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
        } else {
            Log::error('Failed to fetch weather data: ' . $response->body());
            return null;
        }
 
    } 
}
