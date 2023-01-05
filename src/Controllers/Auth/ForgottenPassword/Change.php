<?php

namespace App\Controllers\Auth\ForgottenPassword;

use App\Contracts\Auth;
use App\Contracts\ORM;
use Lemon\Http\Request;
use Lemon\Http\Response;
use Lemon\Templating\Template;

class Change
{
    public function get($target, Auth $auth): Template|Response
    {
        if (!$auth->canChangeForgottenPassword($target)) {
            return redirect('login');
        }

        return template('auth.forgotten-password.change');
    }

    public function post($target, Auth $auth, ORM $orm, Request $request): Template|Response
    {
        if (!$auth->canChangeForgottenPassword($target)) {
            return redirect('login');
        }
        
        $ok = $request->validate([
            'password' => 'min:8|max:256',
        ]);

        if (!$ok) {
            return template('auth.forgotten-password.change', message: 'validation_error');
        }

        $user = $auth->user();
        $user->password = $request->get('password');
        $orm->getEntityManager()->persist($user);

        return redirect('/login');
    }
}
