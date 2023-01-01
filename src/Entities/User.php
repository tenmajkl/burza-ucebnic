<?php

namespace App\Entities;

use App\Entities\Traits\DateTimes;
use Cycle\Annotated\Annotation\{Entity, Column};
use Cycle\Annotated\Annotation\Relation\{BelongsTo, HasMany};
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
class User
{
    use DateTimes;

    #[Column(type: 'primary')]
    public int $id;

    #[HasMany(target: Offer::class)]
    public array $offers = [];

    #[HasMany(target: Reservation::class)]
    public array $reservations = [];

    public function __construct(
        #[Column(type: 'string')]
        public string $email,
        #[Column(type: 'string')]
        public string $password,
        #[BelongsTo(target: Year::class)]
        public Year $year,
    ) {

    }
}
