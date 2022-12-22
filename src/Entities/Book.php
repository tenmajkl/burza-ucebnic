<?php

namespace App\Entities;

use Cycle\Annotated\Annotation\{Entity, Column};
use Cycle\Annotated\Annotation\Relation\{BelongsTo, HasMany};

#[Entity]
class Book
{
    #[Column(type: 'primary')]
    public int $id;

    #[HasMany(target: Offer::class)]
    public array $offers;

    public function __construct(
        #[Column()]
        public string $name,
        #[Column()]
        public string $author,
        #[Column()]
        public int $release_year,
        #[BelongsTo(target: Year::class)]
        public Year $year
    ) {

    }
}
