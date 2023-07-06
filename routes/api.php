<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FolloweeController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\UserController;
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

//public Routs
Route::controller(AuthController::class)->group(function (){
    Route::post('/register','register');
    Route::post('/login','login')->name('login');
});

//private Routs
Route::middleware('auth:sanctum')->group( function () {
    Route::controller(UserController::class)->group(function (){
        Route::put('/update/{user}','updateLocation');
        Route::put('/fcm/{user}','updateFcm');
        Route::post('/search','search');
    });

    Route::controller(ShareController::class)->group(function (){
        Route::get('/activeShare/nearest/{user}','getNearestSender');
        Route::post('/activeShare/{user}','activeShare');
        Route::delete('/activeShare/{user}','deleteActiveShare');
    });

    Route::controller(LinkController::class)->group(function (){
        Route::get('/links','index');
        Route::post('/links','store');
        Route::put('/links/{link}','update');
        Route::delete('/links/{link}','delete');
    });

    Route::controller(FolloweeController::class)->group(function (){
        Route::get('/followers','index');
        Route::post('/followers','store');
    });
});




