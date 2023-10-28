<?php

declare(strict_types=1);

namespace App\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

#[Entity()]
class SubjectBook
{
    #[Column(type: 'primary')]
    public int $id;
}
