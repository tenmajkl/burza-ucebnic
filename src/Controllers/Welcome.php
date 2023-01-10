<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Subject;
use App\Entities\User;
use App\Entities\Year;
use Lemon\Templating\Template;

class Welcome
{
    public function handle(Auth $auth, ORM $orm): Template
    {
        $years = $orm->getORM()->getRepository(Year::class)->findAll();
        $subjects = $orm->getORM()->getRepository(Subject::class)->findAll();
        $sorts = [];
        $year = $auth->user()->year->id;
        return template('welcome', years: $years, subjects: $subjects, sorts: $sorts, year: $year);
    }
}
