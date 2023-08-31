<?php

namespace App\Entities;

use App\Entities\Traits\DateTimes;
use Cycle\Annotated\Annotation\{Entity, Column};
use Cycle\ORM\Entity\Behavior;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use JsonSerializable;

#[Entity]
#[Behavior\CreatedAt(
    field: 'createdAt',
    column: 'created_at'
)]
/**
 * Represents notification somehow related to user 
 */
class OfferNotification implements JsonSerializable
{
    use DateTimes;

    #[Column(type: 'primary')]
    public int $id;

    public function __construct(
        #[BelongsTo(target: Offer::class)]
        public Offer $offer,
        #[BelongsTo(target: User::class)]
        public User $user,
        #[Column(type: 'int', typecast: OfferNotificationType::class)]
        public OfferNotificationType $type,
        #[Column(type: 'bool')]
        public int $seen = 0,
    ) {

    }

    public function jsonSerialize(): mixed
    {
        return [
            'offer' => $this->offer,
            'user' => $this->user,
            'type' => $this->type,
            'seen' => (bool) $this->seen,
            'created_at' => diff($this->createdAt),
        ];
    }
}
