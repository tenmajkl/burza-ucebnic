<?php

declare(strict_types=1);

namespace App\Controllers\Auth\ForgottenPassword;

use App\Contracts\ORM;
use App\Entities\User;
use App\TokenGenerator;
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
            'email' => 'email',
        ], template('auth.forgotten-password.request'));

        [$email, $host] = explode('@', $request->get('email'));

        $users = $orm->getORM()->getRepository(User::class)->select()->where('email', $email)->where(function ($select) use ($host) {
            $select->where('year.school.email', $host)
                ->orWhere('year.school.admin_email', $host)
            ;
        })->fetchAll();

        if (!isset($users[0])) {
            // trolling hackers, they cant find out if the users is registered gg
            return template('auth.forgotten-password.request', message: 'auth.reset-email-sent');
        }

        $user = $users[0];

        if ($user->email() !== $request->get('email')) {
            return template('auth.forgotten-password.request', message: 'auth.reset-email-sent');
        }

        $token = TokenGenerator::generate();

        $session->set('token', $token);
        $session->set('reset-email', $request->get('email'));

        $mailer->send(
            (new Email())
                ->from(config('mail.from'))
                ->to($request->get('email'))
                ->subject(text('auth.forgotten-password-subject'))
                ->html(template('mail.forgotten-password', token: $token)->render())
        );

        return template('auth.forgotten-password.request', message: 'auth.reset-email-sent');
    }
}
