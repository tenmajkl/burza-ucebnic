<?php

namespace App\Entities;

use Cycle\Annotated\Annotation\{Entity, Column};
use Cycle\Annotated\Annotation\Relation\HasOne;

#[Entity]
class Inquiry
{
    #[Column(type: 'primary')]
    public int $id;

    #[HasOne(target: Book::class)]
    public Book $book;

    #[HasOne(target: User::class)]
    public User $user;

    public function __construct(
        #[Column(type: 'int')]
        public int $expected_price,
    ) {

    }
    
}
