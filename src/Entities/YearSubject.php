<?php

namespace App\Entities;

use Cycle\Annotated\Annotation\{Entity, Column};

#[Entity]
class YearSubject
{
    #[Column(type: 'primary')]
    public int $id;
}
