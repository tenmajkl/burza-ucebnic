<?php

declare(strict_types=1);

use App\Auth;
use App\Controllers\Admin\Books;
use App\Controllers\Admin\Users;
use App\Controllers\Admin\Years;
use Lemon\Config;
use Lemon\Route;

Route::redirect('/', '/admin/years');

Route::controller('years', Years::class);

Route::get('books/upload', [Books::class, 'uploadMenu']);
Route::post('books/upload', [Books::class, 'upload']);
$books = Route::controller('books', Books::class);

$users = Route::controller('users', Users::class);
$users->add('{target}/ban', 'get', [Users::class, 'banMenu']);
$users->add('{target}/ban', 'post', [Users::class, 'ban']);
$users->add('{target}/unban', 'get', [Users::class, 'unban']);

Route::get('/logs', function (Auth $auth) {
    if ('kripa19111' !== $auth->user()->email) {
        return error(404);
    }

    return file_get_contents(Config::file('logging.file', 'log'));
});
