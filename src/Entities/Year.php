<?php

declare(strict_types=1);

namespace App\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\{HasMany, BelongsTo};

#[Entity()]
class Year
{
    #[Column(type: 'primary')]
    public int $id;

    public function __construct(
        #[Column(type: 'string')]
        public string $name,
        #[BelongsTo(target: School::class)]
        public School $school
    ) {

    }

    #[HasMany(target: User::class)]
    public array $users = [];
}
