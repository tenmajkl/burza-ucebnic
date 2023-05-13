<?php

namespace App\Entities;

use App\Entities\Traits\DateTimes;
use Cycle\Annotated\Annotation\{Entity, Column};
use Cycle\Annotated\Annotation\Relation\HasOne;

#[Entity]
class Message
{
    use DateTimes;

    #[Column(type: 'primary')]
    public int $id;

    #[HasOne(target: User::class)]
    public User $from;

    #[HasOne(target: User::class)]
    public User $to;

    public function __construct(
        #[Column(type: 'string')]
        public string $content,
    ) {

    }
}
