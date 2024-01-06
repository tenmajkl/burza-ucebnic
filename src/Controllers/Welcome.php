<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\Auth;
use Lemon\Http\Responses\RedirectResponse;
use Lemon\Templating\Template;

class Welcome
{
    public function handle(Auth $auth): Template|RedirectResponse
    {
        if (!$auth->user()) {
            return template('about');
        }

        return template('welcome', admin: $auth->user()->role !== 0);
    }
}
