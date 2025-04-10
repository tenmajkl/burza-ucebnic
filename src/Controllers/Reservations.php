<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\Auth;
use App\Contracts\Discord;
use App\Contracts\Notifier;
use App\Contracts\ORM;
use App\Entities\Inquiry;
use App\Entities\Offer;
use App\Entities\RatingAbility;
use App\Entities\Reservation;
use App\Entities\ReservationState;
use Lemon\Kernel\Application;

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

    public function forward($target, ORM $orm, Auth $auth, Notifier $notifier, Application $app, Discord $discord)
    {
        $reservation = $orm->getORM()->getRepository(Reservation::class)->findOne([
            'hash' => $target,
            'offer.user.id' => $auth->user()->id,
            'status' => 1,
        ]);

        if (null === $reservation) {
            return error(404);
        }

        /** @var Offer $offer */
        $offer = $reservation->offer;
        $offer->boughtAt = new \DateTimeImmutable();
        $offer->buyer = $reservation->user;

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

        $job = $orm->getEntityManager()->persist($rating);

        $inquiry = $orm->getORM()->getRepository(Inquiry::class)->findOne([
            'book.id' => $offer->book->id,
            'user.id' => $auth->user()->id,
        ]);

        if ($inquiry) {
            $job->delete($inquiry)->run();
        }

        unlink($app->file('storage.images.offers.'.$offer->id, 'image'));
        $job->persist($offer)->run();
        $notifier->notifyRating($reservation->user, $rating);
        $discord->sendSuccess();

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

        $offer = $reservation->offer;
        $deletion = $orm->getEntityManager()->delete($reservation);

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

        $rating = new RatingAbility($buyer, $offer->author);
        $orm->getEntityManager()->persist($rating)->run();
        $notifier->notifyRating($buyer, $rating);

        // TODO less boilerplate, maybe

        return redirect('/');
    }
}
