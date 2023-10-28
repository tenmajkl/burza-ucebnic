<?php

declare(strict_types=1);

use App\Controllers\Api\Messages;
use App\Controllers\Api\Notifications;
use App\Controllers\Api\Offers;
use App\Controllers\Api\Rating;
use App\Controllers\Api\Wishlist;
use App\Controllers\Auth\ChangePassword;
use App\Controllers\Auth\Logout;
use Lemon\Route;

Route::get('/offers/init', [Offers::class, 'init']);
Route::get('/offers/mine', [Offers::class, 'mine']);
Route::controller('offers', Offers::class);

$reservations = Route::controller('reservations', \App\Controllers\Api\Reservations::class);
$reservations->add('make/{target}', 'post', [\App\Controllers\Api\Reservations::class, 'make']);
$reservations->add('qr/{reservation}', 'get', [\App\Controllers\Api\Reservations::class, 'qr']);
$reservations->add('disable/{target}', 'post', [\App\Controllers\Api\Reservations::class, 'disable']);

Route::controller('messages', Messages::class);

Route::controller('wishlist', Wishlist::class);

Route::controller('notifications', Notifications::class);

Route::get('/rating/verify/{target}', [Rating::class, 'verify']);
Route::post('/rating/{target}', [Rating::class, 'update']);

Route::post('logout', [Logout::class, 'post']);

Route::post('change-password', [ChangePassword::class, 'post']);
