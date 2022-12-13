<?php

use Cycle\Database\Config;

return [
        'default' => 'default',
        'databases' => [
            'default' => ['connection' => 'sqlite']
        ],
        'connections' => [
            'sqlite' => new Config\SQLiteDriverConfig(
                connection: new Config\SQLite\MemoryConnectionConfig(),
                queryCache: true,
            ),
        ]
];
