<?php

declare(strict_types=1);

namespace App\Controllers;

use Lemon\Templating\Template;

class Welcome
{
    public function handle(): Template
    {
        return template('welcome');
    }
}
