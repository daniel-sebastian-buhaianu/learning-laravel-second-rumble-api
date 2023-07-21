<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ChannelController;

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

// Channel 
Route::get('/channels', [ChannelController::class, 'index']);
Route::post('/channels', [ChannelController::class, 'store']);
Route::get('/channels/{id}', [ChannelController::class, 'show']);
Route::patch('/channels/{id}', [ChannelController::class, 'update']);
Route::delete('/channels/{id}', [ChannelController::class, 'destroy']);

// Video
Route::get('/videos', [VideoController::class, 'index']);
Route::post('/videos', [VideoController::class, 'store']);
Route::get('/videos/{id}', [VideoController::class, 'show']);
Route::delete('/videos/{id}', [VideoController::class, 'destroy']);