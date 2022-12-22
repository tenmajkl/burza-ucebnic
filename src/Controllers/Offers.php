<?php

namespace App\Controllers\Api;

use Lemon\Http\Request;
use Lemon\Templating\Template;

class Offers
{
    public function create(): Template
    {
        return template('offers.create');
    }

    public function store(Request $request)
    {
        $ok = $request->validate([
            'book' => 'book',
            'price' => 'numeric',
            'description' => 'max:256',
        ]);

        if (!$ok) {
            return template('offers.create', message: 'validation-error');
        }

    }

    public function show()
    {
        return template('offers.show')
    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}
