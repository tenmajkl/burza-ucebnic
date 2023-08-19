<?php

declare(strict_types=1);

use App\Controllers\Auth\Login;
use App\Controllers\Auth\Logout;
use App\Controllers\Auth\Register;
use App\Controllers\Auth\Verify;
use App\Controllers\Auth\ForgottenPassword\Request;
use App\Controllers\Auth\ForgottenPassword\Change;
use App\Middlewares\Auth;
use Lemon\Route;

Route::controller('login', Login::class);

Route::controller('register', Register::class);

Route::get('verify/{token}', [Verify::class, 'get']);
Route::post('verify/{token}', [Verify::class, 'post']);

Route::get('logout', [Logout::class, 'get'])
    ->exclude([Auth::class, 'onlyGuest'])
    ->middleware([Auth::class, 'onlyAuthenticated'])
;

Route::controller('forgotten-password', Request::class);
Route::get('forgotten-password/{token}', [Change::class, 'get']);
Route::post('forgotten-password/{token}', [Change::class, 'post']);

