<?php

declare(strict_types=1);

namespace App\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\ORM\Entity\Behavior;

#[Entity]
#[Behavior\CreatedAt(
    field: 'createdAt',
    column: 'created_at'
)]
class Message implements \JsonSerializable
{
    #[Column(type: 'datetime')]
    public \DateTimeImmutable $createdAt;

    #[Column(type: 'primary')]
    public int $id;

    public function __construct(
        #[Column(type: 'string')]
        public string $content,
        #[BelongsTo(target: User::class)]
        public User $author,
        #[BelongsTo(target: Reservation::class)]
        public Reservation $reservation,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'author' => $this->author->email,
            'createdAt' => $this->createdAt->format('Y-m-dTH:i:s'),
        ];
    }
}
