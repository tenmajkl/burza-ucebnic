<?php

declare(strict_types=1);

use App\Auth;
use App\Contracts\Auth as AuthContract;
use App\Contracts\Discord as DiscordContract;
use App\Contracts\ORM as ORMContract;
use App\Integrations\Discord;
use App\Mailer;
use App\ORM;
use Symfony\Component\Mailer\MailerInterface;

return [
    'services' => [
        ORM::class => [ORMContract::class],
        Auth::class => [AuthContract::class],
        Mailer::class => [MailerInterface::class],
        Discord::class => [DiscordContract::class]
    ],
    'timezone' => 'Europe/Prague',
];
