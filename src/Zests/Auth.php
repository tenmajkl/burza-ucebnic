<?php

namespace App\Zests;

use App\Contracts\Auth as AuthContract;
use Lemon\Zest;

/**
 * @method static \App\Entities\User user() Returns curent user
 */
class Auth extends Zest
{
    public static function unit(): string
    {
        return AuthContract::class;
    }
}
