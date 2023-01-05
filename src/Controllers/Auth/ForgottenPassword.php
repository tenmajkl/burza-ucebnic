<?php

namespace App\Controllers\Auth;

use App\Contracts\Auth;
use App\TokenGenerator;
use App\Contracts\ORM;
use App\Entities\User;
use Lemon\Contracts\Http\Session;
use Lemon\Http\Request;
use Lemon\Http\Response;
use Lemon\Templating\Template;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


/**
 * I forgor
 */
class ForgottenPassword
{
    public function get(): Template
    {
        return template('auth.forgotten-password');
    }

    public function post(Request $request, MailerInterface $mailer, ORM $orm, Session $session): Template
    {
        $ok = $request->validate([
            'email' => 'email'
        ]);

        if (!$ok) {
            return template('auth.forgotten-password', message: 'validation-error');
        }

        if (is_null($orm->getORM()->getRepository(User::class)->findOne(['email' => $request->get('email')]))) {
            return template('auth.forgotten-password', message: 'auth-error');
        }

        $token = TokenGenerator::generate($request->get('email'));

        $session->set('token', $token);

        $mailer->send((new Email())
               ->from(config('mail.from'))
               ->to($request->get('email'))
               ->subject(text('forgotten-password-subject')) 
               ->html(template('email.forgotten-password', token: $token)->render()) 
        );

        return template('auth.forgotten-password', message: 'email-sent');
    }

    public function change($token, Auth $auth): Template|Response
    {
        if (!$auth->canChangeForgottenPassword($token)) {
            return redirect('login');
        }

        return template('auth.change-forgotten-password');
    }

    public function changePost($token, Auth $auth, ORM $orm, Request $request): Template|Response
    {
        if (!$auth->canChangeForgottenPassword($token)) {
            return redirect('login');
        }
        
        $ok = $request->validate([
            'password' => 'min:8|max:256',
        ]);

        if (!$ok) {
            return template('auth.change-forgotten-password', message: 'validation_error');
        }

        $user = $auth->user();
        $user->password = $request->get('password');
        $orm->getEntityManager()->persist($user);

        return redirect('/login');
    }
}
