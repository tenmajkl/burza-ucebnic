<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Contracts\ORM;
use App\Entities\School;
use App\Entities\User;
use App\Entities\Year;
use Lemon\Contracts\Http\Session;
use Lemon\Http\Responses\RedirectResponse;

class Verify
{
    public function get($token, Session $session, ORM $orm): RedirectResponse
    {
        if (!$session->has('data') || $token !== ($data = $session->get('data'))['token']) {
            return redirect('/register');
        }

        $year = $orm->getORM()->getRepository(Year::class)->findByPK($data['year']);

        [$email, $host] = explode('@', $data['email']);

        $school = $orm->getORM()->getRepository(School::class)->findOne(['email' => $host]);

        $user = new User($email, $data['password'], $year, $school);

        $orm->getEntityManager()->persist($user)->run();

        $session->dontExpire();
        $session->set('email', $data['email']);

        return redirect('/');
    }
}
