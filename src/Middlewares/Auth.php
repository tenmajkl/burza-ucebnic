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
        if ($auth->user()->email !== 'majkel') {
            return error(404);
        }    
    }
}
