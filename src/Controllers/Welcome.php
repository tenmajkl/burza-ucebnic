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

        if ($auth->user()->role === 1) {
            return redirect('/admin');
        }

        return template('welcome');
    }
}
