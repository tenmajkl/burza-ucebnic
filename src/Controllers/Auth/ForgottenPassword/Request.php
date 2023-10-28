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
        $request->validate([
            'email' => 'email'
        ], template('auth.forgotten-password.request'));

        if (is_null($orm->getORM()->getRepository(User::class)->findOne(['email' => explode('@', $request->get('email'))[0]]))) {
            return template('auth.forgotten-password.request', message: 'auth.wrong-email');
        }

        $token = TokenGenerator::generate($request->get('email'));

        $session->set('token', $token);
        $session->set('reset-email', $request->get('email'));

        $mailer->send((new Email())
               ->from(config('mail.from'))
               ->to($request->get('email'))
               ->subject(text('auth.forgotten-password-subject')) 
               ->html(template('mail.forgotten-password', token: $token)->render()) 
        );

        return template('auth.forgotten-password.request', message: 'auth.reset-email-sent');
    }
}
