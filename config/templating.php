<?php

declare(strict_types=1);

use Lemon\Templating\Juice\Syntax;

return [
    'cached' => 'storage.templates',
    'location' => 'resources.templates',
    'juice' => [
        'syntax' => new Syntax(),
    ],
];
