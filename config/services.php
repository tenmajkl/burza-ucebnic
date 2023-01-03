<?php

use App\Auth;
use App\Mailer;
use App\ORM;
use App\Contracts\ORM as ORMContract;
use App\Contracts\Auth as AuthContract;
use Symfony\Component\Mailer\MailerInterface;

return [
    ORM::class => [ORMContract::class],
    Auth::class => [AuthContract::class],
    Mailer::class => [MailerInterface::class],
];
