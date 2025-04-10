<?php

declare(strict_types=1);

namespace App\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Relation\ManyToMany;

#[Entity()]
class Subject implements \JsonSerializable
{
    #[Column(type: 'primary')]
    public int $id;

    #[BelongsTo(Year::class)]
    public Year $year;

    #[ManyToMany(target: Book::class, through: SubjectBook::class)]
    public array $books = [];

    public function __construct(
        #[Column(type: 'string')]
        public string $name,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'year' => [
                'id' => $this->year->id,
                'name' => $this->year->name,
            ],
        ];
    }
}
