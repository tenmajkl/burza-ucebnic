<?php

declare(strict_types=1);

namespace App\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\ORM\Entity\Behavior;

#[Entity]
#[Behavior\CreatedAt(
    field: 'createdAt',
    column: 'created_at'
)]
class Ban
{
    #[Column(type: 'primary')]
    public int $id;

    #[Column(type: 'datetime')]
    public \DateTimeImmutable $createdAt;

    public function __construct(
        #[Column(type: 'string')]
        public string $reason,
        #[Column(type: 'datetime')]
        public \DateTimeImmutable $expiresAt,
        #[BelongsTo(target: User::class, innerKey: 'banned_id')]
        public User $banned,
        #[BelongsTo(target: User::class, innerKey: 'admin_id')]
        public User $admin,
        #[Column(type: 'boolean')]
        public int $active = 1,
    ) {}

    public function isActive()
    {
        return $this->active && $this->expiresAt > new \DateTimeImmutable();
    }
}
