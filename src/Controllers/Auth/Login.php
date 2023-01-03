<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Entities\User;
use App\ORM;
use Lemon\Http\Request;
use Lemon\Http\Responses\RedirectResponse;
use Lemon\Templating\Template;

class Login
{
    public function get(): Template
    {
        return template('auth.login');
    }

    public function post(ORM $orm, Request $request): RedirectResponse|Template
    {
        $ok = $request->validate([
            'email' => 'max:128|email|school_email',
            'password' => 'max:128|min:8',
        ]);

        if (!$ok) {
            return template('auth.login', message: 'validation-error');
        }

        $user = $orm->getORM()->getRepository(User::class)->findOne([
            'email' => $request->get('email'),
        ]);

        if (!password_verify($request->get('password'), $user->password)) {
            return template('login', message: 'auth-error');
        }

        return redirect('/');
    }
}
