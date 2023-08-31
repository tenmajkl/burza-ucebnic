<?php

namespace App;

use App\Contracts\Notifier as NotifierContract;
use App\Entities\OfferNotification;
use App\Entities\OfferNotificationType;
use App\Entities\Offer;
use App\Entities\User;
use Symfony\Component\Mailer\MailerInterface;

class Notifier implements NotifierContract
{
    public function __construct(
        public readonly MailerInterface $mailer,
        public readonly ORM $orm,
    ) {

    }

    public function notifyWishlist(User $user, Offer $offer): self
    {
        $this->saveOfferNotification($user, $offer, OfferNotificationType::Wishlist);

        return $this;
    }
// todo maile
    public function notifyRating(User $user, Offer $offer): self
    {
        $this->saveOfferNotification($user, $offer, OfferNotificationType::Rating);

        return $this;
    }
    
    public function notifyActiveReservation(User $user, Offer $offer): self
    {
        $this->saveOfferNotification($user, $offer, OfferNotificationType::ActiveReseration);
        return $this;
    }

    public function of(User $user): array
    {
        return $this->orm->getORM()->getRepository(OfferNotification::class)->findAll([ 
            'user.id' => $user->id,
        ]);
    }

    public function see(OfferNotification $notification): self
    {
        if ($notification->seen) {
            return $this;
        }

        $notification->seen = 1;
        $this->orm->getEntityManager()->persist($notification)->run();
        return $this;
    }

    private function saveOfferNotification(User $user, Offer $offer, OfferNotificationType $type): void
    {
        $this->orm->getEntityManager()->persist(new OfferNotification(
            $offer,
            $user,
            $type
        ))->run();
    } 
}
