<?php

use Lemon\Debug\Style;
use Lemon\Env;

return [
    'dumper' => [
        'style' => new Style(),
    ],
    'debug' => Env::get('DEBUG'),
];
