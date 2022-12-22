<?php

namespace App\Entities\Traits;

use Cycle\Annotated\Annotation\Column;
use DateTimeImmutable;

trait DateTimes
{
    #[Column(type: 'datetime')]
    public DateTimeImmutable $createdAt;
        
    #[Column(type: 'datetime', nullable: true)]
    public ?DateTimeImmutable $updatedAt;
}
