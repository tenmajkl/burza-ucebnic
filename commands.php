<?php

declare(strict_types=1);

use Lemon\Support\CaseConverter;
use Lemon\Terminal;

Terminal::command('entity:make {name}', function ($name) {
    $entity = file_get_contents(__DIR__.'/stubs/Entity.php.stub');
    $name = CaseConverter::toPascal($name);
    $file = __DIR__.'/src/Entities/'.$name.'.php';
    file_put_contents($file, str_replace('{name}', $name, $entity));
}, 'Generates new entity with given name');

Terminal::command('server', function() {
    Terminal::out('<div class="text-yellow">Dev server started at https://localhost:8000</div>');
    exec('php -S localhost:8000 -t public &');
    exec('yarn run mix watch &');
}, 'starts server with mix watch');
