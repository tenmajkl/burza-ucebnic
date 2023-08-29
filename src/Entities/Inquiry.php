<?php

namespace App\Entities;

use Cycle\Annotated\Annotation\{Entity, Column};
use Cycle\Annotated\Annotation\Relation\{HasOne, BelongsTo};

#[Entity]
class Inquiry
{
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
    
}
