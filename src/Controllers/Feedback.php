<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\Auth;
use App\Contracts\Discord;
use Lemon\Http\Request;
use Lemon\Templating\Template;

class Feedback
{
    public function get(): Template
    {
        return template('feedback');
    }

    public function post(Request $request, Discord $discord, Auth $auth): Template
    {
        $request->validate([
            'description' => 'max:1024|min:8',
        ], template('feedback'));

        if (!$discord->sendIssue(
            $request->get('description'),
            $auth->user()
        )) {
        }

        return template('feedback');
    }
}
