<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Offer;
use App\Entities\Reservation;
use Lemon\Http\Request;

class Reservations
{
    public function index(Auth $auth)
    {
        $reservations = $auth->user()->reservations;

        return template('reservations.index', reservations: $reservations);
    }

    public function store(Request $request, Auth $auth, ORM $orm)
    {
        // TODO maybe api?
        $ok = $request->validate([
            'offer' => 'id:offer',
        ]);

        if (!$ok) {
            return template('offers.show', message: 'validation_error');
        }

        $user = $auth->user();
        $offer = $orm->getORM()->getRepository(Offer::class)->findByPK((int) $request->get('offer'));
        if ($offer->user->id == $user->id) {
            return template('offers.show', message: 'reservation-author-error');
        }

        $book = $offer->book->id;
        
        // todo maybe bad idea idk
        if (!empty(array_filter($user->reservations, fn(Reservation $reservation) => $reservation->offer->book->id == $book))) {
            return template('offers.show', message: 'reservation-exists-error');
        }

        $reservation = new Reservation($offer, $user);
    
        $orm->getEntityManager()->persist($reservation);

        return template('offers.show');
    }

    public function destroy($target, Auth $auth, ORM $orm)
    {
        $reservation = $orm->getORM()->getRepository(Reservation::class)->findByPK($target);
        $user = $auth->user()->id;
        if ($reservation->author->id !== $user || $reservation->offer->author->id !== $user) {
            return error(403);
        }

        $orm->getEntityManager()->delete($reservation);

        return redirect('/');
    }

    public function aprove($hash)
    {
    }
}
