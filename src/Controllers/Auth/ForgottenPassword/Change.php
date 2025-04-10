<?php

declare(strict_types=1);

namespace App\Controllers\Auth\ForgottenPassword;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\User;
use Lemon\Contracts\Http\Session;
use Lemon\Http\Request;
use Lemon\Http\Response;
use Lemon\Templating\Template;

class Change
{
    public function get($token, Auth $auth): Response|Template
    {
        if (!$auth->canChangeForgottenPassword($token)) {
            return error(404);
        }

        return template('auth.forgotten-password.change');
    }

    public function post($token, Auth $auth, ORM $orm, Request $request, Session $session): Response|Template
    {
        if (!$auth->canChangeForgottenPassword($token)) {
            return error(404);
        }

        $request->validate([
            'password' => 'min:8|max:256',
        ], template('auth.forgotten-password.change'));

        [$email, $host] = explode('@', $session->get('reset-email'));

        $user = $orm->getORM()->getRepository(User::class)->findOne([
            'email' => $email,
        ]);
        $user->password = password_hash($request->get('password'), PASSWORD_ARGON2I);
        $orm->getEntityManager()->persist($user)->run();

        $session->clear();

        return redirect('/login');
    }
}
