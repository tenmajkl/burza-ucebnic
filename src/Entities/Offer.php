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
    public const States = [
        '0' => 'new',
        '1' => 'used',
        '2' => 'damaged',
    ];

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
        #[Column(type: 'int')]
        public int $state,
        #[BelongsTo(target: User::class, nullable: true)]
        public User $user,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'name' => $this->book->name,
            'subject' => $this->book->subject->id,
            'year' => $this->book->year,
            'price' => $this->price,
            'description' => $this->description,
            'author_email' => $this->author->email,
            'reserved' => !empty($this->reservations),
        ];
    }
}
