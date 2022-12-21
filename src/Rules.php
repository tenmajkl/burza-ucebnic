<?php

namespace App;

use Lemon\Config\Exceptions\ConfigException;
use Lemon\Contracts\Validation\Validator;


/**
 * Custom validation rules
 */
class Rules
{
    public function __construct(
        Validator $validator
    ) {
        $validator->rules()
                  ->rule('school_email', [$this, 'schoolEmail']);
    }

    public function schoolEmail(string $email): bool
    {
        return str_ends_with($email, env('EMAIL') ?? throw new ConfigException('Undefined env variable EMAIL'));
    }
}
