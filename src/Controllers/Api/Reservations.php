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

        return [
            'status' => '200',
            'message' => 'OK',
            'data' => $user->reservations,
        ];
    }

    public function qr(?Reservation $reservation)
    {
        if (!$reservation) {
            return error(404);
        }

        return response([
            'status' => '200',
            'message' => 'OK',
            'data' => $reservation->hash,
        ])->code(200);
    }

    public function showToSeller($target, ORM $orm, Auth $auth)
    {
        $reservation = $orm->getRepository(Reservation::class)->findOne([
            'hash' => $target,
            'offer.user.id' => $auth->user()->id,
        ]);

        if (!$reservation) {
            return error(404);
        }

        return response([
            'status' => '200',
            'message' => 'OK',
            'data' => $reservation,
        ])->code(200);
    }

    public function forward($target, ORM $orm)
    {

    }
}
