<?php

declare(strict_types=1);

use App\Controllers\Files;
use App\Middlewares\Auth;
use App\Controllers\Offers;
use App\Controllers\Welcome;
use Lemon\Route;

Route::collection(function() {
    Route::get('/', [Welcome::class, 'handle']);
    Route::get('static/img/offers/{image}', [Files::class, 'offer']);
})->middleware([Auth::class, 'onlyAuthenticated'])
  ->middleware([Auth::class, 'onlyUser'])
;

Route::template('/about', 'about');
