<?php

declare(strict_types=1);

namespace App\Entities;

enum BookState: int 
{
    case Any = 0;
    case New = 1;
    case Used = 2;
    case Damaged = 3;

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

    public function name(): string
    {
        return match($this) {
            self::Any => 'any',
            self::New => 'new',
            self::Used => 'used',
            self::Damaged => 'damaged',
        };
    }

    public static function all(): array
    {
        return [
            'any',
            'new',
            'used',
            'damaged',
        ];
    }

    /**
     * For cycle ORM.
     */
    public static function typecast(mixed $value): self
    {
        return self::fromId((int) $value);
    }
}
