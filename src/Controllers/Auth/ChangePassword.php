<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Auth;
use App\Contracts\ORM;
use Lemon\Http\Request;
use Lemon\Validator;

class ChangePassword
{
    public function post(Request $request, Auth $auth, ORM $orm): mixed
    {
        $request->validate([
            'old_password' => 'max:256',
            'password' => 'min:8|max:256',
        ], fn () => response([
            'status' => 400,
            'message' => Validator::error(),
        ])->code(400));

        $user = $auth->user();

        if (!password_verify($request->get('old_password'), $user->password)) {
            return response([
                'status' => 400,
                'message' => text('auth.wrong-password'),
            ])->code(400);
        }

        $user->password = password_hash($request->get('password'), PASSWORD_ARGON2I);
        $orm->getEntityManager()->persist($user)->run();

        return [
            'status' => 200,
            'message' => 'OK',
        ];
    }
}
