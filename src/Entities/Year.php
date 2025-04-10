<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\InjectableEntity;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Lemon\Contracts\Kernel\Injectable;

#[Entity()]
class Year implements \JsonSerializable, Injectable
{
    use InjectableEntity;

    public const RelationToSchool = 'school.id';

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
        public School $school,
        #[Column(type: 'boolean')]
        public bool|int $visible = true, // whenever this year has any books that can be sold
    ) {}

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'books' => array_reduce(
                $this->subjects,
                fn ($acc, $subject) => array_merge(
                    $acc,
                    array_map(fn ($book) => $book->jsonSerializeWithAverages(), $subject->books)
                ),
                []
            ),
            'visible' => $this->visible,
        ];
    }
}
