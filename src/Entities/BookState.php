<?php

namespace App\Entities;

enum BookState: string
{
    case Any = 'any';
    case New = 'new';
    case Used = 'used';
    case Damaged = 'damaged';

    public static function fromId(int $id): ?self
    {
        return match ($id) {
            0 => self::Any,
            1 => self::New,
            2 => self::Used,
            3 => self::Damaged,
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
