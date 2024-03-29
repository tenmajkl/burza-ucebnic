<?php

namespace App\Controllers;

use App\Entities\Reservation;
use App\Entities\ReservationState;
use App\Contracts\Notifier;
use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\RatingAbility;
use App\Entities\Inquiry;

class Reservations
{
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

        if (null === $reservation) {
            return error(404);
        }

        /** @var \App\Entities\Offer $offer */
        $offer = $reservation->offer;
        $offer->boughtAt = new \DateTimeImmutable();
        $offer->buyer = $reservation->user;

        $offer->reservations = [];

        $inquiry = $orm->getORM()->getRepository(Inquiry::class)->findOne([
            'book.id' => $offer->book->id,
            'user.id' => $auth->user()->id,
        ]);

        if ($inquiry) {
            $orm->getEntityManager()->delete($inquiry)->run(); 
        }

        $orm->db()->table('notifications')->delete()->where([
            'offer_id' => $offer->id,
        ])->run();

        $orm->db()->table('reservations')->delete()->where([
            'offer_id' => $offer->id,
        ])->run();


        $rating = new RatingAbility(
            $offer->buyer,
            $offer->user,
        );

        $orm->getEntityManager()->persist($rating)->run();

        $orm->getEntityManager()->persist($offer)->run();
        $notifier->notifyRating($reservation->user, $rating);

        return redirect('/');
    }

    public function deny($target, ORM $orm, Auth $auth, Notifier $notifier)
    {
        $reservation = $orm->getORM()->getRepository(Reservation::class)->findOne([
            'hash' => $target,
            'offer.user.id' => $auth->user()->id,
            'status' => 1,
        ]);

        if (null === $reservation) {
            return error(404);
        }

        $reservation->status = ReservationState::Denied;
        $offer = $reservation->offer;
        $deletion = $orm->getEntityManager()->persist($reservation);

        $buyer = $reservation->user;

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

        $notifier->notifyRating($buyer, $reservation);

        // TODO less boilerplate, maybe

        return redirect('/');
    }

}
