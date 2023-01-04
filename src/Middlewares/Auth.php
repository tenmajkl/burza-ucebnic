<?php

declare(strict_types=1);

namespace App\Middlewares;

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
}
