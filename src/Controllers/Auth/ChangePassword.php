<?php

namespace App\Controllers\Auth;

use App\Auth;
use App\Contracts\ORM;
use Lemon\Http\Request;
use Lemon\Templating\Template;
use Lemon\Validator;

class ChangePassword
{
    public function post(Request $request, Auth $auth, ORM $orm): Template
    {
        $request->validate([
            'old_password' => 'max:256',
            'password' => 'min:8|max:256'
        ], fn() => response([ 
            'status' => 400,
            'message' => Validator::error(),
        ])->code(400));

        $user = $auth->user();

        if (!password_verify($request->get('password'), $user->password)) {
            return template('auth.change-password', message: 'auth.error');
        }

        $user->password = $request->get('password');
        $orm->getEntityManager()->persist($user);

        return [
            'status' => 200,
            'message' => 'OK',
        ];
    }
}
