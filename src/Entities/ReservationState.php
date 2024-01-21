<?php

declare(strict_types=1);

namespace App\Entities;

enum ReservationState: int
{
    case Waiting = 0;

    case Active = 1;

    case Denied = 2;

    public static function typecast(mixed $value): self
    {
        return self::tryFrom($value);
    }
}
