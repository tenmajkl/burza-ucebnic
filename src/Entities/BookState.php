<?php

namespace App\Entities;

enum BookState: string
{
    case New = 'new';
    case Used = 'used';
    case Damaged = 'damaged';

    public static function fromId(int $id): ?self
    {
        return match ($id) {
            0 => self::New,
            1 => self::Used,
            2 => self::Damaged,
            default => null 
        };
    }

    /**
     * For cycle ORM
     */
    public static function typecast(mixed $value): self
    {
        return self::fromId((int) $value);
    } 
}
