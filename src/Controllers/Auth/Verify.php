<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Contracts\ORM;
use App\Entities\School;
use App\Entities\User;
use App\Entities\Year;
use Lemon\Contracts\Http\Session;
use Lemon\Http\Request;
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

        $email= explode('@', $data['email']);

        $school = $orm->getORM()->getRepository(School::class)->findOne(['id' => $data['school']]);

        $years = $orm->getORM()->getRepository(Year::class)->findAll(['school_id' => $school->id, 'name' => ['!=' => 'admins']]);

        return template('auth.verify', years: $years);
    }

    public function post($token, Session $session, ORM $orm, Request $request): RedirectResponse|Template
    {
        if (!$session->has('verify_data') 
            || $token !== ($data = $session->get('verify_data'))['token']
        ) {
            return redirect('/register');
        }

        $request->validate([
            'year' => 'numeric',
        ], redirect('/verify/'.$token));

        $email = $data['email'];

        $year = $orm->getORM()->getRepository(Year::class)
                              ->findOne([
                                  'id' => $request->get('year'), 
                              ])
        ;

        if ($year === null) {
            return redirect('/verify/'.$token);
        }

        $user = new User($email, $data['password'], $year, 0);

        $session->dontExpire();
        $session->remove('verify_data');

        $session->set('email', $email);

        $orm->getEntityManager()->persist($user)->run();

        return redirect('/');
    }
}
