<?php

declare(strict_types=1);

use App\Controllers\Api\Offers;
use Lemon\Route;

$offers = Route::controller('offers', Offers::class);
$offers->add('init', 'get', [Offers::class, 'init']);
