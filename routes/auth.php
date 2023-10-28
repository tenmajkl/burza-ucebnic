<?php

declare(strict_types=1);

use App\Controllers\Auth\ForgottenPassword\Change;
use App\Controllers\Auth\ForgottenPassword\Request;
use App\Controllers\Auth\Login;
use App\Controllers\Auth\Register;
use App\Controllers\Auth\Verify;
use Lemon\Route;

Route::controller('login', Login::class);

Route::controller('register', Register::class);

Route::get('verify/{token}', [Verify::class, 'get']);
Route::post('verify/{token}', [Verify::class, 'post']);

Route::controller('forgotten-password', Request::class);
Route::get('forgotten-password/{token}', [Change::class, 'get']);
Route::post('forgotten-password/{token}', [Change::class, 'post']);
