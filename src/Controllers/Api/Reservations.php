<?php

namespace App\Controllers\Api;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Offer;
use App\Entities\Reservation;

class Reservations
{
    public function make(?Offer $offer, Auth $auth, ORM $orm)
    {
        if (!$offer) {
            return response([
                'status' => '404',
                'message' => 'Offer not found',
            ])->code(404);
        }

        $user = $auth->user();

        if (array_filter($offer->reservations, fn ($reservation) => $reservation->user->id === $user->id)) {
            return response([
                'status' => '400',
                'message' => 'already-reserved',
            ])->code(400);
        }

        $active = $offer->reservations === [];

        $reservation = new Reservation($offer, $user, (int) $active); 

        $orm->getEntityManager()->persist($reservation)->run();

        return response([
            'status' => '200',
            'message' => 'OK',
        ])->code(200);
    }

    public function index(Auth $auth)
    {
        $user = $auth->user();

        return $user->reservations;
    }
}
