<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\Auth;
use App\Contracts\Discord;
use Lemon\Http\Request;
use Lemon\Http\Response;
use Lemon\Templating\Template;

class SchoolRegistration
{
    public function get(): Template
    {
        return template('school-registration');
    }

    public function post(Request $request, Discord $discord, Auth $auth): Response
    {
        $request->validate([
            'email' => 'email',
            'school-name' => 'max:256|min:8',
        ], template('school-registration'));

        if (!$discord->sendRequest(
            $request->get('email'),
            $request->get('school-name'),
        )) {
        }

        return redirect('/');
    }
}
