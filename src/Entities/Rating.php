<?php

namespace App\Entities;

use App\Entities\Traits\DateTimes;
use Cycle\Annotated\Annotation\{Entity, Column};
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\ORM\Entity\Behavior;

#[Entity]
#[Behavior\CreatedAt(
    field: 'createdAt',
    column: 'created_at'
)]
#[Behavior\UpdatedAt(
    field: 'updatedAt',
    column: 'updated_at'
)]
class Rating
{
    use DateTimes;

    #[Column(type: 'primary')]
    public int $id;

    public function __construct(
        #[Column(type: 'int')]
        public int $rating,
        #[BelongsTo(target: User::class)]
        public User $author,
        #[BelongsTo(target: Offer::class)]
        public Offer $offer,
    ) {

    }
}
