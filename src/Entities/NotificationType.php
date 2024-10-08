<?php

declare(strict_types=1);

namespace App\Entities;

enum NotificationType: int
{
    case Wishlist = 0;

    case Rating = 1;

    case ActiveReseration = 2;

    case NewReservation = 3;

    case Editing = 4;

    case NewMessage = 5;

    /**
     * For cycle ORM.
     */
    public static function typecast(mixed $value): self
    {
        return match ($value) {
            0 => self::Wishlist,
            1 => self::Rating,
            2 => self::ActiveReseration,
            3 => self::NewReservation,
            4 => self::Editing,
            5 => self::NewMessage,
            default => throw new \Exception('Unexpected value: '.$value),
        };
    }
}
