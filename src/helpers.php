<?php

declare(strict_types=1);

if (!function_exists('diff')) {
    /** @deprecated */
    function diff(DateTimeImmutable $date): string
    {
        return $date->format("j. n. o G:i");
    }
}

if (!function_exists('sum')) {
    function sum(array $array, callable $fn): int
    {
        $count = count($array);

        return
            0 === $count
            ? 0
            : intdiv(array_reduce($array, fn ($acc, $item) => $acc + $fn($item)), $count);
    }
}

if (!function_exists('url')) {
    function url(): string
    {
        return
            (empty($_SERVER['HTTPS'])
                ? 'http'
                : 'https'
            )
            .'://'
            .$_SERVER['HTTP_HOST'];
    }
}

function _time(): int 
{
    return (new DateTimeImmutable())->getTimestamp();
}
