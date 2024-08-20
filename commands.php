<?php

declare(strict_types=1);

use App\Contracts\ORM;
use App\Entities\School;
use App\Entities\User;
use App\Entities\Year;
use Lemon\Support\CaseConverter;
use Lemon\Terminal;

Terminal::command('entity:make {name}', function ($name) {
    $entity = file_get_contents(__DIR__.'/stubs/Entity.php.stub');
    $name = CaseConverter::toPascal($name);
    $file = __DIR__.'/src/Entities/'.$name.'.php';
    file_put_contents($file, str_replace('{name}', $name, $entity));
}, 'Generates new entity with given name');

Terminal::command('server', function () {
    Terminal::out('<div class="text-yellow">Dev server started at https://localhost:8000</div>');
    exec('php -S localhost:8000 -t public &');
    exec('yarn run mix watch &');
}, 'Starts server with mix watch');

Terminal::command('school:make {name} {email} {adminEmail}', function (ORM $orm, $name, $email, $adminEmail) {
    $school = new School(
        $name,
        $email,
        $adminEmail,
    );
    $year = new Year('teachers', $school, 0);
    $password = base64_encode(random_bytes(16));
    Terminal::out('<div class="text-yellow">Mrkej na heslo: '.$password.'</div>');
    $password = password_hash($password, PASSWORD_DEFAULT);
    $root = new User('root', 1, $password, 1, null, $year);
    $orm->getEntityManager()->persist($school)->run();
    $orm->getEntityManager()->persist($root)->run();
}, 'Creates new school');
