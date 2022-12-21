<?php

namespace App\Controllers\Auth;

use App\Contracts\ORM;
use App\Entities\User;
use Lemon\Contracts\Http\Session;
use Lemon\Http\Responses\RedirectResponse;

class Verify
{
    public function get(string $token, Session $session, ORM $orm): RedirectResponse
    {
        $data = $session->get('verify_data');
        if ($token !== $data['token']) {
            return redirect('register');
        }

        $user = new User($data['email'], $data['password']);

        $orm->getEntityManager()->persist($user);

        $session->dontExpire();
        $session->set('email', $data['email']);
        return redirect('/');
    }
}
