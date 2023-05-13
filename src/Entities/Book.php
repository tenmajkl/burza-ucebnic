<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\Dynamic;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Cycle\Annotated\Annotation\Relation\ManyToMany;

#[Entity]
class Book
{
    use Dynamic; 

    #[Column(type: 'primary')]
    public int $id;

    #[HasMany(target: Offer::class)]
    public array $offers = [];

    #[ManyToMany(target: Year::class, though: YearBook::class)]
    public array $years = [];

    public function __construct(
        #[Column(type: 'string')]
        public string $name,
        #[Column(type: 'string')]
        public string $author,
        #[Column(type: 'int')]
        public int $release_year,
        #[BelongsTo(target: Subject::class)]
        public Subject $subject,
    ) {
    }
}
