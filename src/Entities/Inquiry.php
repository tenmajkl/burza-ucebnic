<?php

namespace App\Entities;

use App\Entities\Traits\InjectableEntity;
use Cycle\Annotated\Annotation\{Entity, Column};
use Cycle\Annotated\Annotation\Relation\{HasOne, BelongsTo};
use JsonSerializable;
use Lemon\Contracts\Kernel\Injectable;

#[Entity]
class Inquiry implements JsonSerializable, Injectable
{
    use InjectableEntity;

    public const RelationToSchool = 'book.subjects.year.school.id';

    #[Column(type: 'primary')]
    public int $id;

    public function __construct(
        #[BelongsTo(target: Book::class)]
        public Book $book,
        #[BelongsTo(target: User::class)]
        public User $user,
        #[Column(type: 'int')]
        public int $expected_price,
    ) {

    }

    public function jsonSerialize(): mixed
    {
        return [
            'id'=> $this->id,
            'book' => $this->book->jsonSerialize(),
            'max_price' => $this->expected_price,
        ];
    }
}
