<?php

use Carbon\Carbon;

if (!function_exists('diff')) {
    function diff(DateTimeImmutable $date): string
    {
        return (new Carbon($date))->diffForHumans();
    }
}
