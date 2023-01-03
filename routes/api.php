<?php

declare(strict_types=1);

use App\Controllers\Api\Offers;
use Lemon\Route;

Route::get('offers', [Offers::class, 'all']);
