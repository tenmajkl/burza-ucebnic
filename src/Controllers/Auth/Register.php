<?php

namespace App\Controllers\Auth;

use Lemon\Contracts\Http\Session;
use Lemon\Http\Request;
use Lemon\Http\Responses\TemplateResponse;
use Lemon\Templating\Template;
use Lemon\Terminal;

class Register
{
    public function get(): Template
    {
        return template('auth.register');
    }

    public function post(Request $request, Session $session): TemplateResponse
    {
        $ok = $request->validate([
            'email' => 'email|max:128|school_email',
            'password' => 'max:128|min:8',
        ]);

        if (!$ok) {
            return template('auth.register', message: 'validation-error');
        }

        // TODO send mail with symfony mailer
        $token = str_shuffle(base64_encode(sha1(str_shuffle(rand().time().$request->email))));
        $password = password_hash($request->get('password'));
        $session->set('verify_data', ['email' => $request->email, 'password' => $password, 'token' => $token]);
        $session->expireAt(300);

        return template('auth.register', message: 'email-sent');
    }
}
