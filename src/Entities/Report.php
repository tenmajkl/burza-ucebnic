<?php

namespace App\Entities;

use Cycle\Annotated\Annotation\{Entity, Column};
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\ORM\Entity\Behavior;

#[Entity]
#[Behavior\CreatedAt(
    field: 'createdAt',
    column: 'created_at'
)]
class Report
{
    #[Column(type: 'primary')]
    public int $id;

    #[BelongsTo(target: User::class)]
    public User $author;

    #[BelongsTo(target: User::class)]
    public User $reported;

    #[Column(type: 'datetime')]
    public \DateTimeImmutable $createdAt;

    public function __construct(
        #[Column(type: 'string')]
        public string $reason,
    ) {

    }
}
