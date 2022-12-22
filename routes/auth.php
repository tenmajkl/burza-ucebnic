<?php

use App\Controllers\Auth\{Login, Register, Verify};
use App\Middlewares\Auth;
use Lemon\Route;

Route::controller('login', Login::class);

Route::controller('register', Register::class);

Route::get('verify/{token}', [Verify::class, 'get'])
    ->middleware([Auth::class, 'onlyUnverified']);
