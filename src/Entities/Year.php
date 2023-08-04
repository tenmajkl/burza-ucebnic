<?php

declare(strict_types=1);

namespace App\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\{HasMany, BelongsTo, ManyToMany};

#[Entity()]
class Year implements \JsonSerializable
{
    #[Column(type: 'primary')]
    public int $id;

    #[HasMany(target: User::class, nullable: true)]
    public ?array $users = [];

    #[HasMany(target: Subject::class)]
    public array $subjects = [];

    public function __construct(
        #[Column(type: 'string')]
        public string $name,
        #[BelongsTo(target: School::class)]
        public School $school
    ) {

    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'books' => array_reduce(
                $this->subjects, 
                fn($acc, $subject) => array_merge(
                    $acc, 
                    array_map(fn($book) => $book->jsonSerialize(), $subject->books)
            ), []),
        ];        
    }
}
