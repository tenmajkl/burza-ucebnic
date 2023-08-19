<?php

namespace App\Controllers\Auth;

use Lemon\Contracts\Http\Session;
use Lemon\Http\Responses\RedirectResponse;

class Logout
{
    public function post(Session $session): RedirectResponse
    {
        $session->clear();
        return redirect('login');
    }
}
