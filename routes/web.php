<?php

use App\Controllers\Welcome;
use Lemon\Route;

Route::get('/', [Welcome::class, 'handle']);
