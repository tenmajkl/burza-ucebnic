<?php

namespace App\Controllers\Admin;

class Books
{
    public function index()
    {
        return template('admin.books.index');
    }
}
