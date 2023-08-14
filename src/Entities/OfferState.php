<?php

namespace App\Entities;

enum OfferState: string
{
    case New = 'new';
    case Used = 'used';
    case Damaged = 'damaged';

    public static function fromId(int $id): self
    {
        return match ($id) {
            0 => self::New,
            1 => self::Used,
            2 => self::Damaged,
            default => throw new \InvalidArgumentException("Invalid offer state id: $id"),
        };
    }

    /**
     * For cycle ORM
     */
    public static function typecast(int $value): self
    {
        return self::fromId($value);
    } 
}
