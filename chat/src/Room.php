<?php

namespace App;

use stdClass;

class Room
{
    private array $users = [];

    public function __construct(
        public readonly int $id,
    ) {

    }

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
        return count($this->users) === 0;
    }

    public function send(stdClass $message): self
    {
        foreach ($this->users as $user) {
            $user->send($message);
        }

        return $this;
    }

}
