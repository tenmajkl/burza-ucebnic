<?php

namespace App\Controllers\Api;

use App\Contracts\Auth;
use App\Contracts\Notifier;
use App\Contracts\ORM;
use App\Entities\Offer;
use App\Entities\Reservation;
use App\Entities\ReservationState;
use DateTimeImmutable;

class Reservations
{
    public function make($target, Auth $auth, ORM $orm)
    {
        $offer = $orm->getORM()
                     ->getRepository(Offer::class)
                     ->findOne([
                         'id' => $target,
                         'book.subjects.year.id' => $auth->user()->year->id
                     ]);

        if (!$offer) {
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

        $status = $offer->reservations === [];

        $reservation = new Reservation($offer, $user, ReservationState::tryFrom((int) $status)); 

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

    public function delete($target, ORM $orm, Auth $auth)
    {
        $reservation = $orm->getORM()->getRepository(Reservation::class)->findOne([
            'hash' => $target,
            'user.id' => $auth->user()->id,
            'status' => ['!=' => 2],
        ]);

        if (!$reservation) {
            return error(404);
        }

        $orm->getEntityManager()->delete($reservation)->run();

        return [
            'status' => '200',
            'message' => 'OK',
        ];
    }

    public function disable($target, ORM $orm, Auth $auth)
    {
        $reservation = $orm->getORM()->getRepository(Reservation::class)->findOne([
            'id' => $target,
            'user.id' => $auth->user()->id,
            'status' => ReservationState::Active,
        ]);

        if ($reservation === null) {
            return response([
                'status' => '404',
                'message' => 'Not found',
            ])->code(404);
        }

        $offer = $reservation->offer;
        $orm->getEntityManager()->delete($reservation);

        $reservation = $orm->getORM()->getRepository(Reservation::class)
                      ->findOne([
                          'offer.id' => $offer->id, 
                      ]);

        $reservation->status = ReservationState::Active;

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
            'status' => 1,
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
            'status' => 1,
        ]) : null;

        return template('order-acceptance', reservation: $reservation, user: $user);
    }

    public function forward($target, ORM $orm, Auth $auth, Notifier $notifier)
    {
        $reservation = $orm->getORM()->getRepository(Reservation::class)->findOne([
            'hash' => $target,
            'offer.user.id' => $auth->user()->id,
            'status' => 1,
        ]);

        if ($reservation === null) {
            return error(404);
        }

        /** @var \App\Entities\Offer $offer */
        $offer = $reservation->offer;
        $offer->boughtAt = new DateTimeImmutable();
        $offer->buyer = $reservation->user;

        $offer->reservations = [];
        $notifier->notifyRating($auth->user(), $offer);
        $orm->getEntityManager()->persist($offer)->run();

        return redirect('/');
    }

    public function deny($target, ORM $orm, Auth $auth, Notifier $notifier)
    {
        $reservation = $orm->getORM()->getRepository(Reservation::class)->findOne([
            'hash' => $target,
            'offer.user.id' => $auth->user()->id,
            'status' => 1,
        ]);

        if ($reservation === null) {
            return error(404);
        }

        $reservation->status = ReservationState::Denied;
        $notifier->notifyRating($auth->user(), $reservation->offer);
        $orm->getEntityManager()->persist($reservation)->run();

        // TODO zaktivneni

        return redirect('/');
    }
}
