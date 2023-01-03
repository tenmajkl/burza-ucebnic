<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\Auth;

class Reservations
{
    public function index(Auth $auth)
    {
        $reservations = $auth->user()->reservations;

        return template('reservations.index', reservations: $reservations);
    }

    public function store($reservation)
    {
    }

    public function destroy($reservation)
    {
    }

    public function aprove($hash)
    {
    }
}
