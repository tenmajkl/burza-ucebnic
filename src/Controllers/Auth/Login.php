<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Entities\User;
use App\ORM;
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

        $year = $user->year;
        $school = $year->school;

        $expected_host = match ($user->role) {
            0 => $school->email,
            1 => $school->admin_email,
        };

        if ($host !== $expected_host) {
            return template('auth.login', message: 'auth.error');
        }

        $session->set('email', explode('@', $request->get('email'))[0]);

        $session->expireAt(31536000); // 1 year

        return redirect('/');
    }
}
