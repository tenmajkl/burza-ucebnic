<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\DateTimes;
use App\Entities\Traits\Dynamic;
use App\Entities\Traits\InjectableEntity;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Cycle\ORM\Entity\Behavior;
use Lemon\Contracts\Kernel\Injectable;

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
class Offer implements \JsonSerializable, Injectable
{
    use DateTimes, Dynamic, InjectableEntity;

    const RelationToSchool = 'book.subjects.year.school.id';

    #[Column(type: 'primary')]
    public int $id;

    #[HasMany(target: Reservation::class)]
    public array $reservations = [];

    #[Column(type: 'datetime', nullable: true)]
    public ?\DateTimeImmutable $updatedAt;

    #[Column(type: 'datetime', nullable: true)]
    public ?\DateTimeImmutable $boughtAt;

    #[BelongsTo(target: User::class, nullable: true)]
    public ?User $buyer;

    #[HasMany(target: Rating::class, nullable: true)]
    public ?array $ratings = [];

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
            'author_rating' => $this->user->countRating(),
            'created_at' => diff($this->createdAt),
            'reservations' => count($this->reservations),
        ];
    }

    public function canRate(User $user): bool
    {
        // ayo what the fuck is this
        return ($this->buyer?->id === $user->id 
            || (array_filter($this->reservations, fn(Reservation $item) => $item->user->id === $user->id)[-2] ?? null)?->state === ReservationState::Denied)
            && (array_filter($this->ratings, fn(Rating $rating) => $rating->author->id === $user->id) === [])
        ;
    }
}
