<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\DateTimes;
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
#[Entity]
#[Behavior\SoftDelete(
    field: 'deletedAt',
    column: 'deleted_at'
)]
class Offer implements \JsonSerializable
{
    use DateTimes;

    #[Column(type: 'primary')]
    public int $id;

    #[HasMany(target: Reservation::class)]
    public array $reservations;

    #[Column(type: 'datetime', nullable: true)]
    public ?\DateTimeImmutable $updatedAt;

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

    public function jsonSerialize(): mixed
    {
        return [
            'name' => $this->book->name,
            'subject' => $this->book->subject,
            'year' => $this->book->year,
            'price' => $this->price,
            'description' => $this->description,
            'author_email' => $this->author->email,
            'reserved' => !empty($this->reservations),
        ];
    }
}
