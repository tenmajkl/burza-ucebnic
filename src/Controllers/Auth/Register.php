<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Contracts\ORM;
use App\Entities\School;
use App\Entities\User;
use App\TokenGenerator;
use DateTime;
use Lemon\Http\Request;
use Lemon\Http\Response;
use Lemon\Http\Responses\RedirectResponse;
use Lemon\Templating\Template;
use Lemon\Validator;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Register
{
    public function get(ORM $orm): Template|RedirectResponse
    {
        if (_time() < 1726239600) {
            return redirect('/');
        }

        return template('auth.register');
    }

    public function post(Request $request, MailerInterface $mailer, ORM $orm): Template|Response
    {
        if (_time() < 1726239600) {
            return redirect('/');
        }

        $request->validate([
            'email' => 'max:128|email',
            'password' => 'max:128|min:8',
        ], template('auth.register'));

        $email = $request->get('email');
        [$login, $host] = explode('@', $email);

        $school = $orm->getORM()->getRepository(School::class)->select()
                      ->where(['email' => $host])
                      ->orWhere(['admin_email' => $host])
                      ->fetchAll();

        if ([] === $school) {
            Validator::addError('school-email', 'email', '');

            return template('auth.register');
        }

        $school = $school[0];
        $admin = $school->admin_email === $host;

        if ($user = $orm->getORM()->getRepository(User::class)->findOne(['email' => $login, 'email_host' => (int) $admin, 'year.school.id' => $school->id, 'verify_token' => null])) {
            return template('auth.register', message: 'auth.email-sent');
        }

        $raw_token = TokenGenerator::generate(); 
        $token = sha1($raw_token. $school->id);

        $message =
            (new Email())
                ->from(config('mail.from'))
                ->to($email)
                ->subject(text('auth.verify-subject'))
                ->html(template('mail.verify', token: $raw_token, school: $school)->render())
        ;

        $mailer->send($message);

        $password = password_hash($request->get('password'), PASSWORD_ARGON2I);
        $user = new User($login, (int) $admin, $password, (int) $admin, $token);        

        $orm->getEntityManager()->persist($user)->run();
        
        return template('auth.register', message: 'auth.email-sent');
    }
}
