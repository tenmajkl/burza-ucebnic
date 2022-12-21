<?php

use App\Auth;
use App\ORM;
use App\Contracts\ORM as ORMContract;
use App\Contracts\Auth as AuthContract;

return [
    ORM::class => [ORMContract::class],
    Auth::class => [AuthContract::class],
];
