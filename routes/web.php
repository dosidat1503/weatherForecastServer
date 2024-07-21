<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
 
Route::get('/getWeather', [WeatherController::class, 'getWeather']);
Route::post('/subcribeMailReceiveInfoDaily', [WeatherController::class, 'subcribeMailReceiveInfoDaily']); 
Route::get('/unsubscribe/{verificationCode}', [WeatherController::class, 'unsubscribe']); 
Route::get('/verify-email/{verificationCode}', [WeatherController::class, 'verifyEmail']);
Route::get('/sendDailyWeatherEmail', function () {
    Artisan::call('emails:send-daily-weather');
    return response()->json(['message' => 'Emails sent successfully']);
});