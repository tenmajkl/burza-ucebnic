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
