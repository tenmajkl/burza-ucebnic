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
class User implements Injectable, \JsonSerializable
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

    #[HasMany(target: Report::class)]
    public array $given_reports = [];

    #[HasMany(target: Ban::class, outerKey: 'admin_id')]
    public array $given_bans = [];

    #[HasMany(target: Ban::class, outerKey: 'banned_id')]
    public array $received_bans = [];

    #[HasMany(target: RatingAbility::class, outerKey: 'user_id')]
    public array $rating_abilities = [];

    #[HasMany(target: RatingAbility::class, outerKey: 'rated_id')]
    public array $unresolved_given_ratings = [];

    public function __construct(
        #[Column(type: 'string')]
        public string $email,
        #[Column(type: 'string')]
        public string $password,
        #[Column(type: 'int')]
        public int $role,
        #[Column(type: 'string', nullable: true)]
        public ?string $verify_token = null,
        #[BelongsTo(target: Year::class, nullable: true)]
        public ?Year $year = null,
        #[Column(type: 'int')]
        public int $rating = 0,
    ) {

    }

    /**
     * Returns whole e-mail address
     */
    public function email(): string
    {
        $school = $this->year->school;
        $host = $this->year->name === 'teachers' ? $school->admin_email : $school->email;
        return $this->email.'@'.$host;
    }

    public function isBanned(): bool
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

    public function getReports(): array 
    {
        $result = [];
        foreach ($this->offers as $offer) {
            foreach ($offer->reports as $report) {
                $result[] = $report;
            }
        }

        return $result;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'email' => $this->email,
        ];
    }
}
