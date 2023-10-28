<?php

declare(strict_types=1);

namespace App;

class TokenGenerator
{
    /**
     * Generates token.
     */
    public static function generate(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }
}
