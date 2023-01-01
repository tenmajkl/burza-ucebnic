<?php

use App\Controllers\Api\Offers;
use \Lemon\Route;

Route::get('offers', [Offers::class, 'all']);
