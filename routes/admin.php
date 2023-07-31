<?php

use App\Controllers\Admin\Books;
use App\Controllers\Admin\Subjects;
use App\Controllers\Admin\Users;
use App\Controllers\Admin\Years;
use App\Controllers\Offers;
use Lemon\Route;

Route::redirect('/', 'years');

Route::controller('years', Years::class);

Route::controller('books', Books::class);

$users = Route::controller('users', Users::class);
$users->add('{target}/ban', 'get', [Users::class, 'banMenu']);
$users->add('{target}/ban', 'post', [Users::class, 'ban']);

Route::controller('offers', Offers::class);
