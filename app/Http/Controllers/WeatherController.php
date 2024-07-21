<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendVerificationEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService){
        $this->weatherService = $weatherService;
    }

    public function getWeather(Request $request){
        $location = $request->input('location');
        $forecastDayQuantity = $request->input('forecastDayQuantity');
        $weather = $this->weatherService->getWeather($location, $forecastDayQuantity);

        return $weather;
    }

    public function subcribeMailReceiveInfoDaily(Request $request){
        
        $email  = $request->input('email'); 
        $cityName  = $request->input('cityName');   

        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]); 

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid email address'], 422);
        }

        $emails = [];
        if(Storage::exists('emails.json')){
            $emails = json_decode(Storage::get('emails.json'), true); 
        }

        if(in_array($email, $emails)){
            return response()->json(['message' => 'Email already subscribed'], 409);
        } 

        $verificationCode = sha1(time() . $email);
        Storage::put(
            'verification_codes/'  . $verificationCode  . '.json'
            , json_encode([
                'email' => $email,
                'cityName' => $cityName
            ]) 
        );

        SendVerificationEmail::dispatch($email, $verificationCode);

        return response()->json(['message' => 'Subscription successful. Please check your email to confirm.']);
    }


    public function verifyEmail($verificationCode)
    {
        $filePath = 'verification_codes/' . $verificationCode . '.json';

        if (!Storage::exists($filePath)) {
            return response()->json(['message' => 'Invalid verification code'], 400);
        }

        $emailData = json_decode(Storage::get($filePath), true);
        $email = $emailData['email'];
        $cityName = $emailData['cityName'];
        Storage::delete($filePath);

        $emails = [];

        if (Storage::exists('emails.json')) {
            $emails = json_decode(Storage::get('emails.json'), true);
        }

        $emailExists = false;
        foreach ($emails as $existingEmailData) {
            if ($existingEmailData['email'] === $email) {
                $emailExists = true;
                break;
            }
        }

        if (!$emailExists) {
            $emails[] = [
                'email' => $email,
                'cityName' => $cityName
            ];
            Storage::put('emails.json', json_encode($emails));
        }

        return response()->json(['message' => 'Email verified and subscribed']);
    } 

    public function unsubscribe($verificationCode)
    {

        $filePath = 'verification_codes/' . $verificationCode . '.json';

        if (!Storage::exists($filePath)) {
            return response()->json(['message' => 'Invalid verification code'], 400);
        }

        $emailData = json_decode(Storage::get($filePath), true);
        $email = $emailData['email'];

        $emails = [];
        if (Storage::exists('emails.json')) {
            $emails = json_decode(Storage::get('emails.json'), true);
        }

        $emails = array_filter($emails, function ($subscribedEmail) use ($email) {
            return $subscribedEmail['email'] !== $email;
        });

        Storage::put('emails.json', json_encode($emails));
        Storage::delete($filePath);

        return response()->json(['message' => 'Unsubscribed successfully']);
    }
     
}
