<?php

namespace App\Entities;

use Cycle\Annotated\Annotation\{Entity, Column};
use Cycle\Annotated\Annotation\Relation\HasOne;

#[Entity]
class Rating
{
    #[Column(type: 'primary')]
    public int $id;

    #[HasOne(target: User::class)]
    public User $author;

    #[HasOne(target: User::class)]
    public User $rated;

    public function __construct(
        #[Column(type: 'int')]
        public int $rating,
    ) {

    }
}
