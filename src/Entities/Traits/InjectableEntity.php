<?php

namespace App\Entities\Traits;

use App\Contracts\Auth;
use App\Contracts\ORM;
use Lemon\Contracts\Kernel\Injectable;
use Lemon\Kernel\Container;

trait InjectableEntity
{
    public static function fromInjection(Container $container, mixed $value): ?self
    {
        return $container->get(ORM::class)->getORM()->getRepository(self::class)->findOne(['id' => $value, self::RelationToSchool => $container->get(Auth::class)->user()->year->school->id]);
    }
}
