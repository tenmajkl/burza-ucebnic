<?php

namespace App\Controllers;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Reservation;

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
