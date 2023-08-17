<?php

declare(strict_types=1);

use App\Controllers\Api\Offers;
use App\Controllers\Api\Messages;
use Lemon\Route;

$offers = Route::controller('offers', Offers::class);
$offers->add('init', 'get', [Offers::class, 'init']);

$reservations = Route::controller('reservations', \App\Controllers\Api\Reservations::class);
$reservations->add('make/{offer}', 'post', [\App\Controllers\Api\Reservations::class, 'make']);

Route::controller('messages', Messages::class);
