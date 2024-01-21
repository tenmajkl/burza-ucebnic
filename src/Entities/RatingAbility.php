<?php

namespace App\Entities;

use Cycle\Annotated\Annotation\{Entity, Column};

#[Entity]
class RatingAbility
{
    #[Column(type: 'primary')]
    public int $id;

    public function __construct(
        #[Column(target: User::class)]
        public User $user,
        #[Column(target: User::class)]
        public User $rated,
    ) {

    }
}
