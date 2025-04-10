<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Contracts\Auth as AuthContract;
use Lemon\Contracts\Http\Session;

class Auth
{
    public function onlyAuthenticated(Session $session, AuthContract $auth)
    {
        if (!$session->has('email')) {
            return error(404);
        }

        if ($auth->user()->isBanned()) {
            return template('auth.banned', user: $auth->user());
        }
    }

    public function onlyGuest(Session $session)
    {
        if ($session->has('email')) {
            return redirect('/');
        }
    }

    public function onlyAdmin(AuthContract $auth)
    {
        if (0 === $auth->user()->role) {
            return error(404);
        }
    }
}
