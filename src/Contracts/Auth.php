<?php

namespace App\Contracts;

use App\Entities\User;

interface Auth
{
    public function user(): User;
}
