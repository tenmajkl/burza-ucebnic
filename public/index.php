<?php

declare(strict_types=1);

use Lemon\Http\Request;

$maintenance = __DIR__.'/../maintenance.php';

if (file_exists($maintenance)) {
    require $maintenance;

    exit;
}

/** @var \Lemon\Kernel\Application $application */
$application = include __DIR__.'/../init.php';

$application->add(Request::class, Request::capture()->injectApplication($application));
$application->alias('request', Request::class);

$application->boot();
