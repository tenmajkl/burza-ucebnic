<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Contracts\ORM;
use App\Entities\School;
use App\Entities\User;
use App\TokenGenerator;
use Lemon\Contracts\Http\Session;
use Lemon\Http\Request;
use Lemon\Templating\Template;
use Lemon\Validator;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Register
{
    public function get(ORM $orm): Template
    {
        return template('auth.register');
    }

    public function post(Request $request, Session $session, MailerInterface $mailer, ORM $orm): Template
    {
        $request->validate([
            'email' => 'max:128|email',
            'password' => 'max:128|min:8',
        ], template('auth.register'));

        $email = $request->get('email');
        [$login, $host] = explode('@', $email);

        $school = $orm->getORM()->getRepository(School::class)->findOne(['email' => $host]);

        if (null === $school) {
            Validator::addError('school-email', 'email', '');

            return template('auth.register');
        }

        if ($orm->getORM()->getRepository(User::class)->findOne(['email' => $login])) {
            Validator::addError('user-exists', 'email', '');

            return template('auth.register');
        }

        $token = TokenGenerator::generate();

        $message =
            (new Email())
                ->from(config('mail.from'))
                ->to($email)
                ->subject(text('auth.verify-subject'))
                ->html(template('mail.verify', token: $token)->render())
        ;

        $mailer->send($message);

        $password = password_hash($request->get('password'), PASSWORD_ARGON2I);
        $session->set('verify_data', ['email' => $login, 'password' => $password, 'school' => $school->id, 'token' => $token]);
        $session->expireAt(3);

        return template('auth.register', message: 'auth.email-sent');
    }
}
