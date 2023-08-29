<?php

declare(strict_types=1);

use App\Controllers\Files;
use App\Controllers\Feedback;
use App\Middlewares\Auth;
use App\Controllers\Welcome;
use App\Controllers\Api\Reservations;
use Lemon\Route;

Route::collection(function() {
    Route::get('static/img/offers/{image}', [Files::class, 'offer']);
    Route::controller('change-password', \App\Controllers\Auth\ChangePassword::class);
    Route::controller('feedback', Feedback::class);
})->middleware([Auth::class, 'onlyAuthenticated'])
  ->middleware([Auth::class, 'onlyUser'])
;

Route::get('/', [Welcome::class, 'handle']);

Route::get('reservations/acceptance/{target}', [Reservations::class, 'showToSeller']); // to show specific message, this route doesnt have mauth middleware
Route::template('/about', 'about');
