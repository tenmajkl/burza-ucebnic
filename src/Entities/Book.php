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

    #[ManyToMany(target: Subject::class, though: SubjectBook::class)]
    public array $subjects = [];

    public function __construct(
        #[Column(type: 'string')]
        public string $name,
        #[Column(type: 'string')]
        public string $author,
        #[Column(type: 'int')]
        public int $release_year,
        #[Column(type: 'string')]
        public string $publisher,
    ) {
    }
}
