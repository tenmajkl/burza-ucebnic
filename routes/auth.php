<?php

declare(strict_types=1);

use App\Controllers\Auth\Login;
use App\Controllers\Auth\Register;
use App\Controllers\Auth\Verify;
use App\Middlewares\Auth;
use Lemon\Route;

Route::controller('login', Login::class);

Route::controller('register', Register::class);

Route::get('verify/{token}', [Verify::class, 'get'])
    ->middleware([Auth::class, 'onlyUnverified'])
;
