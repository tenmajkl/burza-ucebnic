<?php

namespace App\Entities;

use Cycle\Annotated\Annotation\{Entity, Column};
use Cycle\Annotated\Annotation\Relation\HasMany;

#[Entity()]
class Year
{
    #[Column(type: 'primary')]
    public int $id;

    #[Column()]
    public string $name;

    #[HasMany(target: User::class)]
    public array $users;
}
