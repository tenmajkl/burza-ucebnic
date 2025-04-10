<?php

declare(strict_types=1);

namespace App\Entities;

use App\Contracts\Auth;
use App\Contracts\ORM;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Lemon\Contracts\Kernel\Injectable;
use Lemon\Kernel\Container;

#[Entity()]
class RatingAbility implements Injectable, \JsonSerializable
{
    #[Column(type: 'primary')]
    public int $id;

    public function __construct(
        #[BelongsTo(target: User::class)]
        public User $user,
        #[BelongsTo(target: User::class)]
        public User $rated,
    ) {}

    public static function fromInjection(Container $container, mixed $value): ?self
    {
        $user_id = $container->get(Auth::class)->user()->id;

        return $container->get(ORM::class)->getORM()
            ->getRepository(self::class)
            ->findOne([
                'id' => $value,
                'user.id' => $user_id,
            ])
        ;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'rated' => $this->rated->email,
        ];
    }
}
