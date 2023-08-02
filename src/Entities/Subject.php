<?php

declare(strict_types=1);

namespace App\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\{HasMany, BelongsTo, ManyToMany};
use JsonSerializable;

#[Entity()]
class Subject implements JsonSerializable
{
    #[Column(type: 'primary')]
    public int $id;
 
    #[BelongsTo(Year::class)]
    public Year $year;

    public function __construct(
        #[Column(type: 'string')]
        public string $name,
    ) {
        
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'year' => [
                'id' => $this->year->id,
                'name' => $this->year->name,
            ]
        ];
    }

}
