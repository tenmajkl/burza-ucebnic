<?php

declare(strict_types=1);

use App\Controllers\Files;
use App\Middlewares\Auth;
use App\Controllers\Offers;
use App\Controllers\Welcome;
use App\Controllers\Api\Reservations;
use Lemon\Route;

Route::collection(function() {
    Route::get('static/img/offers/{image}', [Files::class, 'offer']);
    Route::controller('change-password', \App\Controllers\Auth\ChangePassword::class);
})->middleware([Auth::class, 'onlyAuthenticated'])
  ->middleware([Auth::class, 'onlyUser'])
;

Route::get('/', [Welcome::class, 'handle']);

Route::get('reservations/acceptance/{target}', [Reservations::class, 'showToSeller']); // to show specific message, this route doesnt have mauth middleware
Route::template('/about', 'about');
