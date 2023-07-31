<?php

declare(strict_types=1);

namespace App\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\{HasMany, BelongsTo, ManyToMany};

#[Entity()]
class Subject
{
    #[Column(type: 'primary')]
    public int $id;

    #[HasMany(target: Book::class)]
    public array $books = [];

    #[BelongsTo(Year::class)]
    public Year $year;

    public function __construct(
        #[Column(type: 'string')]
        public string $name,
    ) {
        
    }
}
