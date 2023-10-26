<?php

use Carbon\Carbon;

if (!function_exists('diff')) {
    function diff(DateTimeImmutable $date): string
    {
        return (new Carbon($date))->diffForHumans();
    }
}

if (!function_exists('sum')) {
    function sum(array $array, callable $fn): int 
    {
        $count = count($array);
        return 
            $count === 0
            ? 0
            : intdiv(array_reduce($array, fn($acc, $item) => $acc + $fn($item)), $count) 
        ;
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
            .$_SERVER['HTTP_HOST']
        ;
    }
}
