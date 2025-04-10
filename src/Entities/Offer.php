<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\DateTimes;
use App\Entities\Traits\Dynamic;
use App\Entities\Traits\InjectableEntity;
use App\Scopes\NotBoughtScope;
use App\Zests\Auth;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Cycle\ORM\Entity\Behavior;
use Lemon\Contracts\Kernel\Injectable;

/**
 * AHOJTE LIDI, MAM PRO VAS VELICE ZAJIMAVOU NABIDKU.
 */
#[Entity(scope: NotBoughtScope::class)]
#[Behavior\CreatedAt(
    field: 'createdAt',
    column: 'created_at'
)]
#[Behavior\UpdatedAt(
    field: 'updatedAt',
    column: 'updated_at'
)]
class Offer implements \JsonSerializable, Injectable
{
    use DateTimes;
    use Dynamic;
    use InjectableEntity;

    public const RelationToSchool = 'book.subjects.year.school.id';

    #[Column(type: 'primary')]
    public int $id;

    #[HasMany(target: Reservation::class)]
    public array $reservations = [];

    #[HasMany(target: Report::class)]
    public array $reports = [];

    #[Column(type: 'datetime', nullable: true)]
    public ?\DateTimeImmutable $boughtAt;

    #[BelongsTo(target: User::class, nullable: true)]
    public ?User $buyer;

    /**
     * Whenever currently logged user can make reservation, thus its not saved in db. This value is generated manualy using function canUserMakeReservation.
     */
    public bool $can_be_reserved = false;

    public function __construct(
        #[BelongsTo(target: Book::class)]
        public Book $book,
        #[Column(type: 'int')]
        public int $price,
        #[Column(type: 'int', typecast: BookState::class)]
        public BookState $state,
        #[BelongsTo(target: User::class, nullable: true)]
        public User $user,
    ) {}

    /**
     * Sets internal value can_be_reserved.
     */
    public function canUserMakeReservation(User $user): self
    {
        if ($this->user->id === $user->id) {
            $this->can_be_reserved = false;

            return $this;
        }

        foreach ($this->reservations as $reservation) {
            if ($reservation->user->id === $user->id) {
                $this->can_be_reserved = false;

                return $this;
            }
        }

        $this->can_be_reserved = true;

        return $this;
    }

    // TBD
    public function jsonSerialize(): mixed
    {
        // this is terrible practice and I should be blamed however it works and its faster than the "cleaner way"
        $this->canUserMakeReservation(Auth::user());

        return [
            'id' => $this->id,
            'name' => $this->book->name,
            'price' => $this->price,
            'state' => $this->state->name(),
            'author_email' => $this->user->email,
            'author_rating' => $this->user->rating,
            'created_at' => diff($this->createdAt),
            'reservations' => count($this->reservations),
            'can_be_reserved' => $this->can_be_reserved,
        ];
    }
}
