<?php

declare(strict_types=1);

use App\Auth;
use App\Contracts\Auth as AuthContract;
use App\Contracts\Discord as DiscordContract;
use App\Contracts\ORM as ORMContract;
use App\Contracts\Notifier as NotifierContract;
use App\Integrations\Discord;
use App\Mailer;
use App\Notifier;
use App\ORM;
use Symfony\Component\Mailer\MailerInterface;

return [
    'services' => [
        ORM::class => [ORMContract::class],
        Auth::class => [AuthContract::class],
        Mailer::class => [MailerInterface::class],
        Discord::class => [DiscordContract::class],
        Notifier::class => [NotifierContract::class],
    ],
    'timezone' => 'Europe/Prague',
];
