<?php

use App\Models\User;
use App\Models\Video;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\RegistrationController;
use App\Http\Middleware\AuthenticateOnceWithBasicAuth;

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

// Registration
Route::post('/register', [RegistrationController::class, 'store']);

Route::middleware(AuthenticateOnceWithBasicAuth::class)->group(function () {
    // User
    Route::get('/users', [UserController::class, 'index'])->can('viewAny', User::class);
    Route::get('/users/{user}', [UserController::class, 'show'])->can('view', 'user');
    Route::patch('/users/{user}', [UserController::class, 'update'])->can('update', 'user');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->can('delete', 'user');

    // Channel 
    Route::get('/channels', [ChannelController::class, 'index'])->can('viewAny', Channel::class);
    Route::post('/channels', [ChannelController::class, 'store'])->can('create', Channel::class);
    Route::get('/channels/{channel}', [ChannelController::class, 'show'])->can('view', 'channel');
    Route::patch('/channels/{channel}', [ChannelController::class, 'update'])->can('update', 'channel');
    Route::delete('/channels/{channel}', [ChannelController::class, 'destroy'])->can('delete', 'channel');

    // Video
    Route::get('/videos', [VideoController::class, 'index'])->can('viewAny', Video::class);
    Route::post('/videos', [VideoController::class, 'store'])->can('create', Video::class);
    Route::get('/videos/{video}', [VideoController::class, 'show'])->can('view', 'video');
    Route::patch('/videos/{video}', [VideoController::class, 'update'])->can('update', 'video');
    Route::delete('/videos/{video}', [VideoController::class, 'destroy'])->can('delete', 'video');
});

