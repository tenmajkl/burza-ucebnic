<?php

namespace App\Entities;

use App\Contracts\ORM;
use App\Contracts\Auth;
use Cycle\Annotated\Annotation\{Entity, Column};
use Lemon\Contracts\Kernel\Injectable;
use Lemon\Kernel\Container;

#[Entity()]
class RatingAbility implements Injectable
{
    #[Column(type: 'primary')]
    public int $id;

    public function __construct(
        #[BelongsTo(target: User::class)]
        public User $user,
        #[BelongsTo(target: User::class)]
        public User $rated,
    ) {

    }

    public static function fromInjection(Container $container, mixed $value): ?self
    {
        $user_id = $container->get(Auth::class)->user()->id;
        return $container->get(ORM::class)->getORM()
            ->getRepository(self::class)
            ->findOne([
                'id' => $value,
                'user.id' => $user_id,
            ]);
    }
}
