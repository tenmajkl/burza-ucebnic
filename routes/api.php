<?php

declare(strict_types=1);

use App\Controllers\Api\Account;
use App\Controllers\Api\Messages;
use App\Controllers\Api\Notifications;
use App\Controllers\Api\Offers;
use App\Controllers\Api\Rating;
use App\Controllers\Api\Reservations;
use App\Controllers\Api\Wishlist;
use App\Controllers\Auth\ChangePassword;
use App\Controllers\Auth\Logout;
use Lemon\Route;

Route::get('/offers/init', [Offers::class, 'init']);
Route::get('/offers/mine', [Offers::class, 'mine']);
Route::post('/offers/{target}/report', [Offers::class, 'report']);
Route::controller('offers', Offers::class);

$reservations = Route::controller('reservations', Reservations::class);
$reservations->add('make/{target}', 'post', [Reservations::class, 'make']);
$reservations->add('qr/{reservation}', 'get', [Reservations::class, 'qr']);

Route::controller('messages', Messages::class);

Route::get('wishlist/search/{target}', [Wishlist::class, 'search']);
Route::controller('wishlist', Wishlist::class);

Route::post('notifications/read-all', [Notifications::class, 'readAll']);
Route::post('notifications/clear', [Notifications::class, 'clear']);
Route::get('notifications/unread', [Notifications::class, 'getUnread']);
Route::controller('notifications', Notifications::class);

Route::get('/rating/verify/{target}', [Rating::class, 'verify']);
Route::post('/rating/{target}', [Rating::class, 'update']);

Route::post('logout', [Logout::class, 'post']);

Route::post('change-password', [ChangePassword::class, 'post']);

Route::get('/account/years', [Account::class, 'getYears']);
Route::post('/account/changeYear', [Account::class, 'changeYear']);
Route::post('/account/delete', [Account::class, 'delete']);
Route::get('/account/info', [Account::class, 'getInfo']);
