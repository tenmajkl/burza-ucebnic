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
})->middleware([Auth::class, 'onlyAuthenticated']);

Route::get('/', [Welcome::class, 'handle']);

Route::get('reservations/acceptance/{target}', [Reservations::class, 'showToSeller']); // to show specific message, this route doesnt have mauth middleware
Route::template('/about', 'about');

Route::template('cookies');
Route::template('personal_info');

Route::get('upload/{password}', function($password) {
    if (!password_verify($password, '$argon2i$v=19$m=65536,t=4,p=1$cXdrVVQwLmN5ckJXRU51ZQ$QVr3QBUj/B0bJ+ODFlZjSpPwn2pkTf48J+hGlGql9hU')) {
        return error(404);
    }

    system('git pull; yarn mix');
});
