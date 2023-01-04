<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\DateTimes;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\ORM\Entity\Behavior;

#[Entity()]
#[Behavior\CreatedAt(
    field: 'createdAt',
    column: 'created_at'
)]
#[Behavior\UpdatedAt(
    field: 'updatedAt',
    column: 'updated_at'
)]
class Reservation
{
    use DateTimes;

    #[Column(type: 'primary')]
    public int $id;

    #[Column(type: 'string')]
    public string $hash;

    public function __construct(
        #[BelongsTo(target: Offer::class)]
        public Offer $offer,
        #[BelongsTo(target: User::class)]
        public User $user,
        #[Column(type: 'bool')]
        public bool $active
    ) {
        // Profi token generation coolfido aproves
        $this->hash = str_shuffle(str_shuffle($this->id.$this->offer->id.rand().time()).base64_encode(sha1(rand().time().$user->id)));
    }
}
