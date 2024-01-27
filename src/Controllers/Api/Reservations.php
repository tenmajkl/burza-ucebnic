<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Contracts\Auth;
use App\Contracts\Notifier;
use App\Contracts\ORM;
use App\Entities\Inquiry;
use App\Entities\Offer;
use App\Entities\RatingAbility;
use App\Entities\Reservation;
use App\Entities\ReservationState;

class Reservations
{
    public function make($target, Auth $auth, ORM $orm, Notifier $notifier)
    {
        $offer = $orm->getORM()
            ->getRepository(Offer::class)
            ->findOne([
                'id' => $target,
                'book.subjects.year.id' => $auth->user()->year->id,
            ])
        ;

        if (!$offer) {
            return error(404); 
        }

        if ($offer->user->id === $auth->user()->id) {
            return response([
                'status' => '400',
                'message' => text('cannot-reserve-own-offer'),
            ])->code(400);
        }

        $user = $auth->user();

        if (array_filter($offer->reservations, fn ($reservation) => $reservation->user->id === $user->id)) {
            return response([
                'status' => '400',
                'message' => text('already-reserved'),
            ])->code(400);
        }

        $status = [] === $offer->reservations;

        $reservation = new Reservation($offer, $user, ReservationState::tryFrom((int) $status));

        $orm->getEntityManager()->persist($reservation)->run();

        $notifier->notifyNewReservation($offer->user, $offer);

        return [
            'status' => '200',
            'message' => 'OK',
        ];
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

    public function delete($target, ORM $orm, Auth $auth, Notifier $notifier)
    {
        $reservation = $orm->getORM()->getRepository(Reservation::class)->findOne([
            'id' => $target,
            'user.id' => $auth->user()->id,
            'status' => ReservationState::Active,
        ]);

        if (null === $reservation) {
            return error(404); 
        }

        $offer = $reservation->offer;
        $deletion = $orm->getEntityManager()->delete($reservation);

        $reservation = $orm->getORM()->getRepository(Reservation::class)
            ->findOne([
                'offer.id' => $offer->id,
                'id' => ['!=' => $reservation->id],
            ])
        ;

        if ($reservation) {
            $reservation->status = ReservationState::Active;
            $orm->getEntityManager()->persist($reservation)->run();

            $notifier->notifyActiveReservation($reservation->user, $offer);
        } else {
            $deletion->run();
        }

        return [
            'status' => '200',
            'message' => 'OK',
        ];
    }

    /**
     * Shows active reservation of offer (only to the owner of the offer).
     *
     * @param mixed $target
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

    }
