<?php

namespace App\Middlewares;

use Lemon\Contracts\Http\Session;
use Lemon\Http\Responses\RedirectResponse;

class Auth
{
    public function onlyAuthenticated(Session $session): ?RedirectResponse
    {
        if (!$session->has('email')) {
            return redirect('login');
        }
    }

    public function onlyGuest(Session $session): ?RedirectResponse
    {
        if ($session->has('email')) {
            return redirect('/');
        }
    }

    public function onlyUnverified(Session $session): ?RedirectResponse
    {
        if (!$session->has('verify_data')) {
            return redirect('register');
        }
    }
}
