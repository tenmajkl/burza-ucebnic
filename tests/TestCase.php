<?php

namespace Tests;

use Lemon\Kernel\Application;
use Lemon\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function createApplication(): Application
    {
        return require __DIR__.'/../init.php';
    }
}
