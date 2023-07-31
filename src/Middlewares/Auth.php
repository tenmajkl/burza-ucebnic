<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Contracts\Auth as AuthContract;
use Lemon\Contracts\Http\Session;

class Auth
{
    public function onlyAuthenticated(Session $session)
    {
        if (!$session->has('email')) {
            return redirect('/login');
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
        if ($auth->user()->role === 0) {
            return error(404);
        }
    }

    public function onlyUser(AuthContract $auth)
    {
        if ($auth->user()->role === 1) {
            return redirect('/admin/');
        }
    }

    public function onlyMajkel(AuthContract $auth)
    {
        if ($auth->user()->role !== 2) {
            return error(404);
        }
    }
}
