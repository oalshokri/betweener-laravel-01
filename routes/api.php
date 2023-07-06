<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::controller(AuthController::class)->group(function (){
    Route::post('/register','register');
    Route::post('/login','login')->name('login');
});

Route::controller(UserController::class)->group(function (){
    Route::put('/update/{user}','updateLocation');
    Route::put('/fcm/{user}','updateFcm');
});

Route::controller(ShareController::class)->group(function (){
//    Route::get('/longPressShare/{user}','longPressShare');
    Route::get('/activeShare/nearest/{user}','getNearestSender');
    Route::post('/activeShare/{user}','activeShare');
    Route::delete('/activeShare/{user}','deleteActiveShare');
});
