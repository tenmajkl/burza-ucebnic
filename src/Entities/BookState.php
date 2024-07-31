<?php

declare(strict_types=1);

namespace App\Entities;

enum BookState: int 
{
    case New = 0;
    case Used = 1;
    case Damaged = 2;

    public static function fromId(int $id): ?self
    {
        return match ($id) {
            0 => self::New,
            1 => self::Used,
            2 => self::Damaged,
            default => null
        };
    }

    public function name(): string
    {
        return match($this) {
            self::New => 'new',
            self::Used => 'used',
            self::Damaged => 'damaged',
        };
    }

    public static function all(): array
    {
        return [
            -1 => 'any',
            0 => 'new',
            1 => 'used',
            2 => 'damaged',
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
