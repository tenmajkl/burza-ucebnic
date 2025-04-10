<?php

declare(strict_types=1);

namespace App\Entities;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Traits\DateTimes;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\ORM\Entity\Behavior;
use Lemon\Contracts\Kernel\Injectable;
use Lemon\Kernel\Container;

#[Entity]
#[Behavior\CreatedAt(
    field: 'createdAt',
    column: 'created_at'
)]
/**
 * Represents notification somehow related to direct offer.
 */
class Notification implements \JsonSerializable, Injectable
{
    use DateTimes;

    #[Column(type: 'primary')]
    public int $id;

    public function __construct(
        #[BelongsTo(target: Offer::class, nullable: true)]
        public ?Offer $offer,
        #[BelongsTo(target: RatingAbility::class, nullable: true)]
        public ?RatingAbility $rating,
        #[BelongsTo(target: User::class)]
        public User $user,
        #[Column(type: 'int', typecast: NotificationType::class)]
        public NotificationType $type,
        #[Column(type: 'bool')]
        public bool|int $seen = 0,
    ) {}

    public function jsonSerialize(): mixed
    {
        return
            $this->offer
            ? [
                'id' => $this->id,
                'offer' => $this->offer,
                'user' => $this->user,
                'type' => $this->type,
                'seen' => (bool) $this->seen,
                'created_at' => diff($this->createdAt),
            ]
            : [
                'id' => $this->id,
                'rating' => $this->rating,
                'user' => $this->user,
                'type' => NotificationType::Rating,
                'seen' => (bool) $this->seen,
                'created_at' => diff($this->createdAt),
                'can_rate' => $this->rating?->user->id === $this->user->id,
            ];
    }

    public static function fromInjection(Container $container, mixed $value): ?self
    {
        return $container->get(ORM::class)
            ->getORM()
            ->getRepository(self::class)
            ->findOne([
                'id' => $value,
                'user.id' => $container->get(Auth::class)->user()->id,
            ])
        ;
    }
}
