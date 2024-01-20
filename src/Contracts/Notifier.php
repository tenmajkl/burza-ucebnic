<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Entities\Offer;
use App\Entities\Notification;
use App\Entities\User;
use App\Entities\Reservation;

interface Notifier
{
    public function notifyWishlist(User $user, Offer $offer): self;

    public function notifyRating(User $user, Reservation $reservation): self;

    public function notifyActiveReservation(User $user, Offer $offer): self;

    public function notifyNewReservation(User $user, Offer $offer): self;

    public function of(User $user): array;

    public function see(Notification $notification): self;
}
