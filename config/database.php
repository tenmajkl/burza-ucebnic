<?php

use Cycle\Database\Config;
use Lemon\Env;

return [
        'default' => 'default',
        'databases' => [
            'default' => ['connection' => 'sqlite']
        ],
        'connections' => [
            'sqlite' => new Config\SQLiteDriverConfig(
                connection: new Config\SQLite\FileConnectionConfig(
                    database: Env::file('DB_FILE', 'db', 'database.db'),
                ),
                queryCache: true,
            ),
        ]
];
