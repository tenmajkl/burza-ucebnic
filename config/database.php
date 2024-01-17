<?php

declare(strict_types=1);

use Cycle\Database\Config;
use Lemon\Env;

return [
    'default' => 'default',
    'databases' => [
        'default' => ['connection' => Env::get('DB_DRIVER')],
    ],
    'connections' => [
        'sqlite' => new Config\SQLiteDriverConfig(
            connection: new Config\SQLite\FileConnectionConfig(
                database: Env::file('DB_FILE', 'db', 'database.db'),
            ),
            queryCache: true,
        ),
        //'mysql' => new Config\MySQLDriverConfig(
        //    connection: new Config\MySQL\TcpConnectionConfig(
        //        database: Env::get('DB_DATABASE', ''),
        //        host: Env::get('DB_HOST', ''),
        //        port: Env::get('DB_PORT', ''),
        //        user: Env::get('DB_USER', ''),
        //        password: Env::get('DB_PASSWORD', '')
        //    ),
        //    queryCache: true,
        //),
    ],
];
