<?php

namespace App\Entities;

use Cycle\Annotated\Annotation\{Entity, Column};

#[Entity]
class YearBook
{
    #[Column(type: 'primary')]
    public int $id;
}
