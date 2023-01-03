<?php

declare(strict_types=1);

namespace App\Entities\Traits;

use Cycle\Annotated\Annotation\Column;

trait DateTimes
{
    #[Column(type: 'datetime')]
    public \DateTimeImmutable $createdAt;

    #[Column(type: 'datetime', nullable: true)]
    public ?\DateTimeImmutable $updatedAt;
}
