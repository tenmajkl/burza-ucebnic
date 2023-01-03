<?php

namespace App\Entities;

use App\Entities\Traits\DateTimes;
use Cycle\Annotated\Annotation\{Entity, Column};
use Cycle\Annotated\Annotation\Relation\{BelongsTo, HasMany};
use Cycle\ORM\Entity\Behavior;
use DateTimeImmutable;

/**
 * AHOJTE LIDI, MAM PRO VAS VELICE ZAJIMAVOU NABIDKU
 */
#[Entity]
#[Behavior\CreatedAt(
    field: 'createdAt',
    column: 'created_at'
)]
#[Behavior\UpdatedAt(
    field: 'updatedAt', 
    column: 'updated_at'
)]
#[Entity]
#[Behavior\SoftDelete(
    field: 'deletedAt',   
    column: 'deleted_at'  
)]
class Offer
{
    use DateTimes;

    #[Column(type: 'primary')]
    public int $id;

    #[HasMany(target: Reservation::class)]
    public array $reservations;

    #[Column(type: 'datetime', nullable: true)]
    public ?DateTimeImmutable $updatedAt;

    public function __construct(
        #[BelongsTo(target: Book::class)]
        public Book $book,
        #[Column(type: 'int')]
        public int $price,
        #[Column(type: 'int')]
        public string $description,
        #[BelongsTo(target: User::class)]
        public User $author
    ) {

    }
}
