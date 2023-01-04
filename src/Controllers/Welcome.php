<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\ORM;
use App\Entities\User;
use Lemon\Templating\Template;

class Welcome
{
    public function handle(ORM $orm): Template
    {
        var_dump($orm->getORM()->getRepository(User::class)->findAll());
        return template('welcome');
    }
}
