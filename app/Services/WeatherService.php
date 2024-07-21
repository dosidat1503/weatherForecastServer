<?php

namespace App\Services;

use GuzzleHttp\client;
use Illuminate\Support\Facades\Log;

class WeatherService{
    protected $client;
    protected $apiKey;

    public function __construct(){
        $this->client = new Client();
        $this->apiKey = env('WEATHER_API_KEY');
        Log::info('WEATHER_API_KEY: ' . $this->apiKey);
    }

    public function getWeather($location, $forecastDayQuantity){
        // $response = $this->client->request(
        //     'GET', 
        //     'http://api.weatherapi.com/v1/forecast.json',
        //     [
        //         'query' => [
        //             'key' => $this->apiKey,
        //             'q' => $location,
        //             'days' => $forecastDayQuantity,
        //             'aqi' => 'no',
        //             'alerts' => 'no',
        //         ]
        //     ]
        // ); 

        // return json_decode($response->getBody()->getContents(), true);


        $queryParams = [
            'key' => $this->apiKey,
            'q' => $location,
            'days' => $forecastDayQuantity,
            'aqi' => 'no',
            'alerts' => 'no',
        ];
    
        $url = 'http://api.weatherapi.com/v1/forecast.json?' . http_build_query($queryParams);
    
        // Log the request URL
        Log::info("Request URL: " . $url);
    
        $response = $this->client->request(
            'GET', 
            'http://api.weatherapi.com/v1/forecast.json',
            [
                'query' => $queryParams
            ]
        ); 
    
        return json_decode($response->getBody()->getContents(), true);
    }

    
}
