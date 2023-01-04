<?php

namespace App\Controllers\Auth;

use App\Auth;
use App\Contracts\ORM;
use Lemon\Http\Request;
use Lemon\Templating\Template;

class ChangePassword
{
    public function get(): Template
    {
        return template('auth.change-password');
    }

    public function post(Request $request, Auth $auth, ORM $orm): Template
    {
        $ok = $request->validate([
            'old_password' => 'max:256',
            'password' => 'min:8|max:256'
        ]);

        if (!$ok) {
            return template('auth.change-password', message: 'validation-error');
        }

        $user = $auth->user();

        if (!password_verify($request->get('password'), $user->password)) {
            return template('auth.change-password', message: 'auth-error');
        }

        $user->password = $request->get('password');
        $orm->getEntityManager()->persist($user);

        return template('auth.change-password', message: 'success');
    }
}
