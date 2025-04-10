<?php

declare(strict_types=1);

use App\Controllers\Auth\ChangePassword;
use App\Controllers\Feedback;
use App\Controllers\Files;
use App\Controllers\Reservations;
use App\Controllers\SchoolRegistration;
use App\Controllers\Welcome;
use App\Middlewares\Auth;
use Lemon\Route;
use Lemon\Session;

Route::collection(function () {
    Route::get('static/img/offers/{image}', [Files::class, 'offer']);
    Route::controller('change-password', ChangePassword::class);
    Route::controller('feedback', Feedback::class);
    Route::post('reservations/forward/{target}', [Reservations::class, 'forward']);
    Route::post('reservations/deny/{target}', [Reservations::class, 'deny']);
})->middleware([Auth::class, 'onlyAuthenticated']);

Route::get('/', [Welcome::class, 'handle']);

Route::get('static/img/discord/offers/{image}/{webhook}', [Files::class, 'discord']);

// Route::get('lang/{lang}', function($lang) {
//    Session::set('locale', $lang);
//    return redirect('/');
// });

// to show specific message, this route doesnt have mauth middleware
Route::get('reservations/acceptance/{target}', [Reservations::class, 'showToSeller']);

Route::template('/about', 'about');
Route::controller('/school-registration', SchoolRegistration::class);
Route::template('cookies');
Route::template('personal-info');
