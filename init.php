<?php

declare(strict_types=1);

include __DIR__.'/vendor/autoload.php';

use App\Middlewares\Auth;
use App\Rules;
use Lemon\Http\Middlewares\Cors;
use Lemon\Kernel\Application;
use Lemon\Protection\Middlwares\Csrf;

$application = new Application(__DIR__);

// --- Loading default Lemon services ---
$application->loadServices();

// --- Loading Zests for services ---
$application->loadZests();

// --- Loading Error/Exception handlers ---
$application->loadHandler();

$application->get('config')->load();

// --- Loading services from config/app.php ---
foreach (config('app.services') as $service => $aliases) {
    $application->add($service);
    foreach ($aliases as $alias) {
        $application->alias($alias, $service);
    }
}

$compiler = $application->get('juice');
foreach (config('templating.directives') as $name => $class) {
    $compiler->addDirectiveCompiler($name, $class);
}

date_default_timezone_set(config('app.timezone'));
@ini_set('date.timezone', config('app.timezone'));

new Rules($application);

/** @var \Lemon\Routing\Router $router */
$router = $application->get('routing');

$router->file('routes.web')
       ->middleware(Csrf::class)
       ->middleware([Auth::class, 'onlyAuthenticated'])
;

$router->file('routes.api')
       ->prefix('api')
       ->middleware(Cors::class)
;

$router->file('routes.auth')
       ->middleware([Auth::class, 'onlyGuest'])
       ->middleware(Csrf::class)
;

return $application;
