<?php

namespace App\Controllers\Auth\ForgottenPassword;

use App\TokenGenerator;
use App\Contracts\ORM;
use App\Entities\User;
use Lemon\Http\Request as LemonRequest;
use Lemon\Http\Session;
use Lemon\Templating\Template;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Request
{
    public function get(): Template
    {
        return template('auth.forgotten-password.request');
    }

    public function post(LemonRequest $request, MailerInterface $mailer, ORM $orm, Session $session): Template
    {
        $ok = $request->validate([
            'email' => 'email'
        ]);

        if (!$ok) {
            return template('auth.forgotten-password.request', message: 'validation-error');
        }

        if (is_null($orm->getORM()->getRepository(User::class)->findOne(['email' => $request->get('email')]))) {
            return template('auth.forgotten-password.request', message: 'auth-error');
        }

        $token = TokenGenerator::generate($request->get('email'));

        $session->set('token', $token);

        $mailer->send((new Email())
               ->from(config('mail.from'))
               ->to($request->get('email'))
               ->subject(text('forgotten-password-subject')) 
               ->html(template('email.forgotten-password', token: $token)->render()) 
        );

        return template('auth.forgotten-password.request', message: 'email-sent');
    }
}
