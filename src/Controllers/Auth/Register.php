<?php

namespace App\Controllers\Auth;

use App\Contracts\ORM;
use App\Entities\Year;
use Lemon\Contracts\Http\Session;
use Lemon\Http\Request;
use Lemon\Templating\Template;
use Lemon\Terminal;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Register
{
    public function get(ORM $orm): Template
    {
        $years = $orm->getORM()->getRepository(Year::class)->findAll();

        return template('auth.register', years: $years);
    }

    public function post(Request $request, Session $session, MailerInterface $mailer): Template
    {
        $ok = $request->validate([
            'email' => 'email|max:128|school_email',
            'password' => 'max:128|min:8',
            'year' => 'id:year',
        ]);

        if (!$ok) {
            return template('auth.register', message: 'validation-error');
        }

        $email = $request->get('email');

        $message = 
            (new Email())
                ->from(config('mail.from'))
                ->to($email)
                ->subject(text('verify_subject'))
                ->html(template('mail.verify')->render())
        ;

        $mailer->send($message);

        $token = str_shuffle(base64_encode(sha1(str_shuffle(rand().time().$email))));
        $password = password_hash($request->get('password'), PASSWORD_ARGON2I);
        $year = (int) $request->get('year');
        $session->set('verify_data', ['email' => $email, 'password' => $password, 'year' => $year, 'token' => $token]);
        $session->expireAt(300);

        return template('auth.register', message: 'email-sent');
    }
}
