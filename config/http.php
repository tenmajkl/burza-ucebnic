<?php

declare(strict_types=1);

use Lemon\Env;

return [
    'cors' => [
        'alowed-origins' => Env::get('CORS_ALLOWED_ORIGINS', '*'),
        'expose-headers' => null,
        'max-age' => null,
        'allowed-credentials' => null,
        'alowed-methods' => null,
        'allowed-headers' => null,
    ],
    'session' => [
        'name' => 'PHP_SESSION',
    ],
];
