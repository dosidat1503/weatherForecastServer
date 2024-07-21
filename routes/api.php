<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/getWeather', [WeatherController::class, 'getWeather']);
Route::post('/subcribeMailReceiveInfoDaily', [WeatherController::class, 'subcribeMailReceiveInfoDaily']); 
Route::get('/unsubscribe/{verificationCode}', [WeatherController::class, 'unsubscribe']); 
Route::get('/verify-email/{verificationCode}', [WeatherController::class, 'verifyEmail']);