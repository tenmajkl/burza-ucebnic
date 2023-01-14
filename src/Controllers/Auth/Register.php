<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Contracts\ORM;
use App\Entities\School;
use App\Entities\User;
use App\Entities\Year;
use Lemon\Contracts\Http\Session;
use Lemon\Http\Request;
use Lemon\Templating\Template;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Register
{
    public function get(ORM $orm): Template
    {
        $years = $orm->getORM()->getRepository(Year::class)->findAll();

        return template('auth.register', years: $years);
    }

    public function post(Request $request, Session $session, MailerInterface $mailer, ORM $orm): Template
    {
        $ok = $request->validate([
            'email' => 'max:128|school-email',
            'password' => 'max:128|min:8',
            'year' => 'id:year',
        ]);

        $years = $orm->getORM()->getRepository(Year::class)->findAll();

        if (!$ok) {
            return template('auth.register', message: 'validation-error', years: $years);
        }

        $email = $request->get('email');

        if ($orm->getORM()->getRepository(User::class)->findOne(['email' => $email])) {
            return template('auth.register', message: 'user-exists', years: $years);
        }

        $token = str_shuffle(sha1(str_shuffle(rand().time().$email)));
        
        $message =
            (new Email())
                ->from(config('mail.from'))
                ->to($email)
                ->subject(text('verify_subject'))
                ->html(template('mail.verify', token: $token)->render())
        ;

        $mailer->send($message);

        $password = password_hash($request->get('password'), PASSWORD_ARGON2I);
        $year = (int) $request->get('year');
        $session->set('verify_data', ['email' => $email, 'password' => $password, 'year' => $year, 'token' => $token]);
        $session->expireAt(3);

        return template('auth.register', message: 'email-sent', years: $years);
    }
}
