<?php

use App\Controllers\Admin\Books;
use App\Controllers\Admin\Subjects;
use App\Controllers\Admin\Users;
use App\Controllers\Admin\Years;
use App\Controllers\Offers;
use Lemon\Route;

Route::redirect('/', 'years');

Route::controller('years', Years::class);

Route::controller('subjects', Subjects::class);

Route::controller('books', Books::class);

Route::controller('users', Users::class);

Route::controller('offers', Offers::class);
