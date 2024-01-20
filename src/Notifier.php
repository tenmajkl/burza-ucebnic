<?php

declare(strict_types=1);

namespace App;

use App\Contracts\Notifier as NotifierContract;
use App\Entities\Offer;
use App\Entities\OfferNotification;
use App\Entities\RatingNotification;
use App\Entities\NotificationType;
use App\Entities\User;
use App\Entities\Reservation;
use Cycle\Database\Query\SelectQuery;
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
        $this->saveOfferNotification($user, $offer, NotificationType::Wishlist);
        $this->mail('wishlist', $offer->book->name, $user);

        return $this;
    }

    public function notifyRating(User $user, Reservation $reservation): self
    {
        $not = new RatingNotification($reservation, $user);
        $this->orm->getEntityManager()->persist($not)->run();
        $this->mail('rating', $reservation->user->email, $user);

        return $this;
    }

    public function notifyActiveReservation(User $user, Offer $offer): self
    {
        $this->saveOfferNotification($user, $offer, NotificationType::ActiveReseration);
        $this->mail('active-reservation', $offer->book->name, $user);

        return $this;
    }

    public function notifyNewReservation(User $user, Offer $offer): self
    {
        $this->saveOfferNotification($user, $offer, NotificationType::NewReservation);
        $this->mail('new-reservation', $offer->book->name, $user);

        return $this;
    }

    public function of(User $user): array
    {
        return $this->orm->getORM()->getRepository(OfferNotification::class)->select()->where([
            'user.id' => $user->id,
        ])->orderBy('id', SelectQuery::SORT_DESC)->fetchAll();
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

    private function saveOfferNotification(User $user, Offer $offer, NotificationType $type): void
    {
        $this->orm->getEntityManager()->persist(new OfferNotification(
            $offer,
            $user,
            $type
        ))->run();
    }

    private function mail(string $subject, string $arg, User $user): void
    {
        $text = text('app.emoji-'.$subject).' '.str_replace('%arg', $arg, text('app.notification-'.$subject));
        $message = (new Email())
            ->from(config('mail.from'))
            ->to($user->email())
            ->subject($text)
            ->html(template('mail.notifications', text: $text)->render())
        ;

        $this->mailer->send($message);
    }
}
