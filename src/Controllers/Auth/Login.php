<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Entities\User;
use App\ORM;
use DateTime;
use Lemon\Contracts\Http\Session;
use Lemon\Http\Request;
use Lemon\Http\Responses\RedirectResponse;
use Lemon\Templating\Template;

class Login
{
    public function get(): Template
    {
        return template('auth.login');
    }

    public function post(ORM $orm, Request $request, Session $session): RedirectResponse|Template
    {
        $request->validate([
            'email' => 'max:128|email',
            'password' => 'max:128|min:8',
        ], template('auth.login'));

        [$email, $host] = explode('@', $request->get('email'));

        $user = $orm->getORM()->getRepository(User::class)->findOne([
            'email' => $email,
        ]);

        if (!$user || !password_verify($request->get('password'), $user->password)) {
            return template('auth.login', message: 'auth.error');
        }

        if ($user->verify_token) {
            if ($user->createdAt->diff(new DateTime("now"))->i > 10)  {
                $orm->getEntityManager()->delete($user);
            }

            return redirect('/login');
        }

        if ($user->email() !== $request->get('email')) {
            return template('auth.login', message: 'auth.error');
        }

        $session->set('email', explode('@', $request->get('email'))[0]);

        $session->expireAt(31536000); // 1 year

        return redirect('/');
    }
}
