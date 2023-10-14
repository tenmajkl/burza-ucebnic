<?php

namespace App;

use App\Contracts\Notifier as NotifierContract;
use App\Entities\OfferNotification;
use App\Entities\OfferNotificationType;
use App\Entities\Offer;
use App\Entities\User;
use Lemon\Templating\Template;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

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
        $this->mail('wishlist', $offer->book->name, template('mail.wishlist', offer: $offer), $user);
        return $this;
    }

    public function notifyRating(User $user, Offer $offer): self
    {
        $this->saveOfferNotification($user, $offer, OfferNotificationType::Rating);
        $this->mail('rating', $offer->user->email, template('mail.rating', offer: $offer), $user);
        return $this;
    }
    
    public function notifyActiveReservation(User $user, Offer $offer): self
    {
        $this->saveOfferNotification($user, $offer, OfferNotificationType::ActiveReseration);
        $this->mail('active-reservation', $offer->book->name, template('mail.active_reservation', offer: $offer), $user);
        return $this;
    }

    public function notifyNewReservation(User $user, Offer $offer): self
    {
        $this->saveOfferNotification($user, $offer, OfferNotificationType::NewReservation);
        $this->mail('new-reservations', $offer->book->name, template('mail.new_res$subject->year->idervations', offer: $offer), $user);
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

    private function mail(string $subject, string $arg, Template $template, User $user): void
    {
        $message = (new Email())
                    ->from(config('mail.from'))
                    ->to($user->email.'@'.$user->year->school->email)
                    ->subject(text('emoji-'.$subject).' '.str_replace('%arg', $arg, text('notification-'.$subject)))
                    ->html($template->render())
        ;

        $this->mailer->send($message);
    } 
}
