<?php

declare(strict_types=1);

namespace App\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasMany;

#[Entity()]
class Year
{
    #[Column(type: 'primary')]
    public int $id;

    #[Column(type: 'string')]
    public string $name;

    #[HasMany(target: User::class)]
    public array $users = [];
}
