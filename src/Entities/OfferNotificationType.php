<?php

namespace App\Entities;

use Exception;

enum OfferNotificationType: int
{
    case Wishlist = 0;

    case Rating = 1;

    case ActiveReseration = 2;

    /**
     * For cycle ORM
     */
    public static function typecast(mixed $value): self
    {
        return match($value) {
            0 => self::Wishlist,
            1 => self::Rating,
            2 => self::ActiveReseration,
            default => throw new Exception('Unexpected value: '.$value),
        };
    } 
}
