<?php

declare(strict_types=1);

use App\Directives\IfSet;
use Lemon\Templating\Juice\Syntax;

return [
    'cached' => 'storage.templates',
    'location' => 'resources.templates',
    'juice' => [
        'syntax' => new Syntax(),
    ],
    'directives' => [
        'ifset' => IfSet::class,
    ]
];
