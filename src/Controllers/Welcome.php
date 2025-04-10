<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\Auth;
use Lemon\Http\Responses\RedirectResponse;
use Lemon\Templating\Template;

class Welcome
{
    public function handle(Auth $auth): RedirectResponse|Template
    {
        if (!$auth->user()) {
            return template('about', welcome: true);
        }

        return template('welcome', admin: 0 !== $auth->user()->role);
    }
}
