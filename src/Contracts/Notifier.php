<?php

namespace App\Contracts;

use App\Entities\Offer;
use App\Entities\OfferNotification;
use App\Entities\User;

interface Notifier
{
    public function notifyWishlist(User $user, Offer $offer): self;

    public function notifyRating(User $user, Offer $offer): self;

    public function notifyActiveReservation(User $user, Offer $offer): self;

    public function notifyNewReservation(User $user, Offer $offer): self;

    public function of(User $user): array;

    public function see(OfferNotification $notification): self; 
}
