<?php

declare(strict_types=1);

namespace App;

use App\Contracts\Notifier as NotifierContract;
use App\Entities\Offer;
use App\Entities\OfferNotification;
use App\Entities\OfferNotificationType;
use App\Entities\User;
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
        $this->saveOfferNotification($user, $offer, OfferNotificationType::Wishlist);
        $this->mail('wishlist', $offer->book->name, $user);

        return $this;
    }

    public function notifyRating(User $user, Offer $offer): self
    {
        $this->saveOfferNotification($user, $offer, OfferNotificationType::Rating);
        $this->mail('rating', $offer->user->email, $user);

        return $this;
    }

    public function notifyActiveReservation(User $user, Offer $offer): self
    {
        $this->saveOfferNotification($user, $offer, OfferNotificationType::ActiveReseration);
        $this->mail('active-reservation', $offer->book->name, $user);

        return $this;
    }

    public function notifyNewReservation(User $user, Offer $offer): self
    {
        $this->saveOfferNotification($user, $offer, OfferNotificationType::NewReservation);
        $this->mail('new-reservations', $offer->book->name, $user);

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

    private function saveOfferNotification(User $user, Offer $offer, OfferNotificationType $type): void
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
