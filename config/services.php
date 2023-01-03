<?php

declare(strict_types=1);

use App\Auth;
use App\Contracts\Auth as AuthContract;
use App\Contracts\ORM as ORMContract;
use App\Mailer;
use App\ORM;
use Symfony\Component\Mailer\MailerInterface;

return [
    ORM::class => [ORMContract::class],
    Auth::class => [AuthContract::class],
    Mailer::class => [MailerInterface::class],
];
