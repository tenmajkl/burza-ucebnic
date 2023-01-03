<?php

declare(strict_types=1);

namespace App\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Relation\HasMany;

#[Entity]
class Book
{
    #[Column(type: 'primary')]
    public int $id;

    #[HasMany(target: Offer::class)]
    public array $offers = [];

    public function __construct(
        #[Column(type: 'string')]
        public string $name,
        #[Column(type: 'string')]
        public string $author,
        #[Column(type: 'int')]
        public int $release_year,
        #[BelongsTo(target: Year::class)]
        public Year $year,
        #[BelongsTo(target: Subject::class)]
        public Subject $subject,
    ) {
    }
}
