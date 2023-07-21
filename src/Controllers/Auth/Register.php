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

        $school = $orm->getORM()->getRepository(School::class)->findOne(['email' => $email]);

        if ($school === null) {
            Validator::addError('school-email', 'email', '');
            return template('auth.register');
        }

        if ($orm->getORM()->getRepository(User::class)->findOne(['email' => $email])) {
            Validator::addError('school-email', 'email', '');
            return template('auth.register', message: 'auth.user-exists');
        }

        $token = str_shuffle(sha1(str_shuffle(rand().time().$email)));
        
        $message =
            (new Email())
                ->from(config('mail.from'))
                ->to($email)
                ->subject(text('auth.verify_subject'))
                ->html(template('mail.verify', token: $token)->render())
        ;

        $mailer->send($message);

        $password = password_hash($request->get('password'), PASSWORD_ARGON2I);
        $session->set('verify_data', ['email' => $email, 'password' => $password, 'school' => $school->id, 'token' => $token]);
        $session->expireAt(3);

        return template('auth.register', message: 'auth.email-sent');
    }
}
