<?php

declare(strict_types=1);

namespace App;

class Room
{
    private array $users = [];

    public function __construct(
        public readonly int $id,
    ) {}

    public function addUser(User $user): self
    {
        $this->users[$user->connection->id] = $user;

        return $this;
    }

    public function removeUser(User $user): self
    {
        unset($this->users[$user->connection->id]);

        return $this;
    }

    public function empty(): bool
    {
        return 0 === count($this->users);
    }

    public function hasJustOneUser(): bool
    {
        return 1 === count($this->users);
    }

    public function send(\stdClass $message): self
    {
        foreach ($this->users as $user) {
            $user->send($message);
        }

        return $this;
    }
}
