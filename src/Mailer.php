<?php

declare(strict_types=1);

namespace App;

use Lemon\Contracts\Config\Config;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Mailer as SymfonyMailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\RawMessage;

/**
 * Hack that adapts symfony mailer to Lemon container.
 */
class Mailer implements MailerInterface
{
    private SymfonyMailer $mailer;

    public function __construct(Config $config)
    {
        $this->mailer = new SymfonyMailer(Transport::fromDsn($config->get('mail.dsn')));
    }

    public function send(RawMessage $message, Envelope $envelope = null): void
    {
        if (config('debug.debug')) {
            return;
            d($message); return; // dirty debugging practice
        }
        $this->mailer->send($message, $envelope);
    }
}
