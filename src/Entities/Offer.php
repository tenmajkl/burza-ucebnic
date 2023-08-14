<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\DateTimes;
use App\Entities\Traits\Dynamic;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Cycle\ORM\Entity\Behavior;

/**
 * AHOJTE LIDI, MAM PRO VAS VELICE ZAJIMAVOU NABIDKU.
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
#[Behavior\SoftDelete(
    field: 'deletedAt',
    column: 'deleted_at'
)]
class Offer implements \JsonSerializable
{
    use DateTimes, Dynamic;

    #[Column(type: 'primary')]
    public int $id;

    #[HasMany(target: Reservation::class)]
    public array $resevations = [];

    #[Column(type: 'datetime', nullable: true)]
    public ?\DateTimeImmutable $updatedAt;

    public function __construct(
        #[BelongsTo(target: Book::class)]
        public Book $book,
        #[Column(type: 'int')]
        public int $price,
        #[Column(type: 'int', typecast: BookState::class)]
        public BookState $state,
        #[BelongsTo(target: User::class, nullable: true)]
        public User $user,
    ) {
    }

    // TBD
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'name' => $this->book->name,
            'price' => $this->price,
            'state' => $this->state,
            'author_email' => $this->user->email,
            'reserved' => !empty($this->reservations),
        ];
    }
}
