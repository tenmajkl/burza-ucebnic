<?php

declare(strict_types=1);

use App\Controllers\Admin\Books;
use App\Controllers\Admin\Users;
use App\Controllers\Admin\Years;
use App\Controllers\Offers;
use Lemon\Route;

Route::redirect('/', 'years');

Route::controller('years', Years::class);

Route::get('books/upload', [Books::class, 'uploadMenu']);
Route::post('books/upload', [Books::class, 'upload']);
$books = Route::controller('books', Books::class);

$users = Route::controller('users', Users::class);
$users->add('{target}/ban', 'get', [Users::class, 'banMenu']);
$users->add('{target}/ban', 'post', [Users::class, 'ban']);
$users->add('{target}/unban', 'get', [Users::class, 'unban']);

// Route::controller('offers', Offers::class);
