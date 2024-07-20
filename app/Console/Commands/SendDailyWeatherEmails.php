<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WeatherService; 
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendDailyWeatherEmails extends Command
{
    protected $signature = 'emails:send-daily-weather';
    protected $description = 'Send daily weather forecast emails';

    protected $weatherService;
    
    
    public function __construct(WeatherService $weatherService)
    {
        parent::__construct();
        $this->weatherService = $weatherService;
    }

    public function handle()
    { 
        $filePath = 'emails.json';
        if (!Storage::exists($filePath)) {
            $this->info('No email data found');
            return;
        }

        $emails = json_decode(Storage::get($filePath), true);

        foreach ($emails as $entry) {
            $email = $entry['email'];
            $location = $entry['cityName'];
 
            $weather = $this->weatherService->getWeather($location, 5);  
            
            $this->sendWeatherEmail($email, $location, $weather);
        }

        $this->info('Emails sent successfully');
    }

    protected function sendWeatherEmail($toEmail, $location, $weatherData)
    {
        $verificationCode = sha1(time() . $toEmail);
        Storage::put('verification_codes/' . $verificationCode . '.json', json_encode(['email' => $toEmail]));
        
        Mail::send('emails.mailForecastDailyWeatherInfo', [ 
            'weatherData' => $weatherData,
            'verificationCode' => $verificationCode
        ], function ($message) use ($toEmail) {
            $message->to($toEmail)
                    ->subject('Daily Weather Forecast');
        });
    }
}
