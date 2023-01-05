<?php

namespace App;

class TokenGenerator
{
    /**
     * Generates token
     * @see @CoolFido
     */
    public static function generate(string $unique): string
    {
        $token = env('TOKEN');
        return implode('', array_rand(str_split(str_shuffle(sha1(time().$unique).$token.'!@#$%')), 64));
    } 
}
