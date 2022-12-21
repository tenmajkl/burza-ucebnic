<?php

namespace App\Entities;

use Cycle\Annotated\Annotation\{Entity, Column};
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\ORM\Entity\Behavior;
use DateTimeImmutable;

#[Entity]
#[Behavior\CreatedAt(
    field: 'createdAt',
    column: 'created_at'
)]
#[Behavior\UpdatedAt(
    field: 'updatedAt', 
    column: 'updated_at'
)]
class User
{
    #[Column(type: 'primary')]
    public int $id;

    #[Column(type: 'datetime')]
    public DateTimeImmutable $createdAt;
        
    #[Column(type: 'datetime', nullable: true)]
    public ?DateTimeImmutable $updatedAt;

    public function __construct(
        #[Column()]
        public string $email,
        #[Column()]
        public string $password,
        #[BelongsTo(target: Year::class)]
        public Year $year,
    ) {

    }
}
