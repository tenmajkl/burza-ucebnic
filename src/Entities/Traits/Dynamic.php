<?php

declare(strict_types=1);

namespace App\Entities\Traits;

trait Dynamic
{
    private array $data = [];

    public function __set(string $name, mixed $value): void
    {
        $this->data[$name] = $value;
    }

    public function __get(string $name)
    {
        return $this->data[$name] ?? throw new \Exception('Undefined property '.$name);
    }
}
