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
    public function handle(Auth $auth): Template
    {
        if (!$auth->user()) {
            return template('about');
        }
        return template('welcome');
    }
}
