<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/bot/getupdates', [App\Http\Controllers\BotController::class, 'getupdates']);
Route::post('/bot/sendmessage', [App\Http\Controllers\BotController::class, 'sendmessage']);
Route::get('/bot/getme', [App\Http\Controllers\BotController::class, 'myInfo']);
Route::get('/bot/test', function () {
    return 'hi';
});