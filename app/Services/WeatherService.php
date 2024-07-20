<?php

namespace App\Services;

use GuzzleHttp\client;

class WeatherService{
    protected $client;
    protected $apiKey;

    public function __construct(){
        $this->client = new Client();
        $this->apiKey = env('WEATHER_API_KEY');
    }

    public function getWeather($location, $forecastDayQuantity){
        $response = $this->client->request(
            'GET', 
            'http://api.weatherapi.com/v1/forecast.json',
            [
                'query' => [
                    'key' => $this->apiKey,
                    'q' => $location,
                    'days' => $forecastDayQuantity,
                    'aqi' => 'no',
                    'alerts' => 'no',
                ]
            ]
        ); 

        return json_decode($response->getBody()->getContents(), true);
    }

    
}
