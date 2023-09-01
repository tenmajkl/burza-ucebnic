<?php

namespace App\Entities;

use Exception;

enum ReservationStatus: int
{
    case Waiting = 0;

    case Active = 1;

    case Denied = 2;

    static function typecast(mixed $value): self
    {
        return self::tryFrom($value);
    }
}
