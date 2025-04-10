<?php

declare(strict_types=1);

use Lemon\Http\Request;
use Lemon\Kernel\Application;

$maintenance = __DIR__.'/../maintenance.php';

if (file_exists($maintenance)) {
    require $maintenance;

    exit;
}

/** @var Application $application */
$application = include __DIR__.'/../init.php';

$application->add(Request::class, Request::capture()->injectApplication($application));
$application->alias('request', Request::class);

$application->boot();
