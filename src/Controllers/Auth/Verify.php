<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Contracts\ORM;
use App\Entities\School;
use App\Entities\User;
use App\Entities\Year;
use Lemon\Contracts\Http\Session;
use Lemon\Http\Responses\RedirectResponse;
use Lemon\Templating\Template;

class Verify
{
    public function get($token, Session $session, ORM $orm): RedirectResponse|Template
    {
        if (!$session->has('verify_data') 
            || $token !== ($data = $session->get('verify_data'))['token']
        ) {
            return redirect('/register');
        }

        $year = $orm->getORM()->getRepository(Year::class)->findByPK($data['year']);

        [$email, $host] = explode('@', $data['email']);

        $school = $orm->getORM()->getRepository(School::class)->findOne(['email' => $host]);

        $user = new User($email, $data['password'], null);

        $orm->getEntityManager()->persist($user)->run();

        $session->dontExpire();
        $session->set('email', $data['email']);
        $years = $orm->getORM()->getRepository(Year::class)->findAll();

        return template('verify', years: $years);
    }

    public function post()
    {
    }
}
