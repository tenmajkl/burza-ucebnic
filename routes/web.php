<?php

declare(strict_types=1);

use App\Controllers\Reservations;
use App\Controllers\Feedback;
use App\Controllers\Files;
use App\Controllers\Welcome;
use App\Middlewares\Auth;
use Lemon\Route;

Route::collection(function () {
    Route::get('static/img/offers/{image}', [Files::class, 'offer']);
    Route::controller('change-password', \App\Controllers\Auth\ChangePassword::class);
    Route::controller('feedback', Feedback::class);
    Route::post('reservations/forward/{target}', [Reservations::class, 'forward']);
    Route::post('reservations/deny/{target}', [Reservations::class, 'deny']);
})->middleware([Auth::class, 'onlyAuthenticated']);

Route::get('/', [Welcome::class, 'handle']);

// to show specific message, this route doesnt have mauth middleware
Route::get('reservations/acceptance/{target}', [Reservations::class, 'showToSeller']); 

Route::template('/about', 'about');
Route::template('cookies');
Route::template('personal_info');
