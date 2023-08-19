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
            return error(404);
        }

        // this is terrible I guess
        if (array_intersect($offer->book->subjects, $auth->user()->year->subjects) === []) {
            return error(404);
        }

        if ($offer->user->id === $auth->user()->id) {
            return response([
                'status' => '400',
                'message' => 'cannot-reserve-own-offer',
            ])->code(400);
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

    public function delete($target, ORM $orm, Auth $user)
    {
        $reservation = $orm->getRepository(Reservation::class)->findOne([
            'hash' => $target,
            'user.id' => $auth->user()->id,
            'active' => true,
        ]);

        if (!$reservation) {
            return error(404);
        }

        $reservation->active = 0;

        $orm->getEntityManager()->persist($reservation)->run();

        return [
            'status' => '200',
            'message' => 'OK',
        ];
    }

    /**
     * Shows active reservation of offer (only to the owner of the offer)
     */
    public function show($target, ORM $orm, Auth $auth)
    {
        $reservation = $orm->getORM()->getRepository(Reservation::class)->findOne([
            'offer.id' => $target,
            'offer.user.id' => $auth->user()->id,
            'active' => true,
        ]);

        if (!$reservation) {
            return error(404);
        }

        return [
            'status' => '200',
            'message' => 'OK',
            'data' => $reservation,
        ];
    }

    public function qr(?Reservation $reservation)
    {
        // author of offer can theoreticaly access qr code but that shoudl be ok idk
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
        $user = $auth->user();
        $reservation = $user ? $orm->getORM()->getRepository(Reservation::class)->findOne([
            'hash' => $target,
            'offer.user.id' => $auth->user()->id,
            'active' => true,
        ]) : null;

        return template('order-acceptance', reservation: $reservation, user: $user);
    }

    public function forward($target, ORM $orm)
    {

    }
}
