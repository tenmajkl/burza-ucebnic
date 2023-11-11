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

#[Entity]
#[Behavior\CreatedAt(
    field: 'createdAt',
    column: 'created_at'
)]
#[Behavior\UpdatedAt(
    field: 'updatedAt',
    column: 'updated_at'
)]
class User implements Injectable
{
    use DateTimes;
    use Dynamic;
    use InjectableEntity;

    public const RelationToSchool = 'year.school.id';

    #[Column(type: 'primary')]
    public int $id;

    #[HasMany(target: Offer::class)]
    public array $offers = [];

    #[HasMany(target: Reservation::class)]
    public array $reservations = [];

    #[HasMany(target: Rating::class, nullable: true)]
    public array $given_ratings = [];

    #[HasMany(target: Rating::class)]
    public array $received_ratings = [];

    #[HasMany(target: Report::class)]
    public array $given_reports = [];

    #[HasMany(target: Report::class)]
    public array $received_reports = [];

    #[HasMany(target: Ban::class, outerKey: 'admin_id')]
    public array $given_bans = [];

    #[HasMany(target: Ban::class, outerKey: 'banned_id')]
    public array $received_bans = [];

    public function __construct(
        #[Column(type: 'string')]
        public string $email,
        #[Column(type: 'string')]
        public string $password,
        #[BelongsTo(target: Year::class, nullable: true)]
        public ?Year $year,
        #[Column(type: 'int')]
        public int $role,
    ) {
    }

    public function countRating()
    {
        $sum = 0;
        foreach ($this->received_ratings as $rating) {
            $sum += $rating->rating;
        }

        return $sum;
    }

    public function isBanned()
    {
        return 1 === count(
            array_filter($this->received_bans, fn ($ban) => $ban->isActive())
        );
    }

    public function banUntil(): ?\DateTimeImmutable
    {
        return $this->getBan()?->expiresAt ?? null;
    }

    public function getBan(): ?Ban
    {
        $now = new \DateTimeImmutable();

        foreach ($this->received_bans as $ban) {
            if ($ban->expiresAt > $now) {
                return $ban;
            }
        }

        return null;
    }
}
