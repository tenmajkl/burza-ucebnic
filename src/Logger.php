<?php

namespace App;

use Lemon\Logging\Logger as LemonLogger;
use Lemon\Contracts\Logging\Logger as LoggerContract;
use Lemon\Contracts\Config\Config;
use App\Contracts\Discord;

/**
 * Dirty Lemon hack to send messages to discord
 */
class Logger extends LemonLogger implements LoggerContract
{
    public function __construct(
        Config $config,
        private Discord $discord,
    ) {
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $this->discord->sendWebhook([
            'embeds' => [
                [
                    'title' => 'KRACH NA BURZE (ERROR) <:kanec_exploze:1107056283003142166>',
                    'description' => $message,
                    'color' => 0xFF0000,
                    'timestamp' => date('c'),
                ],
            ],
        ]);
        parent::log($level, $message, $context);
    }
    
}
