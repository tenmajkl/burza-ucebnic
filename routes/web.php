<?php

declare(strict_types=1);

use App\Controllers\Api\Reservations;
use App\Controllers\Feedback;
use App\Controllers\Files;
use App\Controllers\Welcome;
use App\Middlewares\Auth;
use Lemon\Route;

Route::collection(function () {
    Route::get('static/img/offers/{image}', [Files::class, 'offer']);
    Route::controller('change-password', \App\Controllers\Auth\ChangePassword::class);
    Route::controller('feedback', Feedback::class);
    Route::get('reservations/forward/{target}', [Reservations::class, 'forward']);
    Route::get('reservations/deny/{target}', [Reservations::class, 'deny']);
})->middleware([Auth::class, 'onlyAuthenticated'])
    ->middleware([Auth::class, 'onlyUser'])
;

Route::get('/', [Welcome::class, 'handle']);

Route::get('reservations/acceptance/{target}', [Reservations::class, 'showToSeller']); // to show specific message, this route doesnt have mauth middleware
Route::template('/about', 'about');
